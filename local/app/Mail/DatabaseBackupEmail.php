<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DatabaseBackupEmail extends Mailable
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
        $body       = $this->content['MESSAGE'];
        $SITENAME   = $this->content['SITENAME'];

        if (array_key_exists("FILEPATH",$this->content))
        {
            $filepath   = $this->content['FILEPATH'];
            $filename   = config('services.SITE_DETAILS.SITE_NAME').' Database Backup '.dispayTimeStamp(\Carbon\Carbon::now())->toDayDateTimeString().'.zip';
            $backup_name = "";

            if($subject)
            {
                return $this->subject($subject)
                    ->attach($filepath, array( 'as' => $filename,  'mime' => 'application/zip'))
                    ->markdown('emails.custome-email')->with([
                    'body'     =>  $body,
                ]);
            }
        }
        else
        {
            return $this->subject($subject)
                    ->markdown('emails.custome-email')->with([
                    'body'     =>  $body,
                ]);
        }
    }
}
