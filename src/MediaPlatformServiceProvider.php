<?php

namespace MediaPlatform;

use Illuminate\Support\ServiceProvider;
use MediaPlatform\Console\ReindexSearchCommand;
use MediaPlatform\Console\ProcessScheduledPublicationsCommand;

class MediaPlatformServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/media-platform.php',
            'media-platform'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/media-platform.php' => config_path('media-platform.php'),
        ], 'media-platform-config');

        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        $this->loadRoutesFrom(
            __DIR__ . '/../routes/api.php'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                ReindexSearchCommand::class,
                ProcessScheduledPublicationsCommand::class,
            ]);
        }
    }
}
