<?php

namespace Wovosoft\SettingsManager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    const CONFIG_PATH = __DIR__.'/../config/settings-manager.php';

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_PATH => config_path('settings-manager.php'),
            ], 'config');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadFactoriesFrom(__DIR__.'/../database/factories');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'SettingsManager');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'SettingsManager');
        //$this->loadJsonTranslationsFrom(__DIR__."/../resources/lang");
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'settings-manager'
        );

//        $this->app->bind('Settings', function () {
//            return new SettingsManager();
//        });
        $this->app->bind('Settings', SettingsManager::class);

        $this->publishes([
            __DIR__.'/../resources' => resource_path('wovosoft/settings-manager'),
        ], 'resources');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/Seeds' => database_path('migrations/seeds'),
        ], 'seeds');
    }
}
