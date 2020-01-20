<?php

namespace App\Services;

use App\Models\GraphicDesign\UserDesign;
use ColorThief\ColorThief;
use Illuminate\Support\Facades\Storage;
use ourcodeworld\NameThatColor\ColorInterpreter as NameThatColor;
use Spatie\Image\Manipulations;
use SVG\Nodes\Structures\SVGStyle;
use SVG\SVG;
use ZipArchive;

class GenerateDesignPackageService extends DesignService
{
    protected const GUIDELINE_NAME = 'guideline.pdf';
    public const STORAGE_DISK = 's3-pub-bizinabox';
    protected UserDesign $userDesign;
    private int $allSteps = 0;
    private int $currentStep = 0;

    public function setUserDesign(UserDesign $userDesign): self
    {
        $this->userDesign = $userDesign;

        return $this;
    }

    private function createDirectory($dir): void
    {
        if (! Storage::disk(self::STORAGE_DISK)->exists($dir)) {
            Storage::disk(self::STORAGE_DISK)->makeDirectory($dir);
        }
    }

    public function getUserDir(): string
    {
        $dir = 'users' . '/' . $this->userDesign->user_id . '/designs/' . $this->userDesign->hash . '/';
        $this->createDirectory($dir);

        return $dir;
    }

    protected function getPrintQualityPath(string $graphicName, string $name): string
    {
        $dir = $this->getUserDir() . $graphicName . '/print/';
        $this->createDirectory($dir);

        return $dir . $name;
    }

    protected function getSocialMediaPath(string $graphicName, string $name): string
    {
        $dir = $this->getUserDir() . $graphicName . '/social-media/';
        $this->createDirectory($dir);

        return $dir . $name;
    }

    public function getWebImagePath(string $graphicName, string $name): string
    {
        $dir = $this->getUserDir() . $graphicName . '/web/';
        $this->createDirectory($dir);

        return $dir . $name;
    }

    protected function getVectorPath(string $graphicName, string $name): string
    {
        $dir = $this->getUserDir() . $graphicName . '/vectors/';
        $this->createDirectory($dir);

        return $dir . $name;
    }


    protected function getGuidelinePath(string $graphicName, string $name): string
    {
        $dir = $this->getUserDir() . $graphicName . '/';
        $this->createDirectory($dir);

        return $dir . $name;
    }

    public function getArchivePath(): string
    {
        return $this->getUserDir() . 'designs.zip';
    }

    protected function getDesignWidth(SVG $design): int
    {
        return (int)$design->getDocument()->getAttribute('width');
    }

    protected function getDesignHeight(SVG $design): int
    {
        return (int)$design->getDocument()->getAttribute('height');
    }

