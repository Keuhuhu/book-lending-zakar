<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Memaksa penggunaan HTTPS saat aplikasi di-deploy (bukan local)
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }
        
        // Atau jika ingin lebih simpel, langsung saja:
        // URL::forceScheme('https');
    }
}
