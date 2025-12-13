<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            \Illuminate\Support\Facades\URL::forceRootUrl('https://e-yarsi.id');
        }

        // Share badge counts with sidebar
        \Illuminate\Support\Facades\View::composer('partials.sidebar-menu', function ($view) {
            $user = auth()->user();
            $countValidasi = 0;
            $countProses = 0;

            if ($user) {
                if ($user->role === 'direktur') {
                    $countValidasi = \App\Models\Dokumen::where('status', 'pending')->count();
                }
                if ($user->role === 'staff') {
                    $countProses = \App\Models\Dokumen::where('status', 'disetujui')->count();
                }
            }

            $view->with('countValidasi', $countValidasi)
                 ->with('countProses', $countProses);
        });
    }
}
