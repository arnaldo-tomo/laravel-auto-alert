<?php

namespace ArnaldoTomo\AutoAlert;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;

class AutoAlertServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/auto-alert.php', 'auto-alert');

        $this->app->singleton(AlertManager::class, function ($app) {
            return new AlertManager();
        });

        $this->app->singleton(LayoutManager::class, function ($app) {
            return new LayoutManager();
        });

        $this->app->singleton(ExceptionListener::class, function ($app) {
            return new ExceptionListener();
        });
    }

    public function boot(Kernel $kernel)
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/auto-alert.php' => config_path('auto-alert.php'),
            ], 'auto-alert-config');

            $this->commands([
                Console\InstallCommand::class,
            ]);
        }

        // Register Global Middleware
        $kernel->pushMiddleware(Middleware\AutoAlertMiddleware::class);
    }
}
