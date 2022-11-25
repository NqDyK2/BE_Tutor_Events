<?php

namespace App\Console\Commands\Account;

use App\Models\User;
use Illuminate\Console\Command;

class ListAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:listAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list admin email';

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

        return Command::SUCCESS;
    }
}
