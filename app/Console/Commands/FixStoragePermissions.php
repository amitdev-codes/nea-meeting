<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-permissions';
    protected $description = 'Fix storage directory permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing storage permissions...');
        $storagePath = storage_path();
        // Set directory permissions to 2775
        shell_exec("find {$storagePath} -type d -exec chmod 2775 {} \;");
        
        // Set file permissions to 0664
        shell_exec("find {$storagePath} -type f -exec chmod 0664 {} \;");
        
        $this->info('Storage permissions fixed!');
        
        return Command::SUCCESS;
    }
}