    public function createPreview(UserDesign $userDesign, array $options = []): self
    {
        try {
            // Set images path
            $name = urlencode($options['name']) . '.' . $options['extension'];
            $imagePath = $this->getWebImagePath($userDesign->graphic->slug, $name);
            $imagePathHighQuality = $this->getPrintQualityPath($userDesign->graphic->slug, $name);

            // Get prepared svg
            $svg = $this->getSvgForPreview($userDesign);

            // Set svg width/height
            $svg->getDocument()->setStyle('width', '100%')->setStyle('height', '100%');

            // Set filter
            if (data_get($options, 'filter')) {
                $svg->getDocument()->setStyle('filter', $options['filter'])
                    ->setStyle('-webkit-filter', $options['filter'])
                    ->setStyle('-moz-filter', $options['filter'])
                    ->setStyle('-ms-filter', $options['filter'])
                    ->setStyle('-o-filter', $options['filter']);
            }

            $width = $this->getDesignWidth($svg);
            if (data_get($options, 'fit')) {
                // Create png preview
                $base64Data = BrowserShot::html($svg->toXMLString())
                    ->setNodeModulePath(config('browser_shot.node_module_path'))
                    ->setChromePath(config('browser_shot.chromium_path'))
                    ->addChromiumArguments(config('browser_shot.chrome_arguments'))
                    ->{($options['background'] ?? 'hideBackground')}()
                    ->deviceScaleFactor(3);

                if (! empty($options['selector'])) {
                    $base64Data = $base64Data->select($options['selector']);
                }

                $base64Data = $base64Data->noSandbox()
                    ->fit(Manipulations::FIT_FILL, $options['width'], $options['height'])
                    ->base64Screenshot();

                $file = base64_decode($base64Data);

                Storage::disk(self::STORAGE_DISK)->put($imagePath, $file);
            } else {
                // Create png preview
                $base64Data = BrowserShot::html($svg->toXMLString())
                    ->setNodeModulePath(config('browser_shot.node_module_path'))
                    ->setChromePath(config('browser_shot.chromium_path'))
                    ->addChromiumArguments(config('browser_shot.chrome_arguments'))
                    ->noSandbox()
                    ->{($options['background'] ?? 'hideBackground')}()
                    ->windowSize($width, 0)
                    ->base64Screenshot();

                $file = base64_decode($base64Data);

                Storage::disk(self::STORAGE_DISK)->put($imagePath, $file);
            }

            // Create height quality preview
            $base64Data = BrowserShot::html($svg->toXMLString())
                ->setNodeModulePath(config('browser_shot.node_module_path'))
                ->setChromePath(config('browser_shot.chromium_path'))
                ->addChromiumArguments(config('browser_shot.chrome_arguments'))
                ->noSandbox()
                ->{($options['background'] ?? 'hideBackground')}()
                ->deviceScaleFactor(3)
                ->windowSize($width, 0)
                ->base64Screenshot();

            $file = base64_decode($base64Data);

            Storage::disk(self::STORAGE_DISK)->put($imagePathHighQuality, $file);
        } catch (\Exception $e) {
            logger($e);
            info("Error in createPreview for " . $options['name'] . ': ' . $e->getMessage());
        }

        return $this;
    }

    public function createSocialMediaPreview(UserDesign $userDesign, array $options): self
    {

        // Get prepared svg
        $path = $this->getSocialMediaPath($userDesign->graphic->slug, $options['name'] . '.' . $options['extension']);

        $svg = $this->getSvgForPreview($this->userDesign);

        $svg->getDocument()
            ->setStyle('width', $options['width'])
            ->setStyle('height', $options['height']);

        $html = view('etc.svg-demonstration')->with(compact('svg'))->render();

        $base64Data = BrowserShot::html($html)
            ->setNodeModulePath(config('browser_shot.node_module_path'))
            ->setChromePath(config('browser_shot.chromium_path'))
            ->addChromiumArguments(config('browser_shot.chrome_arguments'))
            ->select('svg')
            ->noSandbox()
            ->showBackground()
            ->base64Screenshot();

        $image = base64_decode($base64Data);

        Storage::disk(self::STORAGE_DISK)->put($path, $image);

        return $this;
    }

