<?php

namespace harmonic\Ezypay;

use Illuminate\Support\ServiceProvider;

class EzypayServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'harmonic');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'harmonic');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/../config/ezypay.php' => config_path('ezypay.php'),
        ]);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ezypay.php', 'ezypay');

        // Register the service the package provides.
        $this->app->singleton('ezypay', function ($app) {
            return new Ezypay;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['ezypay'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/ezypay.php' => config_path('ezypay.php'),
        ], 'ezypay.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/harmonic'),
        ], 'ezypay.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/harmonic'),
        ], 'ezypay.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/harmonic'),
        ], 'ezypay.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
