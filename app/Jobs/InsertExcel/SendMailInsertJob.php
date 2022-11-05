<?php

namespace App\Jobs\InsertExcel;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMailInsertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $x;
    private $mailService;
    public function __construct($x, $mailService)
    {
        $this->x = $x;
        $this->mailService = $mailService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = 1;
        $this->mailService->sendEmail(
            $this->x['student_email'],
            $content,
            'Bạn đã đã được thêm vào lớp Tutor',
            'mail.import_excel'
        );
    }
}
