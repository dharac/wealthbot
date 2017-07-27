<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Country;
use App\SmsManagement;
use App\myCustome\myCustome;

class SendSms extends Model
{
    protected $table = 'send_sms';
    protected $primaryKey = 'smsid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sms','message_id','created_by', 'modified_by', 'created_at', 'updated_at'];
    
    public static function WealthbotSMS($data = null)
    {
        if (\App::environment('local'))
        {
            return;
        }

        $data['SIGNATURE']     = "\r\nFor help: Go to http://SUPPORT.WealthBot.ONLINE\r\n\r\nWealthBot";
        $signatureManagement  = SmsManagement::where('smsid',6)->where('status','active')->select('body')->first();
        if(count($signatureManagement) > 0)
        {
            $data['SIGNATURE']     = $signatureManagement->body;
        }
        $cntcod = Country::where('coucod',$data['COUCOD'])->select('cou_code')->first();
        if(count($cntcod) > 0)
        {
            if(array_key_exists("PHONE",$data))
            {
                $phoneno        = '+'.$cntcod->cou_code.$data['PHONE'];
                $orgPhone       = $data['PHONE'];
                $sendsms        = 0;
                $messageBody    = "";
                if($data['TYPE'] == 'AMOUNT-DIFFERENCE')
                {
                    $smsManagement  = SmsManagement::findOrFail(1);
                    if($smsManagement->status == 'active')
                    {
                        $data['BODY']   = $smsManagement->body;
                        $messageBody    = SendSms::createSmsBody($data);
                        $sendsms        = 1;
                    }
                }
                else if($data['TYPE'] == 'PAYMENT-CANCELLED')
                {
                    $smsManagement  = SmsManagement::findOrFail(2);
                    if($smsManagement->status == 'active')
                    {
                        $data['BODY']   = $smsManagement->body;
                        $messageBody    = SendSms::createSmsBody($data);
                        $sendsms        = 1;
                    }
                }
                else if($data['TYPE'] == 'PROFILE-UPDATE')
                {
                    $smsManagement  = SmsManagement::findOrFail(3);
                    if($smsManagement->status == 'active')
                    {
                        $data['BODY']   = $smsManagement->body;
                        $messageBody    = SendSms::createSmsBody($data);
                        $sendsms        = 1;
                    }
                }
                else if($data['TYPE'] == 'TICKET-REPLY')
                {
                    $smsManagement  = SmsManagement::findOrFail(4);
                    if($smsManagement->status == 'active')
                    {
                        $data['BODY']   = $smsManagement->body;
                        $messageBody    = SendSms::createSmsBody($data);
                        $sendsms        = 1;
                    }
                }
                else if($data['TYPE'] == 'BITCOIN-CHANGE')
                {
                    $smsManagement  = SmsManagement::findOrFail(5);
                    if($smsManagement->status == 'active')
                    {
                        $data['BODY']   = $smsManagement->body;
                        $messageBody    = SendSms::createSmsBody($data);
                        $sendsms        = 1;
                    }
                }

                $logStore = 0;
                $messageError = '';
                if($sendsms == 1)
                {
                    if($orgPhone != "" && $messageBody != "")
                    {
                        $messageCode  = myCustome::SinchSms($phoneno,$messageBody);
                        SendSms::insertSms($messageCode,$messageBody,$data['ID']);
                    }
                    else
                    {
                        $logStore = 1;
                        $messageError = 'Some error in Cell phone please check.';
                        if(array_key_exists("ID",$data))
                        {
                            SendSms::insertSms('',$messageError,$data['ID']);
                        }
                    }
                }

            }
        }
    }

    public static function createSmsBody($data = null)
    {
        $temps = array('ID','FIRSTNAME','PHONE','USERNAME','COUCOD','COINPAYMENT_MESSAGE','TYPE','SIGNATURE','AMOUNT','SUBJECT','TICKET_NO');
        $finalString = $data['BODY'];
        foreach ($temps as $key => $value) 
        {
            if (strpos($finalString,$value) !== false)
            {
                $finalString = str_replace('['.$value.']',$data[$value],$finalString);
            }
        }
        return $finalString;
    }

    public static function insertSms($data = null,$messageBody = null,$userid)
    {
        $result = json_decode($data);
        $ERROR = 0;
        if (json_last_error() === JSON_ERROR_NONE)
        {
            if($data)
            {
                $data = json_decode($data, true);
                if(array_key_exists("messageId",$data))
                {
                    $insert = SendSms::create([
                        'sms'               => $messageBody,
                        'message_id'        => $data['messageId'],
                        'created_by'        => $userid,
                        'modified_by'       => $userid
                    ]);
                }
                else
                {
                    $ERROR = 1;
                }
            }
            else
            {
                $ERROR = 1;
            }
        }
        else
        {
            $ERROR = 1;
        }

        if($ERROR == 1)
        {
            $insert = SendSms::create([
                    'sms'               => $messageBody.$result,
                    'message_id'        => 'error',
                    'created_by'        => $userid,
                    'modified_by'       => $userid
                ]);
        }

        return true;
    }
}
