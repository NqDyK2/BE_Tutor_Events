<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $mailData;
    public $view;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $mailData, $view)
    {
        $this->subject = $subject;
        $this->mailData = $mailData;
        $this->view = $view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->view($this->view);
    }
}
