<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\myCustome\myCustome;

class CustomeEmail extends Mailable
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
        $subject        = myCustome::emailTemplateMergeVariable($this->content,'subject');
        $body           = myCustome::emailTemplateMergeVariable($this->content,'body');
        $signature      = myCustome::emailTemplateMergeVariable($this->content,'signature');
        $body           = $body.$signature;
        if($body)
        {
            return $this->subject($subject)
                ->markdown('emails.custome-email')->with([
                'body'     =>  $body,
            ]);
        }
    }
}
