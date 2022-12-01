<?php

namespace App\Console\Commands\Account;

use App\Models\User;
use Illuminate\Console\Command;

class RemoveAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:removeAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remove admin permissions of user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::where('role_id', User::ROLE_ADMIN)
        ->get()
        ->each(function (User $user) {
            $this->info($user->email);
        });

        $email = $this->ask('Enter email address to REVOKE admin permission:');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found');
            return;
        }

        $user->role_id = User::ROLE_STUDENT;
        $user->save();

        $this->info('Revoked admin permissions of ' . $email);

        return Command::SUCCESS;
    }
}
