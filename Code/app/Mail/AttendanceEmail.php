<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AttendanceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $ToEmailAddr = $this->data['ToEmailAddr'];
        $Subject = 'MISSED CLASS';
        $ToUserName = $this->data['ToUserName'];
		
        $response =  $this->view('emails.attendace')
                    ->from(env('mail_from_address'), env('MAIL_TO_SUPPORT_NAME'))
                    ->subject($Subject)
                    ->with(['ToUserName' => $ToUserName, 'StudentName' => $this->data['StudentName']]);		
    }
}
