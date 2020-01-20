<?php

namespace App\Providers;

use App\Models\Website\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use OwenIt\Auditing\Models\Audit;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Barryvdh\Elfinder\ElfinderController',
            \App\Integration\CustomElfinderProvider::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

        Paginator::useBootstrap();

        // audit creating -- free logo maker
        Audit::creating(function (Audit $model) {
            $oldData = count($model->old_values) > 0 ? $model->old_values : null;
            $newData = count($model->new_values) > 0 ? $model->new_values : null;

            if (! $oldData && ! $newData) {
                return false;
            }

            if ($oldData && $newData) {
                $oldLogo = $oldData['logo_content'] ?? '';
                $newLogo = $newData['logo_content'] ?? '';

                // Skip equals logos
                if ($oldLogo === $newLogo) {
                    return false;
                }

                // Save only difference logos
                if (isset($oldData['updated_at'])) {
                    if (Carbon::parse($oldData['updated_at'])->diffInMinutes($newData['updated_at']) < 20) {
                        return false;
                    }
                }
            }
        });
        //        \URL::forceScheme('https');

        view()->composer(
            'layouts.app',
            function ($view) {
                $seo = optional(option("seo", null));
                $view->with(['seo' => $seo]);
            }
        );

        view()->composer('admin.*', function ($view) {
            $view_name = $view->getName();
            $tooltip = optional(option($view_name));

            $view->with(['tooltip' => $tooltip, 'view_name' => $view_name]);
        });

        view()->composer('user.*', function ($view) {
            $view_name = $view->getName();
            $tooltip = optional(option($view_name));

            $view->with(['tooltip' => $tooltip, 'view_name' => $view_name]);
        });

        // 20190706-1
        view()->composer(
            'layouts.adminApp',
            function ($view) {
                $basic = optional(option("basic", null));
                $view->with(['basic' => $basic]);
            }
        );
        // end
    }
}
