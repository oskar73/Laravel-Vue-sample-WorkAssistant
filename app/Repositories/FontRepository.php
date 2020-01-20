<?php

namespace App\Repositories;

use App\Models\Logo\Font;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FontRepository extends BaseRepository
{
    public $model = Font::class;

    const FOLDER = 'fonts';
    const DISK = 's3-pub-bizinabox';

    /**
     * @param array $attributes
     *
     * @return int
     * @throws \FontLib\Exception\FontNotFoundException
     */
    public function create(array $attributes): int
    {
        $font = $attributes['font'];
        $name = $this->getFullFontName($font->getRealPath());

        $countNewFonts = 0;

        // Check font existing
        $isExist = $this->first('name', $name);

        if (! $isExist) {
            $nameForFile = $this->nameForFile($name);
            $extension = $this->getExtension($font);

            // Save font to DB
            $this->model->create([
                'name' => $name,
                'public_path' => self::FOLDER . '/' . $nameForFile . '.' . $extension,
                'extension' => $extension,
            ]);

            // Save font file to disk
            $this->saveToDisk($name, $font);

            $countNewFonts++;
        }

        return $countNewFonts;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function prepareName(string $name): string
    {
        return trim(ucwords(strtolower(str_replace(['-'], ' ', $name))));
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function nameForFile(string $name): string
    {
        $words = array_map('lcfirst', explode(' ', $name));

        return implode('-', $words);
    }

    /**
     * @return FontRepository
     */
    public function clearFontCss(): self
    {
        if (! Storage::disk(self::DISK)->exists('fonts/css')) {
            Storage::disk(self::DISK)->makeDirectory('fonts/css');
        }

        $cssPath = $this->getCssPath();

        Storage::put('fonts/css/fonts.css', '');

        return $this;
    }

    /**
     * @param string $name
     * @param string $fileName
     * @param string $extension
     *
     * @return string
     */
    public function generateCss(string $name, string $fileName, string $extension = 'ttf')
    {
        return PHP_EOL.<<<EOD
            @font-face {
                font-family: '{$name}';
                font-style: normal;
                font-weight: 400;
                src: url("../{$fileName}.{$extension}") format('truetype');
            }
        EOD;
    }

    /**
     * @param string $name
     * @param string $extension
     *
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function addCss(string $fontName, string $fileName, string $extension = 'ttf')
    {
        Storage::disk(self::DISK)->append('fonts/css/fonts.css', $this->generateCss($fontName, $fileName, $extension));

        return $this;
    }

    public function removeCss($fontName, $extension)
    {
        // Font name to lower case
        $fileName = $this->nameForFile($fontName);

        $cssPath = $this->getCssPath();
        $fontCss = file_get_contents($cssPath);

        $cssToRemove = $this->generateCss($fontName, $fileName, $extension);

        $newCss = str_replace($cssToRemove, '', $fontCss);

        Storage::disk(self::DISK)->put('fonts/css/fonts.css', $newCss);
    }


    /**
     * @param string $fileName
     *
     * @return string
     */
    public function getCssPath($fileName = 'fonts.css'): string
    {
        return $this->getCssDir() . $fileName;
    }

    /**
     * @return string
     */
    public function getCssDir(): string
    {
        return Storage::disk(self::DISK)->url('fonts/css/');
    }

    /**
     * @param string $name
     * @param UploadedFile $font
     *
     * @return $this
     */
    public function saveToDisk(string $name, UploadedFile $font)
    {
        $extension = $this->getExtension($font);

        // Font name to lower case
        $fileName = $this->nameForFile($name);

        // Set font name with extension
        $fileFullName = "{$fileName}.{$extension}";

        // Move file to S3 storage
        $font->storeAs('fonts', $fileFullName, ['disk' => self::DISK,'visibility' => 'public']);
        // Move file to local directory
        // $font->move($this->getDirectory(), $name);

        // update fonts css
        if (! Storage::disk(self::DISK)->exists('fonts/css')) {
            Storage::disk(self::DISK)->makeDirectory('fonts/css');
            Storage::put('fonts/css/fonts.css', '');
        }

        $this->addCss($name, $fileName, $extension);

        return $this;
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return Storage::disk(self::DISK)->url(self::FOLDER).'/';
    }

    /**
     * @param UploadedFile $font
     *
     * @return string
     */
    public function getExtension(UploadedFile $font): string
    {
        return strtolower($font->getClientOriginalExtension());
    }

    /**
     * @param string $path
     *
     * @return null|string
     * @throws \FontLib\Exception\FontNotFoundException
     */
    public function getFontName(string $path): ?string
    {
        $fontParser = \FontLib\Font::load($path);

        if ($fontParser) {
            $fontParser->parse();

            return $this->prepareFontName($fontParser->getFontName());
        }

        return null;
    }

    /**
     * @param string $path
     *
     * @return string|null
     * @throws \FontLib\Exception\FontNotFoundException
     */
    public function getFullFontName(string $path): ?string
    {
        $fontParser = \FontLib\Font::load($path);

        if ($fontParser) {
            $fontParser->parse();

            $fontName = $this->prepareFontName($fontParser->getFontName());
            $fontFamily = $fontParser->getFontSubfamily();

            if (Str::contains($fontName, [$fontFamily])) {
                return $fontName;
            }

            return $fontName . ' ' . $fontFamily;
        }

        return null;
    }

    /**
     * @param string $fontName
     *
     * @return string
     */
    protected function prepareFontName(string $fontName): string
    {
        return Str::replaceArray('!', [''], $fontName);
    }
}
