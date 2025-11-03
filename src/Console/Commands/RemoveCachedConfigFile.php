<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic\Console\Commands;

use Illuminate\Console\Command;

class RemoveCachedConfigFile extends Command
{
    protected $signature = 'quick-static:clear-cached-config';

    protected $description = 'Remove compiled standalone PHP file';

    public function handle()
    {
        shell_exec('rm -rf '.base_path('bootstrap/cache/quick-static.php'));

        $this->info('quick-static config cleared!');
    }
}
