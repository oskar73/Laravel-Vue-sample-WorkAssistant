<?php

namespace App\Console\Commands;

use App\Repositories\FontRepository;
use App\Traits\ConsoleLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RefreshFonts extends Command
{
    use ConsoleLogger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:fonts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import font names to database';

    /**
     * @var FontRepository
     */
    protected $font;

    /**
     * FontsToDb constructor.
     *
     * @param FontRepository $font
     */
    public function __construct(FontRepository $font)
    {
        parent::__construct();

        $this->font = $font;
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        \DB::transaction(function () {
            // Truncate fonts table
            $this->font->model->truncate();

            // Clear font css file
            $this->font->clearFontCss();

            // Get list fonts
            $directory = $this->font->getDirectory();
            $fonts = \File::files($directory);
            $count = count($fonts);

            $now = now();
            $this->info("Start: [{$now}]");

            foreach ($fonts as $key => $font) {
                // Set font name
                $name = explode('.'.$font->getExtension(), $font->getRelativePathname())[0];

                // Prepare font name
                $cleanName = $this->font->prepareName($name);
                $fileName = $this->font->nameForFile($cleanName);

                // Set extension
                $extension = $font->getExtension();

                // From and to paths
                $from = $directory.$font->getRelativePathname();
                $to = $directory.$fileName.'.'.$extension;

                // Move or rename file fonts
                \File::move($from, $to);

                $publicPath = $this->font::FOLDER.'/'.$fileName.'.'.$extension;

                // Get font name
                $fontName = $this->font->getFullFontName(Storage::disk('public')->path($publicPath));

                if (! $fontName) {
                    $this->error('Error font parsing: '.$fileName);

                    continue;
                }

                // Create font record to DB
                $this->font->model->updateOrCreate([
                    'name' => $fontName,
                    'public_path' => $this->font::FOLDER.'/'.$fileName.'.'.$extension,
                    'extension' => $extension,
                ]);

                // Add css to font file
                $this->font->addCss($fontName, $fileName, $extension);

                $this->info("({$key} of {$count}). $fontName imported");
            }
        });
    }
}
