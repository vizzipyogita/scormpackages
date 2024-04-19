<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordEmail extends Mailable
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
        $Subject = 'Reset Password';
        $ToUserName = $this->data['ToUserName'];
		
        $response =  $this->view('emails.forgotpassword')
                    ->from(env('mail_from_address'), env('MAIL_TO_SUPPORT_NAME'))
                    ->subject($Subject)
                    ->with(['ToUserName' => $ToUserName, 'email'=>$ToEmailAddr, 'password'=>$this->data['password']]);		
    }
}
