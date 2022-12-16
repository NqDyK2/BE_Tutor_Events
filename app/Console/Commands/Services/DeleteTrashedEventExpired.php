<?php

namespace App\Console\Commands\Services;

use App\Models\Event;
use Illuminate\Console\Command;

class DeleteTrashedEventExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:deleteExpiredEventsInTrash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired events in trash';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $expiredDays = Event::TRASH_EXPIRED_DAYS;
        Event::where('trashed_at', '<', now()->subDays($expiredDays))->delete();

        return Command::SUCCESS;
    }
}
