<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic;

class PackageServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->commands(Console\ClearStaticFiles::class);

        $this->app->singleton(Controller::class, fn () => (new Controller)->setContainer($this->app));
    }
}
