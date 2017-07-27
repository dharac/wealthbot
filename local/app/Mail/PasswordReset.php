<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $content;

    public function __construct()
    {
        //
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.passwordReset')->with([
                'first_name'    => $this->content['first_name'],
                'last_name'     => $this->content['last_name'],
                'email'         => $this->content['email'],
                'password'      => $this->content['password'],
            ]);
    }
}
