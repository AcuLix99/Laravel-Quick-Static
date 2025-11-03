<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic;

class PackageServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->commands([
            Console\Commands\ClearStaticFiles::class,
            Console\Commands\CacheConfig::class,
            Console\Commands\RemoveCachedConfigFile::class,
        ]);

        $this->app->singleton(Controller::class, fn () => (new Controller)->setContainer($this->app));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/quick-static.php' => config_path('quick-static.php'),
        ], 'quick-static');

        if ($this->app->runningInConsole()) {
            $this->optimizes(
                optimize: 'quick-static:cache-config',
                clear: 'quick-static:clear-cached-config',
                key: 'quick-static',
            );
        }
    }
}
