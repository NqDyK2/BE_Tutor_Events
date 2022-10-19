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
    public function sendEmail($mailTo, $content, $subject, $view)
    {
        Mail::to($mailTo)->send(new SendMail($subject, $content, $view));
        // if (count(Mail::failures()) > 0) {
        //     foreach (Mail::failures() as $emailAddress) {
        //         Log::error(__METHOD__ . ' - ' . __LINE__ . ' - Mail error: ' . $emailAddress);
        //     }
        //     return _error(null, __('message.mailFail'), HTTP_BAD_REQUEST);
        // } else {
        //     return _success(null, __('message.mailSuccess'), HTTP_SUCCESS);
        // }
    }

}