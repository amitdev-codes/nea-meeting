<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UnlockUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:unlock {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock a user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', $this->argument('email'))->first();

        if ($user) {
            $user->update([
                'is_locked' => false,
                'wrong_password_attempts' => 0,
                'locked_at' => null,
            ]);

            $this->info("User {$user->email} has been unlocked.");
        } else {
            $this->error('User not found.');
        }
    }
}
