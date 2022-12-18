<?php

namespace App\Jobs\Mail;

use App\Http\Services\MailServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMailInviteLesson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mailTo;
    private $data;

    /**
     *  Send mail invite when lesson starting.
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
                'Buổi học lớp ' . $this->data['subject']['name'] . ' đang diễn ra',
                $this->data,
                'mail.invite'
            );
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
