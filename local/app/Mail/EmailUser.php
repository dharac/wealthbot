<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
            $subject = $this->content['SUBJECT'].' | '.config('services.SITE_DETAILS.SITE_NAME');

            return $this->subject($subject)
            ->markdown('emails.email-user')->with([
                    'FIRSTNAME'     =>  $this->content['FIRSTNAME'],
                    'LASTNAME'      =>  $this->content['LASTNAME'],
                    'BODY'          =>  $this->content['BODY'],
                ]);
    }
}
