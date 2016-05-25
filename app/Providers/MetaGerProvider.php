<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\MetaGer;

class MetaGerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MetaGer::class, function($app) {
            return new MetaGer();
        });
    }

}
