<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Samakan root URL & scheme dengan APP_URL agar aset tidak salah skema.
        // Hanya paksa HTTPS bila APP_URL memang https — supaya deploy via HTTP
        // (mis. http://10.0.22.22:8279) tidak men-generate aset https yang gagal (Mixed Content).
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceRootUrl(config('app.url'));
            URL::forceScheme('https');
        }

        // Implicitly grant "Superadmin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole(['Superadmin', 'superadmin']) ? true : null;
        });

        // Share settings globally to all views
        View::composer('*', function ($view) {
            try {
                if (Schema::hasTable('settings')) {
                    $appSettings = \App\Models\Setting::allCached();
                    $view->with('appSettings', $appSettings);
                }
            } catch (\Exception $e) {
                $view->with('appSettings', []);
            }
        });
    }
}
