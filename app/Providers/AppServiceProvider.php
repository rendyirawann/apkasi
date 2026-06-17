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

        // Data footer (link kolom + logo) dibagikan hanya saat partial footer dirender.
        // Footer ini dipakai landing & halaman dalam, jadi satu sumber data -> konsisten.
        View::composer('frontend.partials.footer', function ($view) {
            $columns = collect();
            $brand   = collect();
            $side    = collect();
            try {
                if (Schema::hasTable('footer_columns')) {
                    $columns = \App\Models\FooterColumn::where('is_active', true)
                        ->with(['links' => fn ($q) => $q->where('is_active', true)->orderBy('urut')->orderBy('id')])
                        ->orderBy('urut')->orderBy('id')->get();
                }
                if (Schema::hasTable('site_logos')) {
                    $logos = \App\Models\SiteLogo::whereIn('grup', ['footer_brand', 'footer_side'])
                        ->where('is_active', true)->orderBy('urut')->get()->groupBy('grup');
                    $brand = $logos->get('footer_brand', collect());
                    $side  = $logos->get('footer_side', collect());
                }
            } catch (\Throwable $e) {
                // biarkan kosong -> partial pakai fallback bawaan
            }
            $view->with('footerColumns', $columns)
                 ->with('footerBrandLogos', $brand)
                 ->with('footerSideLogos', $side);
        });
    }
}