    public function createGuidelineByImage(UserDesign $userDesign, string $fromImagePath): self
    {
        try {

            $colorNameDetector = new NameThatColor();
            $sourceImage = Storage::disk(self::STORAGE_DISK)->url($fromImagePath);
            $palette = ColorThief::getPalette($sourceImage);

            $path = $this->getGuidelinePath($userDesign->graphic->slug, self::GUIDELINE_NAME);

            $colors = [];
            $colorNames = [];

            // Convert rgb to hex
            foreach ($palette as $rgb) {
                $colors[] = sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
            }

            // Colors to uppercase
            $colors = array_unique(array_map('strtoupper', $colors));

            // Get color names
            foreach ($colors as $color) {
                $colorNames[$color] = $colorNameDetector->name($color)['name'];
            }

            $colorNames = array_unique($colorNames);

            $svg = $this->getSvgForPreview($this->userDesign);

            $fonts = $this->getSvgFonts($svg);
            $fontNames = $fonts->pluck(['name'])->unique()->toArray();

            $template = view('etc.guideline')->with(compact('svg', 'fontNames', 'colorNames'))->render();

            $guidelineSvg = SVG::fromString($template);

            // Include fonts to svg as css style
            $defaultFonts = $this->fonts->findWhereIn('name', ['Montserrat Regular']);
            $fonts = $fonts->merge($defaultFonts);

            foreach ($fonts as $font) {
                // Encode font to base64
                $encodedFont = base64_encode(file_get_contents(Storage::disk(self::STORAGE_DISK)->url($font->public_path)));

                // Add dynamic font style
                $guidelineSvg->getDocument()->addChild((new SVGStyle(
                    "@font-face {font-family: {$font->name}; src:url('data:font/{$font->extension};base64,{$encodedFont}');"
                )));
            }

            // Create pdf file
            $base64Data = BrowserShot::html($guidelineSvg->toXMLString())
                ->select('svg')
                ->setNodeModulePath(config('browser_shot.node_module_path'))
                ->setChromePath(config('browser_shot.chromium_path'))
                ->addChromiumArguments(config('browser_shot.chrome_arguments'))
                ->noSandbox()
                ->showBackground()
                ->paperSize($this->getDesignWidth($svg), $this->getDesignHeight($guidelineSvg), 'px')
                ->waitUntilNetworkIdle()
                ->base64pdf();

            $file = base64_decode($base64Data);

            Storage::disk(self::STORAGE_DISK)->put($path, $file);
        } catch (\Exception $exception) {
            info("Error in createGuidelineByImage for $fromImagePath: " . $exception->getMessage());
        }

        return $this;
    }

    public function createSvgVector(UserDesign $userDesign, string $fileName): self
    {
        try {
            // Get image path
            $vectorPath = $this->getVectorPath($userDesign->graphic->slug, $fileName);

            // Get prepared svg
            $svg = $this->getSvgForPreview($userDesign);

            // Set svg width/height and filter
            $svg->getDocument()
                ->setStyle('width', '100%')
                ->setStyle('height', '100%');

            Storage::disk(self::STORAGE_DISK)->put($vectorPath, $svg);

        } catch (\Exception $exception) {
            info("Error in createSvgVector for " . $fileName . ": " . $exception->getMessage());
        }

        return $this;
    }

    public function createPdfVector(UserDesign $userDesign, string $fileName, string $filter = null): self
    {
        // Get image path
        $vectorPath = $this->getVectorPath($userDesign->graphic->slug, $fileName);

        // Get prepared svg
        $svg = $this->getSvgForPreview($userDesign);
        $width = $this->getDesignWidth($svg);
        $height = $this->getDesignHeight($svg);

        // Create black-white pdf version
        if ($filter) {
            $svg->getDocument()->setStyle('filter', $filter);
        }

        // Create pdf file
        $base64Data = BrowserShot::html($svg->toXMLString())
            ->setNodeModulePath(config('browser_shot.node_module_path'))
            ->setChromePath(config('browser_shot.chromium_path'))
            ->addChromiumArguments(config('browser_shot.chrome_arguments'))
            ->noSandbox()
            ->margins(5, 10, 5, 5, 'px')
            ->paperSize($width, $height + 12, 'px')
            ->base64Screenshot();

        $file = base64_decode($base64Data);

        Storage::disk(self::STORAGE_DISK)->put($vectorPath, $file);

        return $this;
    }

    private function setAllStep($designs): void
    {
        foreach ($designs as $design) {
            if ($design->graphic->slug == 'logo') {
                $this->allSteps += 22;
            } elseif ($design->graphic->slug == 'favicon') {
                $this->allSteps += 5;
            } else {
                $this->allSteps += 1;
            }
        }

        $this->allSteps++;
    }

    private function updateProgress(): void
    {
        $this->currentStep++;
        $progress = $this->currentStep / $this->allSteps * 100;
        $this->userDesign->progress = $progress;
        $this->userDesign->save();
    }

    public function setAsCompleted(): self
    {
        $this->userDesign->progress = 100;
        $this->userDesign->downloadable = 1;
        $this->userDesign->save();

        return $this;
    }

