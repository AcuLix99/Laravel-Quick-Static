<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic\Console\Commands;

use Illuminate\Console\Command;

class ClearStaticFiles extends Command
{
    protected $signature = 'quick-static:clear';

    protected $description = 'Clears all cached files';

    public function handle(): void
    {
        $path = config('quick-static.cache_folder');
        if ($path === null) {
            throw new \InvalidArgumentException('Please define the "cache_folder" in config/quick-static.php');
        }
        shell_exec('rm -rf '.$path);
        mkdir($path);
    }
}
