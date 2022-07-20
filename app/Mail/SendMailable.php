<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $count;
    
    public function __construct($count)
    {
        $this->count = $count;
    }
     
    public function build()
    {
        return $this->view('emails.registeredcount');
    }
}