    private function buildLogoPackage(UserDesign $userDesign): void
    {
        /**
         * Create social media previews
         */
        // Facebook
        $this->createSocialMediaPreview($userDesign, [
            'name' => 'facebook-profile',
            'extension' => 'png',
            'width' => '800px',
            'height' => '800px',
        ]);
        $this->updateProgress();

        $this->createSocialMediaPreview($userDesign, [
            'name' => 'facebook-cover',
            'extension' => 'png',
            'width' => '820px',
            'height' => '360px',
        ]);
        $this->updateProgress();

        // Instagram
        $this->createSocialMediaPreview($userDesign, [
            'name' => 'instagram-profile',
            'extension' => 'png',
            'width' => '800px',
            'height' => '800px',
        ]);
        $this->updateProgress();

        // Linkedin
        $this->createSocialMediaPreview($userDesign, [
            'name' => 'linkedin-cover',
            'extension' => 'png',
            'width' => '1584px',
            'height' => '396px',
        ]);
        $this->updateProgress();

        // Twitter
        $this->createSocialMediaPreview($userDesign, [
            'name' => 'twitter-profile',
            'extension' => 'png',
            'width' => '800px',
            'height' => '800px',
        ]);
        $this->updateProgress();

        $this->createSocialMediaPreview($userDesign, [
            'name' => 'twitter-cover',
            'extension' => 'png',
            'width' => '1500px',
            'height' => '500px',
        ]);
        $this->updateProgress();

        //Pinterest
        $this->createSocialMediaPreview($userDesign, [
            'name' => 'pinterest-profile',
            'extension' => 'png',
            'width' => '800px',
            'height' => '800px',
        ]);
        $this->updateProgress();

        $this->createSocialMediaPreview($userDesign, [
            'name' => 'pinterest-cover',
            'extension' => 'png',
            'width' => '222px',
            'height' => '150px',
        ]);
        $this->updateProgress();

        // Youtube
        $this->createSocialMediaPreview($userDesign, [
            'name' => 'youtube-cover',
            'extension' => 'png',
            'width' => '2560px',
            'height' => '1440px',
        ]);
        $this->updateProgress();

        $this->createSocialMediaPreview($userDesign, [
            'name' => 'youtube-profile',
            'extension' => 'png',
            'width' => '800px',
            'height' => '800px',
        ]);
        $this->updateProgress();

        /**
         * Create logo previews
         */
        // Create first preview
        $firstPreviewConfig = [
            'name' => 'colored (transparent bg)',
            'extension' => 'png',
            'background' => 'hideBackground',
        ];
        $this->createPreview($userDesign, $firstPreviewConfig);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'colored (transparent bg)',
            'extension' => 'png',
            'background' => 'hideBackground',
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'colored (white bg)',
            'extension' => 'png',
            'background' => 'showBackground',
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'monochrome (transparent bg)',
            'extension' => 'png',
            'background' => 'hideBackground',
            'filter' => 'grayscale(100%)',
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'monochrome (white bg)',
            'extension' => 'jpg',
            'background' => 'showBackground',
            'filter' => 'grayscale(100%)',
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'monochrome-invert (transparent bg)',
            'extension' => 'png',
            'background' => 'hideBackground',
            'filter' => 'grayscale(100%) invert(100%)',
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'monochrome-invert (white bg)',
            'extension' => 'png',
            'background' => 'showBackground',
            'filter' => 'grayscale(100%) invert(100%)',
        ]);
        $this->updateProgress();

        // Create first preview
        $this->createPreview($userDesign, [
            'name' => 'colored small (transparent bg)',
            'extension' => 'png',
            'background' => 'hideBackground',
            'fit' => true,
            'selector' => '.only_svg_content',
            'width' => 400,
            'height' => 225,
        ]);
        $this->updateProgress();

        // Create guideline
        $previewName = urlencode($firstPreviewConfig['name']) . '.' . $firstPreviewConfig['extension'];

        $this->createGuidelineByImage($userDesign, $this->getWebImagePath($userDesign->graphic->slug, $previewName));
        $this->updateProgress();

        /**
         * Create logo vectors
         */
        $this->createSvgVector($userDesign, 'logo-colored.svg');
        $this->updateProgress();

        $this->createPdfVector($userDesign, 'logo-colored.png');
        $this->updateProgress();

        $this->createPdfVector($userDesign, 'logo-monochrome.png', 'grayscale(100%)');
        $this->updateProgress();

        $this->createPdfVector($userDesign, 'logo-monochrome-invert.png', 'grayscale(100%) invert(100%)');
        $this->updateProgress();
    }

    private function buildFaviconPackage(UserDesign $userDesign): void
    {
        $this->createPreview($userDesign, [
            'name' => 'favicon-16X16',
            'extension' => 'png',
            'fit' => true,
            'width' => 16,
            'height' => 16,
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'favicon-32X32',
            'extension' => 'png',
            'fit' => true,
            'width' => 32,
            'height' => 32,
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'favicon-64X64',
            'extension' => 'png',
            'fit' => true,
            'width' => 64,
            'height' => 64,
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'favicon-128X128',
            'extension' => 'png',
            'fit' => true,
            'width' => 128,
            'height' => 128,
        ]);
        $this->updateProgress();

        $this->createPreview($userDesign, [
            'name' => 'favicon-512X512',
            'extension' => 'png',
            'fit' => true,
            'width' => 512,
            'height' => 512,
        ]);
        $this->updateProgress();
    }

    private function buildDesignPackage($userDesign): void
    {
        $this->createPreview($userDesign, [
            'name' => $userDesign->graphic->slug,
            'extension' => 'png',
            'fit' => true,
            'width' => (int)$userDesign->graphic->width,
            'height' => (int)$userDesign->graphic->height,
        ]);
        $this->updateProgress();
    }

    public function createArchive(): self
    {
        $localDirectory = "temp/{$this->userDesign->hash}";
        if (! Storage::disk('local')->exists($localDirectory)) {
            Storage::disk('local')->makeDirectory($localDirectory);
        }

        try {
            // New code for php 8.6
            $zip = new ZipArchive();

            $userDir = $this->getUserDir();
            $files = Storage::disk(self::STORAGE_DISK)->allFiles($userDir);
            $zipFilePath = Storage::disk('local')->path("temp/{$this->userDesign->hash}/design.zip");

            // Create a new ZIP archive
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
                // Add a directory and its contents to the archive
                foreach ($files as $filePath) {
                    // Add current file to archive
                    $relativePath = str_replace($userDir, '', $filePath);
                    if (strpos($relativePath, '.zip') > 0) {
                        continue;
                    }
                    $file = Storage::disk(GenerateDesignPackageService::STORAGE_DISK)->get($filePath);
                    $filePath = "temp/{$this->userDesign->hash}/$relativePath";
                    Storage::disk('local')->put($filePath, $file);

                    $zip->addFile(Storage::disk('local')->path($filePath), $relativePath);
                }

                // Zip archive will be created only after closing object
                $zip->close();
                Storage::disk(self::STORAGE_DISK)->put($this->getArchivePath(), file_get_contents($zipFilePath));
            }
        } catch (\Exception $exception) {
            info("Error in createArchive: " . $exception->getMessage());
        }

        Storage::disk('local')->deleteDirectory($localDirectory);

        return $this;
    }

    public function generateDesignPackage(): void
    {
        try {
            $userDesigns = [$this->userDesign];
            foreach ($this->userDesign->pairs as $pairDesign) {
                $userDesigns[] = $pairDesign;
            }
            $this->setAllStep($userDesigns);
            $this->updateProgress();

            foreach ($userDesigns as $userDesign) {
                try {
                    if ($userDesign->graphic->slug == 'logo') {
                        $this->buildLogoPackage($userDesign);
                    } elseif ($userDesign->graphic->slug == 'favicon') {
                        $this->buildFaviconPackage($userDesign);
                    } else {
                        $this->buildDesignPackage($userDesign);
                    }
                } catch (\Exception $exception) {
                    logger($exception);

                    continue;
                }
            }

            $this->setAsCompleted();
            $this->createArchive();
        } catch (\Exception $exception) {
            logger($exception);
            $this->setAsCompleted();
            $this->createArchive();
        }
    }
}
