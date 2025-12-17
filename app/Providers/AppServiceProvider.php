<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use App\Models\Dokumen;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force URL for ALL environments to debug shared hosting issue
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            URL::forceRootUrl('https://e-yarsi.id');
        }

        // Share badge counts with sidebar
        View::composer('partials.sidebar-menu', function ($view) {
            $user = auth()->user();
            $countValidasi = 0;
            $countProses = 0;

            if ($user) {
                if ($user->role === 'direktur') {
                    $countValidasi = Dokumen::where('status', 'pending')->count();
                }
                if ($user->role === 'staff') {
                    $countProses = Dokumen::where('status', 'disetujui')->count();
                }
            }

            $view->with('countValidasi', $countValidasi)
                 ->with('countProses', $countProses);
        });
    }
}
