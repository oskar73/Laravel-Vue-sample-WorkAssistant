<?php

namespace App\Services;

use App\Models\GraphicDesign\UserDesign;
use App\Repositories\FontRepository;
use App\Repositories\GraphicDesignRepository;
use App\Repositories\LogotypeRepository;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SVG\Nodes\Structures\SVGStyle;
use SVG\SVG;

class DesignService
{

    protected Sanitizer $sanitizer;
    protected FontRepository $fonts;
    const PREVIEW_QUALITY = 1;

    public function __construct(Sanitizer $sanitizer, FontRepository $fonts)
    {
        $this->sanitizer = $sanitizer;
        $this->fonts = $fonts;
    }

    public function getSvgForPreview(UserDesign $design): ?SVG
    {
        try {
            // Get svg path
            $svg = SVG::fromString($design->getContent());

            $fonts = $this->getSvgFonts($svg);

            // Include fonts to svg as css style
            foreach ($fonts as $font) {
                // Encode font to base64
                if (Storage::disk($this->fonts::DISK)->exists($font->public_path)) {
                    $path = Storage::disk($this->fonts::DISK)->url($font->public_path);

                    $encodedFont = base64_encode(file_get_contents($path));

                    // Add dynamic font style
                    $svg->getDocument()->addChild((new SVGStyle(
                        "@font-face {font-family: {$font->name}; src:url('data:font/{$font->extension};base64,{$encodedFont}');"
                    )));
                } else {
                    Log::channel('single')->error("LogotypeService->getSvgForPreview/font {$font->name} does not exist");
                }
            }

            return $svg;
        } catch (\Exception $exception) {
            $ID = time();
            Log::channel('single')->error("#{$ID}: LogotypeService->getSvgForPreview/font {$font->name} does not exist");
            Log::error("#{$ID}");
            Log::error($exception);

            return null;
        }
    }

    public function getSvgFonts(SVG $svg): Collection
    {
        $fontNames = [];
        $content = $this->sanitizer->sanitize($svg->toXMLString());
        $exploded = explode('font-family:', $content);

        // Find font names
        foreach ($exploded as $key => $item) {
            if ($key === 0) {
                continue;
            }

            // Search first ; symbol
            $symbol1 = ';';
            $search1 = (int)strpos($item, $symbol1);

            // Search first " symbol
            // (if font-family in end in inline style)
            $symbol2 = '"';
            $search2 = (int)strpos($item, $symbol2);

            if ($search2 < $search1) {
                $font = trim(substr($item, 0, $search2));
            } else {
                $font = trim(Arr::first(explode(';', $item)));
            }

            if (is_string($font)) {
                $fontNames[] = $font;
            }
        }

        // Search fonts in DB
        return $this->fonts->findWhereIn('name', $fontNames);
    }

    public function getDesignPreview(UserDesign $userDesign, bool $addDataImage = true): string
    {
        $svg = $this->getSvgForPreview($userDesign);

        try {
            $encoded = base64_encode(BrowserShot::html($svg->toXMLString())
                ->hideBackground()
                ->windowSize((int)$svg->getDocument()->getAttribute('width'), (int)$svg->getDocument()->getAttribute('height'))
                ->setScale(self::PREVIEW_QUALITY)
                ->noSandbox()
                ->screenshot());
        } catch (\Exception $e) {
            throw $e;
        }

        if ($addDataImage) {
            return 'data:image/' . GraphicDesignRepository::PREVIEW_EXTENSION . ';base64,' . $encoded;
        }

        return $encoded;
    }
}
