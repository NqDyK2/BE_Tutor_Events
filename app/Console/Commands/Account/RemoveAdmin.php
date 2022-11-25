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
        User::where('role_id', USER_ROLE_ADMIN)
        ->get()
        ->each(function (User $user) {
            $this->info($user->email);
        });

        $email = $this->ask('Enter email address:');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found');
            return;
        }

        $user->role_id = USER_ROLE_ADMIN;;
        $user->save();

        $this->info('Removed admin permissions of ' . $email);

        return Command::SUCCESS;
    }
}
