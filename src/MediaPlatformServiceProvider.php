<?php

namespace MediaPlatform;

use Illuminate\Support\ServiceProvider;

class MediaPlatformServiceProvider extends ServiceProvider
{
    /**
     * Register bindings and config.
     */
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/media-platform.php',
            'media-platform'
        );
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/media-platform.php' => config_path('media-platform.php'),
        ], 'media-platform-config');

        // Load migrations
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        // Load API routes
        $this->loadRoutesFrom(
            __DIR__ . '/../routes/api.php'
        );

        // Register commands (only in console)
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\ReindexSearchCommand::class,
                Console\ProcessScheduledPublicationsCommand::class,
            ]);
        }
    }
}
