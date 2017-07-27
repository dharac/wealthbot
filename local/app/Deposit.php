<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\LevelCommision;
use Carbon\Carbon;
use App\InterestPayment;
use App\myCustome\myCustome;
use App\ReDeposit;
use App\EmailNotify;
use App\WalletAmountInOut;
use App\Setting;
use App\BitcoinPriceDeposit;
use Session;

class Deposit extends Model
{
    protected $table = 'deposit';
    protected $primaryKey = 'depositid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['planid','depositno', 'amount','description','status','payment_through','currency','maturity_date','approvd_dt','transaction_id', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'planid');
    }

    public static function getLastNewInvestment()
    {
        $investments = Deposit::orderby('deposit.created_at','desc')->limit(10)->get();
        return $investments;
    }

    public static function insertDeposite($data = null)
    {
    	if($data)
    	{
            $user_id        = $data['user_id'];
            $planid         = $data['planid'];
            $tranId         = $data['transaction_id'];
            $amount         = $data['amount'];
            $depositdt      = $data['depositdt'];
            $description    = $data['description'];

            $depositCnt = Deposit::where('transaction_id',$tranId)->where('created_by',$user_id)->count();

            if($depositCnt == 0)
            {
                $plan = Plan::where('planid',$planid)->first();
                
                $today          = Carbon::now();
                $maturity_date  = $today->toDateString();

                if(count($plan) > 0)
                {
                    $profit                 = $plan->profit;
                    $interest_period_type   = $plan->interest_period_type;
                    $duration               = $plan->duration;
                    $duration_time          = $plan->duration_time;
                    $nature_of_plan         = $plan->nature_of_plan;
                    $plan_status            = $plan->plan_status;


                    if($plan_status == '1')
                    {
                        $duration       = intval($duration);
                        $getDate        = myCustome::getAccDate($today,$duration_time,$duration);
                        $maturity_date  = $getDate->toDateString();
                    }
                }

                $depositno = Deposit::createDepositNo();

                if($depositdt != "")
                {
                    $dpdt = $depositdt;
                }
                else
                {
                    $dpdt = Carbon::now();
                }

                $deposit_approve_on_bitcoin_rate = 0;
                $bitcoin_currency = "";
                if($data['payment_through'] == 'coinpayment')
                {
                    $deposit_approve_on_bitcoin_rate = Setting::getData('deposit_approve_on_bitcoin_rate');
                    if($deposit_approve_on_bitcoin_rate == 1)
                    {
                        $data['status'] = 'pending';
                    }

                    $bitcoin_currency = strtolower($data['bitcoin_currency']);
                    
                    if($bitcoin_currency == "ltct") {
                        $data['bitcoin_currency'] = "btc";
                    }

                    $bitcoin_currency = $data['bitcoin_currency'];
                }

        		$insert = Deposit::create([
                    'transaction_id'    =>  $data['transaction_id'],
                    'depositno'         =>  $depositno,
    				'planid' 			=>	$data['planid'],
                    'description'       =>  $data['description'],
    				'amount' 			=>	$amount,
    				'status' 			=>	$data['status'],
                    'payment_through'   =>  $data['payment_through'],
                    'currency'          =>  $data['currency'],
                    'maturity_date'     =>  $maturity_date,
    				'created_by' 		=>	$user_id,
    				'modified_by' 	    =>	$user_id,
                    'approvd_dt'        =>  $dpdt,
                    'created_at'        =>  $dpdt,
                    'updated_at'        =>  $dpdt,
    			]);
                
                if($insert)
                {
                    $sendArray  = array(
                        'link_id'  =>  $insert->depositid,
                        'type'     =>  'deposit',
                        'user_id'  =>  $user_id,
                        'amount'   =>  $amount,
                    );
                    Notifications::Notify($sendArray);

                    $investment = Deposit::getSingleRecord($insert->depositid);

                    if($data['payment_through'] == 'coinpayment')
                    {
                        if($deposit_approve_on_bitcoin_rate == 1)
                        {
                            $btcInsertArr  = array(
                            'depositid'         =>  $insert->depositid,
                            'status'            =>  'pending',
                            'amount'            =>  $amount,
                            'bitcoin_currency'  =>  $bitcoin_currency,
                            'currency'          =>  $data['currency'],
                            'created_by'        =>  $user_id,
                            );
                            $btcInsert = BitcoinPriceDeposit::InsertSingleRecord($btcInsertArr);
                        }
                    }


                    if($data['payment_through'] == 'coinpayment' || $data['payment_through'] == 'wallet')
                    {
                        $content = [
                        'EMAIL'             =>  $investment->email,
                        'EMAIL-ID'          =>  $investment->getresponseid,
                        'FIRSTNAME'         =>  $investment->first_name,
                        'USERNAME'          =>  $investment->username,
                        'PLAN_NM'           =>  $investment->plan_name,
                        'PAYMENT_THROUGH'   =>  $investment->payment_through,
                        'TRANS_ID'          =>  $investment->transaction_id,
                        'AMOUNT'            =>  number_format($investment->amount,2),
                        'CURRENCY'          =>  $investment->currency,
                        'LOGINURL'          =>  url('login'),
                        'ADMINMAIL'         =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                        'SITENAME'          =>  config('services.SITE_DETAILS.SITE_NAME'),
                        'TYPE'              =>  'DEPOSIT-AMOUNT',
                        ];
                        EmailNotify::sendEmailNotification($content);
                    }
                    return $insert;
                }
    	    }
        }   
    }

    public static function payoutOnDeposit()
    {
        ini_set('max_execution_time', 800);
        ini_set('memory_limit','1024M');
        
        $deposits = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->join('users', 'users.id', '=', 'deposit.created_by')
        ->where('plan_m.status','active')
        ->where('users.status','active')
        ->where('deposit.status','approved')
        ->whereDate('deposit.created_at', '<', Carbon::now()->addDays(-24)->toDateString())
        ->orderby('deposit.depositid','asc')
        ->select('plan_m.founder','plan_m.new_founder','plan_m.planid','plan_m.profit','plan_m.interest_period_type','plan_m.plan_status','plan_m.nature_of_plan','plan_m.duration','plan_m.duration_time','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','users.founder as usrfounder')
        ->get();

        $settings = array(
                'sustainability_mode'                           => Setting::getData('sustainability_mode'),
                'new_sustainability_mode_on_existing_old_plans' => Setting::getData('new_sustainability_mode_on_existing_old_plans'),
                'founder_sustainablity'                         => Setting::getData('founder_sustainablity'),
                'non_founder_sustainablity'                     => Setting::getData('non_founder_sustainablity'),
                'lender'                                        => Setting::getData('lender'),
                'marketeer'                                     => Setting::getData('marketeer'),
                'wealthbot'                                     => Setting::getData('wealthbot'),
                'non_lender'                                    => Setting::getData('non_lender'),
                'non_marketeer'                                 => Setting::getData('non_marketeer'),
                'non_wealthbot'                                 => Setting::getData('non_wealthbot'),
            );
        
        Session::push('settings', $settings);

        $today = Carbon::now();

        if(count($deposits) > 0)
        {
            foreach ($deposits as $deposit)
            {
                $depositid              = $deposit->depositid;
                $nature_of_plan         = $deposit->nature_of_plan;
                $depositeCreatedDate    = $deposit->created_at;
                $depositeCreatedBy      = $deposit->created_by;
                $depositeAmount         = $deposit->amount;
                $initialDepositAmt      = $deposit->amount;
                $planEndorNot           = $deposit->plan_status;
                $planDuration           = intval($deposit->duration); //001
                $planPeriod             = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                $loanRepaymentPer       = $deposit->profit;
                $loanRepaymentPeriod    = $deposit->interest_period_type;
                $founder                = $deposit->founder;
                $new_founder            = $deposit->new_founder;
                $usrfounder             = $deposit->usrfounder;

                $redeposit  = ReDeposit::where('depositid',$deposit->depositid)
                ->select('amount','created_at')
                ->orderby('created_at','desc')
                ->first();

                if(count($redeposit) > 0)
                {
                    $depositeCreatedDate    = $redeposit->created_at;
                    $depositeAmount         = $redeposit->amount;
                }

                $dpdt               = clone $depositeCreatedDate;
                $date               = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                $cycleStartDt       = $depositeCreatedDate;
                $cycleEndDt         = $date;
                $planEndDate        = "";
                if(strtotime($today) >= strtotime($date))
                {
                    $actionWork = 1;
                    if($planEndorNot == 1)
                    {
                        $actionWork = 0;
                        $dpdate             = clone $depositeCreatedDate;
                        $planEndDate        = myCustome::getAccDate($deposit->created_at,$planPeriod,$planDuration);

                        if($date->toDateString() <= $planEndDate->toDateString())
                        {
                            $actionWork = 1;
                            if(strtotime($today) >= strtotime($planEndDate))
                            {
                                if($nature_of_plan != 4)
                                {
                                    Deposit::where('depositid', $depositid)->update(['status' => 'matured','maturity_date' => $planEndDate->toDateString() ]);
                                }
                            }
                        }
                    }

                    if($actionWork == 1)
                    {
                        $dataArray = array(
                        'planid'                => $deposit->planid,
                        'user_id'               => $depositeCreatedBy,
                        'amount'                => $depositeAmount,
                        'initialDepositAmt'     => $initialDepositAmt,
                        'loanRepaymentPer'      => $loanRepaymentPer,
                        'loanRepaymentPeriod'   => $loanRepaymentPeriod,
                        'depositid'             => $depositid,
                        'nature_of_plan'        => $nature_of_plan,
                        'customeDate'           => $date,
                        'planEndorNot'          => $planEndorNot,
                        'planEndDate'           => $planEndDate,
                        'cycleStartDt'          => $cycleStartDt,
                        'cycleEndDt'            => $cycleEndDt,
                        'founder'               => $founder,
                        'new_founder'           => $new_founder,
                        'usrfounder'            => $usrfounder,
                        );
                        $status = Deposit::checkInterestPaymentEarn($dataArray);
                    }
                }
            }
        }
    }

    public static function checkInterestPaymentEarn($data = null)
    {
        $dataArray = array(
        'planid'                => $data['planid'],
        'user_id'               => $data['user_id'],
        'amount'                => $data['amount'],
        'initialDepositAmt'     => $data['initialDepositAmt'],
        'loanRepaymentPer'      => $data['loanRepaymentPer'],
        'loanRepaymentPeriod'   => $data['loanRepaymentPeriod'],
        'depositid'             => $data['depositid'],
        'nature_of_plan'        => $data['nature_of_plan'],
        'customeDate'           => $data['customeDate'],
        'planEndorNot'          => $data['planEndorNot'],
        'planEndDate'           => $data['planEndDate'],
        'cycleStartDt'          => $data['cycleStartDt'],
        'cycleEndDt'            => $data['cycleEndDt'],
        'founder'               => $data['founder'],
        'new_founder'           => $data['new_founder'],
        'usrfounder'            => $data['usrfounder'],
        );

        $today = Carbon::now();

        $ip = InterestPayment::whereDate('created_at', '=', $data['customeDate']->toDateString())
            ->where('depositid',$data['depositid'])
            ->where('created_by',$data['user_id'])
            ->count();

            if($ip == 0)
            {
                $loopCount = 1;
                if($data['planEndorNot'] == 1)
                {
                    if(strtotime($data['customeDate']) <= strtotime($data['planEndDate']))
                    {
                        $loopCount = 1;
                    }
                    else
                    {
                        $loopCount = 0;
                    }
                }

                if($loopCount == 1)
                {
                    //PLAN DEPOSIT AMOUNT PER PROFIT
                    $dataArray2 = array();
                    $dataArray2 = InterestPayment::userProfitAmount($dataArray);

                    //LEVEL COMMISION
                    $levelCommision = LevelCommision::shareLevelCommision($dataArray2);

                    //RE DEPOSIT ADD AFTER TIME PERIODS
                    $redeposit = ReDeposit::redepositInsert($dataArray2);
                }
            }

        $date       = "";
        $cstdt      = clone $data['customeDate'];
        $date       = myCustome::getAccDate($cstdt,$data['loanRepaymentPeriod'],1);
        if(strtotime($today) >= strtotime($date))
        {
            $dataArray['customeDate'] = $date;
            $status = Deposit::checkInterestPaymentEarn($dataArray);
        }
    }

    public static function getSingleRecord($id = null)
    {
        $investment = Deposit::join('users', 'deposit.created_by', '=', 'users.id')
            ->join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
            ->where('deposit.depositid',$id)
            ->select('users.first_name','users.getresponseid','users.last_name','users.email','users.username','plan_m.plan_name','plan_m.plan_status', 'deposit.*')
            ->first();
        return $investment;
    }

    public static function createDepositNo()
    {
        $depositid  = Deposit::max('depositid');
        if($depositid == null || $depositid == "")
        {
            $depositid =  1;
        }
        else
        {
            $depositid =  1 + $depositid;
        }
        $letter = chr(rand(65,90));
        $number = rand(1,100);
        $orderNoString = 'DEP'.$number.$letter.$depositid;
        return $orderNoString;
    }

    public static function getActiveDeposit()
    {
        $deposits   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->join('users', 'users.id', '=', 'deposit.created_by')
        ->where('plan_m.status','active')
        ->where('users.status','active')
        ->where('deposit.status','approved')
        ->orderby('deposit.depositid','desc')
        ->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','users.username','users.first_name','users.last_name','users.id')
        ->get();

        return $deposits;
    }


    public static function getActiveDepositNatureOfPlanWise()
    {
        $deposits   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->join('users', 'users.id', '=', 'deposit.created_by')
        ->where('plan_m.status','active')
        ->where('users.status','active')
        ->where('plan_m.nature_of_plan','!=','3')
        //->where('deposit.status','approved')
        ->orderby('deposit.depositid','desc')
        ->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','users.username','users.first_name','users.last_name','users.id','deposit.status as dpstatus')
        ->get();
        return $deposits;
    }

    public static function getActiveDepositUserWise($userlist = null)
    {
        $deposits   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->join('users', 'users.id', '=', 'deposit.created_by')
        ->where('plan_m.status','active')
        ->where('deposit.status','approved')
        ->whereIn('deposit.created_by',$userlist)
        ->orderby('deposit.depositid','desc')
        ->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','users.username','users.first_name','users.last_name','users.id')
        ->get();

        return $deposits;
    }

    public static function activeTotalLenders($result='')
    {

       $cnt   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->where('plan_m.status','active')
        ->where('deposit.status','approved')
        ->select('deposit.created_by','deposit.created_at')
        ->groupBy('deposit.created_by')
        ->get(); 

        if($result=='')
        {    
            $count = count($cnt);
            return $count;
        }
        return $cnt;
    }

    public static function getDeposite() 
    {
        $getDeposits   = Deposit::select('created_at','amount')->orderby('deposit.depositid','desc')->get();
        return $getDeposits;
    }

    public static function activeTotalLendersMoreThanDeposit($value)
    {
        $activelenderscounts = array();
        foreach ($value as $val) 
        {
            $cnt = \DB::select("select `deposit`.`created_by`, count(deposit.created_by) as total from `deposit` inner join `plan_m` on `plan_m`.`planid` = `deposit`.`planid` where `plan_m`.`status` = 'active' and `deposit`.`status` = 'approved' group by `deposit`.`created_by` having count(deposit.created_by) > $val order by `total` asc");
            $activelenderscounts[$val] = count($cnt);
        }
        return $activelenderscounts;
    }

    public static function planChangeDescription($plannm = null,$depositno = null)
    {
        $description  = 'This Plan changed to '.$plannm.' (Deposit ID : '.$depositno.' )';
        return $description;
    }

    public static function planSubscriptionByUser($planid = null)
    {
         $plans = \DB::select("SELECT count(*) as cnt FROM ( SELECT count(*) as cnt FROM deposit inner join `users` on `users`.`id` = `deposit`.`created_by` WHERE planid = ".$planid." and deposit.status = 'approved' and users.status = 'active' group by deposit.created_by ) AS x");
        return $plans;
    }
}