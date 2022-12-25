<?php

namespace App\Console\Commands\Account;

use App\Models\User;
use Illuminate\Console\Command;

class SetAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:setAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'grand admin permissions for user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask('Enter email address to GRANT admin permission');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->error('User not found');
        }

        $user->role_id = User::ROLE_ADMIN;
        $user->save();

        $this->info('Granted admin permissions for ' . $email);

        return Command::SUCCESS;
    }
}
