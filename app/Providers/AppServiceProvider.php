<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Importação essencial para manipular URLs

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
        /**
         * Força o esquema HTTPS em todas as URLs geradas pelo Laravel (CSS, JS, Links)
         * quando a aplicação estiver rodando em ambiente de produção.
         * Isso resolve o erro de "Mixed Content" no EasyPanel/Docker.
         */
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}