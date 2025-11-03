<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic\Console\Commands;

use Illuminate\Console\Command;

class CacheConfig extends Command
{
    protected $signature = 'quick-static:cache-config';

    protected $description = 'Compile quick-static config into a standalone PHP file';

    public function handle()
    {
        $config = config('quick-static');
        $export = var_export($config, true);

        $target = base_path('bootstrap/cache/quick-static.php');

        file_put_contents($target, "<?php\nreturn $export;\n");

        $this->info('quick-static config cached!');
    }
}
