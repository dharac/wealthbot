<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\EmailNotify;
use App\Deposit;
use Carbon\Carbon;
use Auth;

class Withdraw extends Model
{
	protected $table = 'withdraw';
	protected $primaryKey = 'withdrawcod';
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
  	protected $fillable = ['withdrawno', 'amount', 'depositid', 'deposit_type', 'redepositid', 'withdraw_type', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'];

	public static function withdrawMassPayment()
	{
		$data = Withdraw::
		join('users','users.id','=','withdraw.created_by')
		->where('withdraw.status','pending')
		->select('users.bitcoin_id','withdraw.amount','users.id','users.first_name','users.username','users.last_name')
        ->orderby('withdraw.created_at','desc')
		->get();
		return $data;
	}

	public static function createWithdrawNo()
    {
        $withdrawcod  = Withdraw::max('withdrawcod');
        if($withdrawcod == null || $withdrawcod == "")
        {
            $withdrawcod =  1;
        }
        else
        {
            $withdrawcod =  1 + $withdrawcod;
        }
        $letter = chr(rand(65,90));
        $number = rand(1,100);
        $orderNoString = 'WD'.$number.$letter.$withdrawcod;
        return $orderNoString;
    }

    public static function getLastNewWithdrawal()
    {
        $withdraw = Withdraw::join('users', 'withdraw.created_by', '=', 'users.id')
            ->select('withdraw.*','users.first_name','users.username','users.id')
            ->where('withdraw.status','pending')
            ->latest()
            ->limit(10)
            ->get();
        return $withdraw;
    }

    public static function totalWithdrawals()
    {
        $pending = Withdraw::where('status','pending')->count();
        $approved = Withdraw::where('status','!=','pending')->count();
        return array($pending,$approved);
    }

    public static function approveWithdrawal($ids = null)
    {
        $withdraws = Withdraw::join('users', 'withdraw.created_by', '=', 'users.id')
        ->whereIn('withdraw.withdrawcod',$ids)
        ->select('withdraw.*','users.first_name','users.username','users.email','users.getresponseid')
        ->get();

        if(count($withdraws) > 0)
        {
            foreach ($withdraws as $withdraw) 
            {
                if($withdraw->status == 'pending')
                {

                    $update = Withdraw::where('withdrawcod', $withdraw->withdrawcod)
                    ->update([ 
                        'status'        => 'approved',
                        'modified_by'   => Auth::user()->id,
                        'updated_at'    => Carbon::now(),
                    ]);

                    $update = 1;
                    if($update)
                    {
                        $sendArray  = array(
                                'link_id'  =>  $withdraw->withdrawcod,
                                'type'     =>  'withdraw-approve',
                                'user_id'  =>  $withdraw->created_by,
                        );
                        Notifications::Notify($sendArray);
                    }
                    
                    if($withdraw->withdraw_type == 'interest' || $withdraw->withdraw_type == 'deposit' || $withdraw->withdraw_type == 'initial' || $withdraw->withdraw_type == 'wallet')
                    {
                        if($update)
                        {
                            $depositno  = "";
                            $planame    = "";
                            if($withdraw->withdraw_type != 'wallet')
                            {
                                $depositid = $withdraw->depositid;
                                $dpdata = Deposit::join('plan_m','plan_m.planid','=','deposit.planid')
                                ->where('depositid',$depositid)->select('plan_m.plan_name','depositno')->first();

                                if(count($dpdata) > 0)
                                {
                                    $depositno = $dpdata->depositno;
                                    $planame   = $dpdata->plan_name;
                                }
                            }

                            if($withdraw->withdraw_type == 'deposit')
                            {
                                Deposit::where('depositid', $withdraw->depositid)->update(['status' => 'withdrawn','description' => 'Withdrawal Amount', 'updated_at' => Carbon::now() ]);
                            }

                            $content = [
                            'EMAIL'             =>  $withdraw->email,
                            'EMAIL-ID'          =>  $withdraw->getresponseid,
                            'DEPOSITID'         =>  $depositno,
                            'PLAN_NM'           =>  $planame,
                            'USERNAME'          =>  $withdraw->username,
                            'TXTAMT'            =>  number_format($withdraw->amount,2),
                            'FIRSTNAME'         =>  $withdraw->first_name,
                            'LOGINURL'          =>  url('login'),
                            'ADMINMAIL'         =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                            'SITENAME'          =>  config('services.SITE_DETAILS.SITE_NAME'),
                            'TYPE'              =>  'WITHDRAW-WALLET',
                            ];

                            EmailNotify::sendEmailNotification($content);
                        }
                    }
                    else if($withdraw->withdraw_type == 'commission')
                    {
                        if($update)
                        {
                            $content = [
                            'EMAIL'             =>  $withdraw->email,
                            'EMAIL-ID'          =>  $withdraw->getresponseid,
                            'USERNAME'          =>  $withdraw->username,
                            'FIRSTNAME'         =>  $withdraw->first_name,
                            'LOGINURL'          =>  url('login'),
                            'ADMINMAIL'         =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                            'SITENAME'          =>  config('services.SITE_DETAILS.SITE_NAME'),
                            'TYPE'              =>  'WITHDRAW-COMMISSION',
                            ];

                            EmailNotify::sendEmailNotification($content);
                        }
                    }
                }
            }
        }
    }

    public static function amountPendingWithdrawls() 
    {
        $pending = Withdraw::where('status','pending')->sum('amount');
        return $pending;
    }


    public static function withdrawInsert($data = null) 
    {
        $withdrawno = Withdraw::createWithdrawNo();

        $status         = $data['status'];
        $depositid      = $data['depositid'];
        $amount         = $data['amount'];
        $withdraw_type  = $data['withdraw_type'];
        $userid         = $data['userid'];
        $deposit_type   = $data['deposit_type'];
        $redepositid    = $data['redepositid'];

        $withdraw = Withdraw::create([
                'withdrawno'            => $withdrawno,
                'depositid'             => $depositid,
                'deposit_type'          => $deposit_type,
                'redepositid'           => $redepositid,
                'status'                => $status,
                'amount'                => $amount,
                'withdraw_type'         => $withdraw_type,
                'created_by'            => $userid,
                'modified_by'           => $userid,
            ]);

        return $withdraw;
    }



}
