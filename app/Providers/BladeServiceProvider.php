<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('getJs', function ($file) {
            if (File::exists(base_path("resources/js/minified/".$file))) {
                $content = file_get_contents(base_path("resources/js/minified/".$file));

                return '<script>' .  $content . '</script>';
            } else {
                return '';
            }
        });

        Blade::if('isWhitelisted', function () {
            return auth()->check() && auth()->user()->hasRole(['admin']);
        });
    }
}
