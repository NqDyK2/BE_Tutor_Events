<?php

namespace App\Jobs\Mail;

use App\Services\MailServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMailChangeLessonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailTo;
    private $data;

    /**
     * Send mail to student when change lesson schedule or change class_location.
     *
     * @param String $mailTo
     *
     * @param Array $data [
     *      $lesson
     *      $subject
     * ]
     *
     * @return void
     */
    public function __construct($mailTo, $data)
    {
        $this->mailTo = $mailTo;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            MailServices::sendEmail(
                $this->mailTo,
                'Lịch học đã được thay đổi',
                $this->data,
                'mail.change_lesson'
            );
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
