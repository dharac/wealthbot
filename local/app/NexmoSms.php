<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use Nexmo\Laravel\Facade\Nexmo;

class NexmoSms extends Model
{
    protected $table = 'nexmo_sms';
    protected $primaryKey = 'smsid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sms','message_id','created_by', 'modified_by', 'created_at', 'updated_at'];
    
    public static function sendSms($data = null)
    {
        if (!\App::environment('local'))
        {
            $messageStatus = Nexmo::message()->send([
                'to'        => $data['to'],
                'from'      => 'NEXMO',
                'text'      => $data['message']
            ]);

            if($messageStatus['status'] == 0)
            {
                
                $userid = $data['userid'];

                $insert = NexmoSms::create([
                        'sms'               => $message,
                        'message_id'        => $messageStatus['message-id'],
                        'created_by'        => $userid,
                        'modified_by'       => $userid,
                    ]);
            }
            return $messageStatus;
        }
    }
}
