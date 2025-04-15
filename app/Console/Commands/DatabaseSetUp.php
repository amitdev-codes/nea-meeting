<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DatabaseSetUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Step 1: Fresh Migration with Seeding
        echo "Migration process started\n";
        Artisan::call('migrate:fresh --seed');
        echo "Migration process completed\n";

        // Step 2: Composer Dump Autoload
        echo "composer  process started\n";
        shell_exec('composer dump-autoload');
        echo "Composer dump-autoload process completed\n";

        // Step 3: Clear Cache
        echo "optimize  process started\n";
        Artisan::call('optimize:clear');
        echo "Optimize clear process completed\n";

        // Step 4: Optimize Cache
        Artisan::call('optimize');
        echo "Optimize process completed\n";
    }
}
