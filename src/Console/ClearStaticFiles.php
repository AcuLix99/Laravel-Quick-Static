<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic\Console;

use Aculix99\LaravelQuickStatic\Controller;

class ClearStaticFiles extends \Illuminate\Console\Command
{
    protected $signature = 'quick-static:clear';

    protected $description = 'Clears all cached files';

    public function handle(): void
    {
        $path = ($this->laravel->make(Controller::class))->getPath();
        shell_exec('rm -rf '.$path);
        mkdir($path);
    }
}
