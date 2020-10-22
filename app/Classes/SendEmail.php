<?php

namespace App\Classes;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $linkEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    
    public function set_email($linkEmail)
    {
        $this->linkemail = $linkEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       return $this->from('it@pttrimitra.com')
                   ->view('auth.passwords.emailku')
                   ->subject('TCH Application Reset Notification')                   
                   ->with(
                    [
                        'elink' => $this->linkemail,                       
                    ]);
    }
}