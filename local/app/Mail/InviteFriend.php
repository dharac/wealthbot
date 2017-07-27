<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InviteFriend extends Mailable
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
        $this->content['subject'] = str_replace("[[friend_name]]",$this->content['name'],$this->content['subject']);
        $this->content['body']    = str_replace("[[friend_name]]",$this->content['name'],$this->content['body']);
        $this->content['body']    = str_replace("[[your_name]]",$this->content['yourName'],$this->content['body']);
   
        return $this->from($this->content['yourEmail'],$this->content['yourName'])
            ->to($this->content['EMAIL'],$this->content['name'])
            ->subject($this->content['subject'])
            ->markdown('emails.invite-friend')->with([
            'yourName'      => $this->content['yourName'],
            'name'          => $this->content['name'],
            'email'         => $this->content['EMAIL'],
            'subject'       => $this->content['subject'],
            'body'          => $this->content['body'],
        ]);
    }
}
