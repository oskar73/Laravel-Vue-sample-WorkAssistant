<?php

namespace App\Repositories;

use App\Models\GraphicDesign\GraphicDesign;
use App\Models\GraphicDesign\GraphicDesignGroup;
use App\Models\GraphicDesignCategory;
use App\Models\Logo\LogoCategory;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GraphicDesignRepository extends BaseRepository
{

    public $model = GraphicDesign::class;

    protected FontRepository $font;

    protected Sanitizer $sanitizer;

    const DISK = 's3-pub-bizinabox';
    const EXTENSION = 'svg';
    const DIRECTORY = 'designs';
    const PREVIEW_DIRECTORY = 'designs/previews/';
    const PREVIEW_EXTENSION = 'png';
    const COMPARE_FONTS_PERCENTAGE = 65;

    public function __construct(FontRepository $font, Sanitizer $sanitizer)
    {
        $this->font = $font;
        $this->sanitizer = $sanitizer;

        parent::__construct();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $svgContent = $data['design']->get();
            $logotypeFile = $this->actualizeFontNames($svgContent, $data['fonts']);

            $previewFile = $data['preview'];

            $request = $data['request'];

            $hash = $this->getHash($logotypeFile);
            $path = $this->getPath($hash);

            // Save logo to disk
            $this->saveToDisk($logotypeFile, $hash);
            // Save preview image to disk
            $preview = '';
            if ($previewFile) {
                $image = json_decode($previewFile)->output->image;
                $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
                $image = base64_decode($image);
                $name = $hash . ".png";
                Storage::disk(self::DISK)->put(self::PREVIEW_DIRECTORY . $name, $image);
                $preview = Storage::disk(self::DISK)->url(self::PREVIEW_DIRECTORY . $name);
            }

            // Check existing logo
            $design = $this->first('hash', $hash);

            // Create record in DB
            if (! $design) {
                $design = $this->model->create([
                    'hash' => $hash,
                    'path' => $path,
                    'name' => $request->name,
                    'status' => $request->status ? 1 : 0,
                    'premium' => $request->premium ? 1 : 0,
                    'recommend' => $request->recommend ? 1 : 0,
                    'order' => $request->order,
                    'global_order' => $request->global_order,
                    'tutorial_id' => $request->tutorial,
                    'graphic_id' => $request->graphic_id,
                    'preview' => $preview,
                ]);
            }

            $pair_ids = collect($request->pair_ids ?? [])->filter(function ($item, $key) use ($request) {
                return $key != $request->graphic_id and ! empty($item);
            })->all();

            foreach ($pair_ids as $pair_id) {
                GraphicDesignGroup::updateOrCreate([
                    'owner_id' => $design->id,
                    'pair_id' => $pair_id,
                ]);
            }

            foreach ($request->category_ids as $category_id) {
                GraphicDesignCategory::updateOrCreate(
                    ['design_id' => $design->id, 'category_id' => $category_id],
                    ['status' => true],
                );
            }

            return $design;
        });
    }

    protected function actualizeFontNames(string $svg, array $fonts): string
    {
        $splitByFonts = explode('font-family="', $svg);
        $existingFonts = $this->font->all();

        foreach ($splitByFonts as $key => $svgChunk) {
            if ($key === 0) {
                continue;
            }

            $fontsStatistics = [];
            $fontNameInSvg = substr($svgChunk, 0, strpos($svgChunk, '"'));
            $fontNameInSvgForCompare = str_replace('-', ' ', str_replace('\'', '', $fontNameInSvg));

            foreach ($fonts as $font) {
                if (! is_object($font)) {
                    continue;
                }

                // Get original font name
                $originalFontName = $this->font->getFontName($font->getRealPath());

                // Get font name with family
                $fullFontName = $this->font->getFullFontName($font->getRealPath());

                // Compare font names (original name and name from svg content)
                similar_text($originalFontName, $fontNameInSvgForCompare, $percentage);

                if ((int)$percentage > self::COMPARE_FONTS_PERCENTAGE) {
                    $this->replaceFontName($svg, $fontNameInSvg, $fullFontName);

                    break;
                }
            }

            foreach ($existingFonts as $existingFont) {
                // Compare font names (original name and name from svg content)
                $existingFontNameV1 = preg_replace('/\W\w+\s*(\W*)$/', '$1', $existingFont->name);

                similar_text($existingFontNameV1, $fontNameInSvgForCompare, $percentage);

                $fontsStatistics[] = [
                    'id' => $existingFont->id,
                    'name' => $existingFont->name,
                    'percentage' => $percentage,
                ];

                // Compare font names with font family
                $existingFontNameV2 = $existingFont->name;

                similar_text($existingFontNameV2, $fontNameInSvgForCompare, $percentage);

                $fontsStatistics[] = [
                    'id' => $existingFont->id,
                    'name' => $existingFont->name,
                    'percentage' => $percentage,
                ];
            }

            $fontsStatistics = collect($fontsStatistics);
            $maxPercentage = $fontsStatistics->max('percentage');

            if ($maxPercentage > self::COMPARE_FONTS_PERCENTAGE) {
                $similarFont = $fontsStatistics->where('percentage', $maxPercentage)->first();

                $this->replaceFontName($svg, $fontNameInSvg, $similarFont['name']);
            }
        }

        return $svg;
    }

    protected function replaceFontName(string &$svg, string $from, string $to): string
    {
        $svg = Str::replaceFirst($from, $to, $svg);

        $svg = $this->sanitizeSvg($this->sanitizer->sanitize($svg));

        return $svg;
    }

    public function sanitizeSvg(string $svg): string
    {
        $svg = Str::replaceFirst('id="Слой_1"', '', $svg);
        $svg = Str::replaceFirst(
            '<!-- Generator: Adobe Illustrator 23.0.2, SVG Export Plug-In . SVG Version: 6.00 Build 0)  -->',
            '',
            $svg
        );
        $svg = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $svg);

        return $svg;
    }

    /**
     * @param string $preview
     * @param string $hash
     * @return $this
     */
    public function savePreview(string $preview, string $hash): self
    {
        // Get preview name
        $name = "{$hash}.png";

        $image = json_decode($preview)->output->image;
        $image = preg_replace('#^data:image/\w+;base64,#i', '', $image);
        $image = base64_decode($image);

        Storage::disk(self::DISK)->put(self::PREVIEW_DIRECTORY . $name, $image);

        return $this;
    }

    public function getHash(string $logotype): string
    {
        return hash('sha256', $logotype);
    }

    public function saveToDisk(string $logotype, string $hash): bool
    {
        return Storage::disk(self::DISK)->put($this->getPath($hash), $logotype);
    }


    public function getPath($hash): string
    {
        return self::DIRECTORY . '/' . $hash . '.' . self::EXTENSION;
    }

    public function getPreviewPath(string $hash): string
    {
        return $this->getPreviewDir() . $hash . '.' . self::PREVIEW_EXTENSION;
    }

    public function getPreviewFullURL(string $hash): string
    {
        return Storage::disk('s3-pub-bizinabox')->url($hash);
    }

    public function getPreviewUrl(string $hash): string
    {
        return asset(self::PREVIEW_DIRECTORY . $hash . '.' . self::PREVIEW_EXTENSION);
    }

    public function getPreviewPaths($category, iterable $loadedLogos = []): array
    {
        $previews = [];
        if ($category === 'all') {
            $query = $this->model->query();
        } else {
            $category = LogoCategory::findorfail($category);
            $query = $category->logoTypes();
        }
        $hashes = $query->whereNotIn('hash', $loadedLogos)
            ->where('enabled', true)
            ->inRandomOrder()
            ->limit(6)
            ->get()
            ->pluck('hash');

        foreach ($hashes as $hash) {
            $previews[$hash] = $this->getPreviewUrl($hash);
        }

        return $previews;
    }

    public function getPreviewDir(): string
    {
        return Storage::disk(self::DISK)->path(self::DIRECTORY . '/' . self::PREVIEW_DIRECTORY);
    }
}
