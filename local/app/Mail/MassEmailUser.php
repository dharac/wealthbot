<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MassEmailUser extends Mailable
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
        $subject    = $this->content['SUBJECT'];
        $body       = $this->content['BODY'];

        $subject = str_replace("[USERNAME]",$this->content['USERNAME'],$subject);
        $subject = str_replace("[FIRSTNAME]",$this->content['FIRSTNAME'],$subject);
        $subject = str_replace("[LASTNAME]",$this->content['LASTNAME'],$subject);

        $body = str_replace("[USERNAME]",$this->content['USERNAME'],$body);
        $body = str_replace("[FIRSTNAME]",$this->content['FIRSTNAME'],$body);
        $body = str_replace("[LASTNAME]",$this->content['LASTNAME'],$body);

        if($body)
        {
            return $this->subject($subject)
                ->markdown('emails.custome-email')->with([
                'body'     =>  $body,
            ]);
        }
    }
}
