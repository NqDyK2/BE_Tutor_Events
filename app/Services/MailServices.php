<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\Log;

class MailServices
{
    /**
     * Send simple mail
     * @param $mailTo - Mail send to
     * @param $data - Data to send
     * @param $subject - Subject of mail
     * @param $view - Name view template of mail
     */
    public static function sendEmail($mailTo, $subject, $data, $view)
    {
        Mail::to($mailTo)->send(new SendMail($subject, $data, $view));
    }

}