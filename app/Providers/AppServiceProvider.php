<?php

namespace App\Providers;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('App\Repositories\ProductRepository', function ($app) {
            return new \App\Repositories\ProductRepository();
        });
    
        $this->app->bind('App\Repositories\IngredientRepository', function ($app) {
            return new \App\Repositories\IngredientRepository();
        });
    
        $this->app->bind('App\Repositories\OrderRepository', function ($app) {
            return new \App\Repositories\OrderRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
