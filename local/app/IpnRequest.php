<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\myCustome\myCustome;
use App\User;
use App\SendSms;

class IpnRequest extends Model
{
	protected $table = 'ipn_request';
	protected $primaryKey = 'ipnid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['transaction_id', 'amount', 'post_contents', 'message', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'];


    public static function insertIpn($data = null)
    {
    	if($data)
    	{
            $status_code        = $data['status_code'];
            $amount2            = $data['amount2'];
            $received_amount    = $data['received_amount'];

    		$insert = IpnRequest::create([
			'transaction_id' 	=> $data['transaction_id'],
			'amount' 			=> $data['amount'],
			'post_contents' 	=> $data['post_contents'],
			'message' 			=> $data['message'],
			'status' 			=> $data['status'],
			'created_by'		=> $data['user_id'],
			'modified_by'		=> $data['user_id'],
			]);

            if($status_code == -1)
            {
                $ipnarr = array(
                    'message' => $data['status'],
                    'userid'  => $data['user_id'],
                    'amount'  => $data['amount'],
                    );

                IpnRequest::paymentCancelTimeOut($ipnarr);

            }
            else if($status_code == 0)
            {
                if($amount2 != $received_amount)
                {
                    if($received_amount != 0)
                    {
                        $ipnarr = array(
                        'message' => $data['status'],
                        'userid'  => $data['user_id'],
                        'amount'  => $data['amount'],
                        );
                        IpnRequest::waitingForPayment($ipnarr);
                    }
                }
            }
			
			return $insert;
    	}
    }

    public static function paymentCancelTimeOut($data = null)
    {
        $message  =  $data['message'];
        $userid   =  $data['userid'];
        $amount   =  $data['amount'];

        $user = User::where('id',$userid)->select('id','username','coucod','first_name','email','getresponseid','phone')->first();

        if(count($user) > 0)
        {

            $sms = [
            'ID'                    =>  $user->id,
            'FIRSTNAME'             =>  $user->first_name,
            'PHONE'                 =>  $user->phone,
            'USERNAME'              =>  $user->username,
            'COUCOD'                =>  $user->coucod,
            'AMOUNT'                =>  number_format($amount,2),
            'TYPE'                  =>  'PAYMENT-CANCELLED',
            ];

            SendSms::WealthbotSMS($sms);


            $content = [
            'EMAIL'                 =>  $user->email,
            'EMAIL-ID'              =>  $user->getresponseid,
            'FIRSTNAME'             =>  ucwords(strtolower($user->first_name)),
            'AMOUNT'                =>  number_format($amount,2),
            'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
            'TYPE'                  =>  'PAYMENT-CANCELLED',
            ];
            EmailNotify::sendEmailNotification($content);
        }

    }

    public static function waitingForPayment($data = null)
    {
        $message  =  $data['message'];
        $userid   =  $data['userid'];
        $amount   =  $data['amount'];

        $user = User::where('id',$userid)->select('first_name','email','username','getresponseid','coucod','phone')->first();

        if(count($user) > 0)
        {

            $sms = [
            'ID'                    =>  $userid,
            'FIRSTNAME'             =>  $user->first_name,
            'PHONE'                 =>  $user->phone,
            'USERNAME'              =>  $user->username,
            'COUCOD'                =>  $user->coucod,
            'COINPAYMENT_MESSAGE'   =>  $message,
            'TYPE'                  =>  'AMOUNT-DIFFERENCE',
            ];

            SendSms::WealthbotSMS($sms);

            $content = [
            'EMAIL'                 =>  $user->email,
            'EMAIL-ID'              =>  $user->getresponseid,
            'FIRSTNAME'             =>  ucwords(strtolower($user->first_name)),
            'COINPAYMENT_MESSAGE'   =>  $message,
            'AMOUNT'                =>  number_format($amount,2),
            'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
            'TYPE'                  =>  'WAITING-BYUER-FUND',
            ];
            EmailNotify::sendEmailNotification($content);
        }
    }

    public static function getTotalNotCompletedPayments()
    {        
       $payment =  IpnRequest::select('amount')
                ->groupBy('transaction_id')
                ->where('status', '=' ,'Cancelled / Timed Out')
                ->get();                  
       return $payment;
    }

    public static function getCountOfUserPayments()
    {
        $payment =  IpnRequest::select('amount')
                ->groupBy('transaction_id')
                ->where('status', '=' ,'Complete')
                ->get();                  
       return $payment;
    }

}
