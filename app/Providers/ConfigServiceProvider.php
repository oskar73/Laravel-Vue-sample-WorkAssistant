<?php

namespace App\Providers;

use App\Models\Module\ModuleMedia;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)

    {
        $this->app['router']->bind('website', function ($website) use ($request) {
            if (($request->is('account/website*') || $request->is('admin/website*') || $request->is('api/v1/website*')) && $website) {
                config([
                    'media-library.media_model' => ModuleMedia::class,
                    'media-library.disk_name' => 's3-pub-bizinasite',
                    "filesystems.disks.storage.root" => config("filesystems.disks.storage.root") . "/" . $website,
                    "filesystems.disks.s3-pub-bizinasite.root" => $website,
                ]);
            }

            return $website;
        });
    }
}
