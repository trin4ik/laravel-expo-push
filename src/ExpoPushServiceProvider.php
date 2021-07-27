<?php

namespace Trin4ik\LaravelExpoPush;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Notification;
use Trin4ik\LaravelExpoPush\Channels\ExpoPushChannel;

class ExpoPushServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-expo-push');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-expo-push');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/../database/migrations/create_expo_push_notifications_table.php' => database_path("/migrations/" . date('Y_m_d_His') . "_create_expo_push_notifications_table.php"),
        ], 'migrations');

        Notification::extend('expo-push', function ($app) {
            return new ExpoPushChannel();
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/expo-push.php' => config_path('expo-push.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-expo-push'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-expo-push'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-expo-push'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/expo-push.php', 'expo-push');
    }
}
