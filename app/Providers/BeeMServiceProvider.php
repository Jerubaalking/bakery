<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BeeMService; // Path to your service class

class BeeMServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(BeeMService::class, function ($app) {
            return new BeeMService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
