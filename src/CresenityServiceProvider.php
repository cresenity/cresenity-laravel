<?php

namespace Cresenity\Laravel;

use Cresenity\Laravel\CApp;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CresenityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        if (!\defined('DS')) {
            define('DS', \DIRECTORY_SEPARATOR);
        }
        require_once dirname(__FILE__).'/../helpers/c.php';
        require_once dirname(__FILE__).'/../helpers/carr.php';
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerRoutes();
        $this->registerResources();
        CApp::registerBlade();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
    }

    /**
     * Register the Telescope resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cresenity');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $publishesMigrationsMethod = method_exists($this, 'publishesMigrations')
                ? 'publishesMigrations'
                : 'publishes';

            $this->{$publishesMigrationsMethod}([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'cresenity-migrations');

            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/cresenity'),
            ], ['cresenity-assets', 'laravel-assets']);

            $this->publishes([
                __DIR__.'/../config/cresenity.php' => config_path('cresenity.php'),
            ], 'cresenity-config');

            // $this->publishes([
            //     __DIR__.'/../stubs/TelescopeServiceProvider.stub' => app_path('Providers/TelescopeServiceProvider.php'),
            // ], 'telescope-provider');
        }
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CConsole\Commands\VersionCommand::class,
            ]);
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cresenity.php',
            'cresenity'
        );
    }
}
