<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\InterestPayment;
use App\Plan;
use App\Deposit;
use Carbon\Carbon;
use App\Referral;
use App\WalletAmountInOut;
use App\DepositCycleCount;
use App\Withdraw;
use App\myCustome\myCustome;
use Auth;

class ReDeposit extends Model
{
	protected $table = 'redeposit';
	protected $primaryKey = 'redepositid';
    public static $mixArrayLedger   = array();
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['depositid', 'amount', 'created_by', 'modified_by', 'created_at', 'updated_at'];


    public static function redepositInsert($data = null)
    {
    	if($data)
    	{
            $amount               = $data['redepositamount'];
            $planid               = $data['planid'];
            $loanRepaymentPeriod  = $data['loanRepaymentPeriod'];
            $planEndDate          = $data['planEndDate'];
            $planEndorNot         = $data['planEndorNot'];
            $customeDate          = $data['customeDate'];
            $depositid            = $data['depositid'];
            $interest             = $data['interest'];
            $user_id              = $data['user_id'];
            $cycleStartDt         = $data['cycleStartDt'];
            $cycleEndDt           = $data['cycleEndDt'];
            $nature_of_plan       = $data['nature_of_plan'];
            $initialDepositAmt    = $data['initialDepositAmt'];
            $cstdt                = clone $customeDate;
            $randomString         = myCustome::randomString();

            //CHECK IF USERS HAS CREATE REQUEST FOR PLAN CHANGE THAN APPLY THIS CODE
            $dpc    = DepositPlanChange::where('depositid',$depositid)
            ->where('created_by',$user_id)
            ->orderby('depo_plan_chng','desc')
            ->first();

            if($planid == '1' || $planid == '2' || $planid == '15')
            {
                $dcc = DepositCycleCount::where('depositid',$depositid)
                ->where('created_by',$user_id)
                ->count();
            }

            $planchangeAccept = 0;
            if(count($dpc) > 0)
            {
                if($dpc->status == 'approved')
                {
                    $planchangeAccept = 1;
                }
                else
                {
                    $planchangeAccept = 0;
                }
            }

            $today = Carbon::now();
            $ins = 1;
            $hasChangePlan = 0;
            $planchangeby = "";
            if($planchangeAccept == 1)
            {
                $pln = Plan::where('planid',$dpc->new_planid)->select('plan_name')->first();
                if($planEndorNot == 1)
                {
                    if($customeDate->toDateString() >= $planEndDate->toDateString()) { }
                    else { $pln = array(); }
                }

                if(count($pln) > 0)
                {
                    $ins            = 0;
                    $hasChangePlan  = 1;
                    $newplanid      = $dpc->new_planid;
                    $newplannm      = $pln->plan_name;
                    $planchangeby   = 'user';
                }
            }
            else
            {
                if($planid == '1' || $planid == '2' || $planid == '15')
                {
                    if($dcc >= 2)
                    {
                        $pln = Plan::where('planid',11)->select('plan_name')->first();
                        if(count($pln) > 0)
                        {
                            $ins            = 0;
                            $hasChangePlan  = 1;
                            $newplanid      = 11;
                            $newplannm      = $pln->plan_name;
                            $planchangeby   = 'admin';
                        }
                    }
                }
            }

            if($ins == 1)
            {
                //IF 90 DAYS PLAN MATURE THAN IT WILL GO IN ANOTHER 90 DAYS PLAN
                if($data['planEndorNot'] == 1)
                {
                    if(strtotime($customeDate) < strtotime($planEndDate)) { }
                    else
                    {
                        if($planid == 11)
                        {
                            $pln = Plan::where('planid',11)->select('plan_name')->first();
                            if(count($pln) > 0)
                            {
                                $ins            = 0;
                                $hasChangePlan  = 1;
                                $newplanid      = 11;
                                $newplannm      = $pln->plan_name;
                                $planchangeby   = 'admin';
                            }
                        }
                    }
                }
            }

            $afterDepositAmt = 0;
            $afterDepositAmt = $amount;
            if($hasChangePlan == 1)
            {
                if($nature_of_plan == 1)
                {
                    $afterDepositAmt = $interest + $amount;
                }
                else if($nature_of_plan == 4)
                {
                    if($planid == 15)
                    {
                        $dpc15    = DepositPlanChange::where('new_planid',$planid)
                        ->where('created_by',$user_id)
                        ->select('depositid','status')
                        ->orderby('depo_plan_chng','desc')
                        ->first();

                        if(count($dpc15) > 0)
                        {
                            if($dpc15->status == 'approved')
                            {
                                $lastdp = Deposit::where('depositid',$dpc15->depositid)
                                ->where('created_by',$user_id)
                                ->select('amount')
                                ->first();

                                if($lastdp)
                                {
                                    $initialDepositAmt = $lastdp->amount;
                                }
                            }
                        }
                    }

                    //WITHDRAW INITIAL DEPOSIT
                    $walletarray = array(
                    'amount'            => $initialDepositAmt,
                    'depositid'         => $depositid,
                    'deposit_type'      => '',
                    'redepositid'       => 0,
                    'status'            => 'withdraw_initial_deposit',
                    'created_by'        => $user_id,
                    );
                    WalletAmountInOut::InsertinWallet($walletarray);
                    $afterDepositAmt = $amount - $initialDepositAmt;
                }

                $dataArray = array(
                'user_id'           => $user_id,
                'planid'            => $newplanid,
                'payment_through'   => 'plan changed',
                'currency'          => 'USD',
                'amount'            => $afterDepositAmt,
                'status'            => 'approved',
                'transaction_id'    => 'DP-'.$randomString,
                'description'       => '',
                'depositdt'         => $customeDate,
                );

                $newDepositId = Deposit::insertDeposite($dataArray);

                //PLAN CHANGED PROCEDURE FOR PLAN ID 1 AND 2
                if($planchangeby == 'user')
                {
                    if($newDepositId)
                    {
                        if($newplanid == 1 || $newplanid == 2 || $newplanid == 15)
                        {
                            $cycleData = array(
                                'depositid' => $newDepositId->depositid,
                                'userid'    => $user_id,
                                'cycle'     => 1
                                );
                            DepositCycleCount::insertDepsitCycleCount($cycleData);
                        }
                    }
                }
                if($planchangeby == 'admin')
                {
                    $planchangeArray = array(
                        'old_planid'        => $planid,
                        'new_planid'        => $newplanid,
                        'depositid'         => $depositid,
                        'status'            => 'approved',
                        'userid'            => $user_id,
                        );
                    DepositPlanChange::insertPlanChange($planchangeArray);
                }

                $depono = "";
                $newdep = Deposit::where('depositid',$newDepositId->depositid)->select('depositno')->first();
                if(count($newdep) > 0)
                {
                    $depono = $newdep->depositno;
                }

                $description = Deposit:: planChangeDescription($newplannm,$depono);
                Deposit::where('depositid', $depositid)->update(['status' => 'plan_changed','description' => $description ,'updated_at' => $customeDate , 'modified_by' => $user_id ]);

                $walletarray = array(
                    'amount'            => $afterDepositAmt,
                    'depositid'         => $depositid,
                    'deposit_type'      => '',
                    'redepositid'       => 0,
                    'status'            => 'plan_changed',
                    'created_by'        => $user_id,
                    );

                WalletAmountInOut::InsertinWallet($walletarray);
            }

            if($ins == 1)
            {
                $loopCount = 1;
                if($data['planEndorNot'] == 1)
                {
                    if(strtotime($customeDate) < strtotime($planEndDate))
                    {
                        $loopCount = 1;
                    }
                    else
                    {
                        if($planid == 11)
                        {
                            $newplanid = 11;
                            $pln = Plan::where('planid',$newplanid)->select('plan_name')->first();
                            $afterDepositAmt = $amount;
                            if(count($pln) > 0)
                            {
                                $dataArray = array(
                                'user_id'           => $user_id,
                                'planid'            => $newplanid,
                                'payment_through'   => 'plan changed',
                                'currency'          => 'USD',
                                'amount'            => $afterDepositAmt,
                                'status'            => 'approved',
                                'transaction_id'    => 'DP-'.$randomString,
                                'description'       => '',
                                'depositdt'         => $customeDate,
                                );

                                $newDepositId = Deposit::insertDeposite($dataArray);

                                $depono = "";
                                $newdep = Deposit::where('depositid',$newDepositId->depositid)->select('depositno')->first();
                                if(count($newdep) > 0)
                                {
                                    $depono = $newdep->depositno;
                                }

                                $description = Deposit:: planChangeDescription($pln->plan_name,$depono);
                                Deposit::where('depositid', $depositid)->update(['status' => 'plan_changed','description' => $description ,'updated_at' => $customeDate , 'modified_by' => $user_id ]);

                                $walletarray = array(
                                    'amount'            => $afterDepositAmt,
                                    'depositid'         => $depositid,
                                    'deposit_type'      => '',
                                    'redepositid'       => 0,
                                    'status'            => 'plan_changed',
                                    'created_by'        => $user_id,
                                    );

                                WalletAmountInOut::InsertinWallet($walletarray);

                                $planchangeArray = array(
                                    'old_planid'        => $planid,
                                    'new_planid'        => $newplanid,
                                    'depositid'         => $depositid,
                                    'status'            => 'approved',
                                    'userid'            => $user_id,
                                    );
                                DepositPlanChange::insertPlanChange($planchangeArray);
                            }
                        }

                        if($nature_of_plan == 4)
                        {
                            //WITHDRAW INITIAL DEPOSIT
                            $walletarray = array(
                            'amount'            => $initialDepositAmt,
                            'depositid'         => $depositid,
                            'deposit_type'      => '',
                            'redepositid'       => 0,
                            'status'            => 'withdraw_initial_deposit',
                            'created_by'        => $user_id,
                            );
                            WalletAmountInOut::InsertinWallet($walletarray);

                            $afterDepositAmt = 0;
                            $afterDepositAmt = ($amount) - $initialDepositAmt;

                            $newplanid = 11;
                            $pln = Plan::where('planid',$newplanid)->select('plan_name')->first();
                            if(count($pln) > 0)
                            {
                                $dataArray = array(
                                'user_id'           => $user_id,
                                'planid'            => $newplanid,
                                'payment_through'   => 'plan changed',
                                'currency'          => 'USD',
                                'amount'            => $afterDepositAmt,
                                'status'            => 'approved',
                                'transaction_id'    => 'DP-'.$randomString,
                                'description'       => '',
                                'depositdt'         => $customeDate,
                                );

                                $newDepositId = Deposit::insertDeposite($dataArray);

                                $depono = "";
                                $newdep = Deposit::where('depositid',$newDepositId->depositid)->select('depositno')->first();
                                if(count($newdep) > 0)
                                {
                                    $depono = $newdep->depositno;
                                }

                                $description = Deposit:: planChangeDescription($pln->plan_name,$depono);
                                Deposit::where('depositid', $depositid)->update(['status' => 'plan_changed','description' => $description ,'updated_at' => $customeDate , 'modified_by' => $user_id ]);

                                $walletarray = array(
                                    'amount'            => $afterDepositAmt,
                                    'depositid'         => $depositid,
                                    'deposit_type'      => '',
                                    'redepositid'       => 0,
                                    'status'            => 'plan_changed',
                                    'created_by'        => $user_id,
                                    );

                                WalletAmountInOut::InsertinWallet($walletarray);

                                $planchangeArray = array(
                                    'old_planid'        => $planid,
                                    'new_planid'        => $newplanid,
                                    'depositid'         => $depositid,
                                    'status'            => 'approved',
                                    'userid'            => $user_id,
                                    );
                                DepositPlanChange::insertPlanChange($planchangeArray);
                            }
                        }
                        else if($nature_of_plan == 1)
                        {
                            $afterDepositAmt = 0;
                            $afterDepositAmt = $interest + $amount;

                            $walletarray = array(
                                'amount'            => $afterDepositAmt,
                                'depositid'         => $depositid,
                                'deposit_type'      => '',
                                'redepositid'       => 0,
                                'status'            => 'all_out',
                                'created_by'        => $user_id,
                                );

                            WalletAmountInOut::InsertinWallet($walletarray);
                        }
                        $loopCount = 0;
                    }
                }

                if($loopCount == 1)
                {
                    $insert = ReDeposit::create([
                    'depositid'     =>  $data['depositid'],
                    'amount'        =>  $amount,
                    'created_by'    =>  $data['user_id'],
                    'modified_by'   =>  $data['user_id'],
                    'created_at'    =>  $customeDate,
                    'updated_at'    =>  $customeDate,
                    ]);
                    return $insert;
                }
            }
    	}
    }

    public static function getDetails($startdt = null,$enddt = null,$userid = null,$type = null,$depositid = null)
    {
        $usernameRequired = '0';
        if($type == 'admin')
        {
            $deposits = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
            ->whereBetween('deposit.created_at', [$startdt, $enddt])
            ->where('plan_m.status','active')
            ->where('deposit.created_by',$userid)
            ->orderby('deposit.depositid','desc')
            ->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','deposit.status','deposit.maturity_date','deposit.description','deposit.depositno')
            ->get();
        }
        else if($type == 'chart')
        {
            $deposits   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
            ->where('plan_m.status','active')
            ->where('deposit.created_by',$userid)
            ->where('deposit.depositid',$depositid)
            ->orderby('deposit.depositid','desc')
            ->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','deposit.description','deposit.depositno')
            ->get();
        }
        else if($type == 'dashboard')
        {
            $usernameRequired = '0';

            if(is_array($userid))
            {
                $useridArray = $userid;
                $sarray =  array();
                foreach ($useridArray as $key => $value)
                {
                    array_push($sarray, $key);
                }
                $useridArray = $sarray;
                $usernameRequired = '1';
            }
            else
            {
                $useridArray = array($userid);
            }

            $deposits   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
            ->join('users', 'users.id', '=', 'deposit.created_by')
            ->where('deposit.status','approved')
            ->where('plan_m.status','active')
            ->whereIn('deposit.created_by',$useridArray)
            ->orderby('deposit.depositid','desc')
            ->select('users.first_name','users.username','users.last_name','users.id','plan_m.planid','plan_m.interest_period_type','plan_m.plan_name','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','deposit.description','deposit.depositno')
            ->get();
        }
        else
        {
            $deposits   = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
            ->where('plan_m.status','active')
            ->where('deposit.created_by',$userid)
            ->orderby('deposit.depositid','desc')
            ->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount','deposit.status','deposit.maturity_date','deposit.description','deposit.depositno')
            ->get();
        }

        $sustainability_mode = Setting::getData('sustainability_mode');

        $data = array();
        if(count($deposits) > 0)
        {
            $depositSpecialStatus  = 0;
            foreach ($deposits as $key => $deposit)
            {
                $depositid                  = $deposit->depositid;
                $created_by                 = $deposit->created_by;
                $interestPer                = $deposit->profit;
                $nature_of_plan             = $deposit->nature_of_plan;
                $intPercent                 = $deposit->profit;
                $planEndorNot               = $deposit->plan_status;
                $planPeriod                 = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                $planDuration               = intval($deposit->duration); //001
                $loanRepaymentPeriod        = $deposit->interest_period_type;
                $startDate                  = "";
                $amount                     = "";
                $levelUser                  = 0;
                $levelCommission            = 0;
                $userLevelCommision         = 0;
                $userLevelCommisionRate     = 0;
                $userLevelCommisionStatus   = "";
                $refernceid                 = 0;

                if($usernameRequired == '1')
                {
                    $levelStatus    = $userid[$deposit->created_by][0];
                    $refernceid     = $userid[$deposit->created_by][1];

                    $plnLevels      = PlanLevel::where('planid',$deposit->planid)->where('level',$levelStatus)->select('commision')->first();

                    if(count($plnLevels) > 0)
                    {
                        $levelUser       = $levelStatus;
                        $levelCommission = $plnLevels->commision;
                    }
                }

                $interestPayments   = InterestPayment::where('depositid',$deposit->depositid)
                ->where('interest_type','deposit')
                ->where('created_by',$created_by)
                ->select('pro_amount','created_at','status')
                ->first();

                $depositSpecialStatus = 0;
                if($deposit->status == 'withdrawn')
                {
                    if(!$interestPayments)
                    {
                        $depositSpecialStatus = 1;
                    }
                }

                $levelCommissions   = LevelCommision::where('depositid',$deposit->depositid)
                ->where('commission_type','deposit')
                ->where('downlineid',$created_by)
                ->where('created_by',Referral::$cmbUser)
                ->select('commission','com_rate')
                ->first();

                if(count($interestPayments) > 0)
                {
                    $startDate      = $deposit->created_at;
                    $endDate        = $interestPayments->created_at;
                    $amount         = $deposit->amount;
                    $totalInterest  = $interestPayments->pro_amount;

                    if(count($levelCommissions) > 0)
                    {
                        $userLevelCommision     = $levelCommissions->commission;
                        $userLevelCommisionRate = $levelCommissions->com_rate;
                        $userLevelCommisionStatus = 'available';
                    }

                     $temp = array(
                        'startDate'                 => $startDate,
                        'endDate'                   => $endDate,
                        'amount'                    => $amount,
                        'depositid'                 => $deposit->depositid,
                        'interest_period_type'      => $deposit->interest_period_type,
                        'level'                     => $levelUser,
                        'levelCommission'           => $levelCommission,
                        'totalInterest'             => $totalInterest,
                        'userLevelCommision'        => $userLevelCommision,
                        'userLevelCommisionRate'    => $userLevelCommisionRate,
                        'userLevelCommisionStatus'  => $userLevelCommisionStatus,
                        'refernceid'                => $refernceid,
                        'depositspecialstatus'      => $depositSpecialStatus,
                        );

                    if($type == 'chart')
                    {
                        $data = $temp;
                    }
                    else if($type == 'dashboard')
                    {
                        $data['data'.$deposit->depositid] = $temp;
                    }
                    else
                    {
                        array_push($data,$temp);  
                    }
                }
                else
                {

                    $levelids  = Referral::getReferralDownlineLevel(Referral::$cmbUser,$deposit->created_by,1,4);
                    $plnLevels = PlanLevel::where('planid',$deposit->planid)->where('level',$levelids)->select('commision')->first();

                    $plncommision = 0;
                    if(count($plnLevels) > 0)
                    {
                        $plncommision = $plnLevels->commision;
                    }
                    
                    $startDate      = $deposit->created_at;
                    $firstDuration  = myCustome::getAccDate($startDate,$loanRepaymentPeriod,1);
                    $endDate        = $firstDuration;
                    $amount         = $deposit->amount;

                    if($nature_of_plan == 1 || $nature_of_plan == 2 || $nature_of_plan == 3 || $nature_of_plan == 4)
                    {
                        //FIRST TIME INTEREST
                        $totalInterest             = $amount * $intPercent / 100;
                    }

                    $userLevelCommision = 0;
                    $userLevelCommision = LevelCommision::countCommission($sustainability_mode,$totalInterest,$amount,$plncommision);
                    $userLevelCommisionRate = $plncommision;
                    $userLevelCommisionStatus = 'pending';

                     $temp = array(
                        'startDate'                 => $deposit->created_at,
                        'endDate'                   => $endDate,
                        'amount'                    => $amount,
                        'depositid'                 => $deposit->depositid,
                        'interest_period_type'      => $deposit->interest_period_type,
                        'level'                     => $levelUser,
                        'levelCommission'           => $levelCommission,
                        'totalInterest'             => $totalInterest,
                        'userLevelCommision'        => $userLevelCommision,
                        'userLevelCommisionRate'    => $userLevelCommisionRate,
                        'userLevelCommisionStatus'  => $userLevelCommisionStatus,
                        'refernceid'                => $refernceid,
                        'depositspecialstatus'      => $depositSpecialStatus,
                        );

                    if($type == 'chart')
                    {
                        $data = $temp;
                    }
                    else if($type == 'dashboard')
                    {
                        $data['data'.$deposit->depositid] = $temp;
                    }
                    else
                    {
                        array_push($data,$temp);
                    }
                }

                if($type == 'chart' || $type == 'dashboard')
                {
                    $redeposites  = ReDeposit::where('depositid',$deposit->depositid)
                    ->select('amount','created_at','redepositid')
                    ->orderby('created_at','desc')
                    ->limit(1)
                    ->get();
                }
                else
                {
                    $redeposites  = ReDeposit::where('depositid',$deposit->depositid)
                    ->select('amount','created_at','redepositid')
                    ->orderby('created_at','asc')
                    ->get();
                }

                if(count($redeposites) > 0)
                {
                    foreach ($redeposites as $redeposite)
                    {
                        $interestPayments   = InterestPayment::where('depositid',$redeposite->redepositid)
                        ->where('interest_type','redeposit')
                        ->select('pro_amount','created_at','status')
                        ->first();

                        $levelCommissions   = LevelCommision::where('depositid',$redeposite->redepositid)
                        ->where('commission_type','redeposit')
                        ->where('downlineid',$created_by)
                        ->where('created_by',Referral::$cmbUser)
                        ->select('commission','com_rate')
                        ->first();
                        
                        $totalInterest  = 0;
                        $a = 1;
                        if(count($interestPayments) > 0)
                        {
                            $totalInterest  = $interestPayments->pro_amount;
                            $endDate        = $interestPayments->created_at;
                        }
                        else
                        {
                            $loanRepaymentPeriod    = $deposit->interest_period_type;
                            $endDate                = myCustome::getAccDate($redeposite->created_at,$loanRepaymentPeriod,1);

                            if($planEndorNot == 1)
                            {
                                $planEndDate = myCustome::getAccDate($deposit->created_at,$planPeriod,$planDuration);

                                if($endDate->toDateString() > $planEndDate->toDateString())
                                {
                                    $a = 0;
                                }
                            }

                            $totalInterest          = ($redeposite->amount * $interestPer) / 100;
                            $loanRepaymentPeriod    = $deposit->interest_period_type;
                            $endDate                = myCustome::getAccDate($redeposite->created_at,$loanRepaymentPeriod,1);
                        }

                        if(count($levelCommissions) > 0)
                        {
                            $userLevelCommision         = $levelCommissions->commission;
                            $userLevelCommisionRate     = $levelCommissions->com_rate;
                            $userLevelCommisionStatus   = 'available';
                        }
                        else
                        {

                            $levelids  = Referral::getReferralDownlineLevel(Referral::$cmbUser,$deposit->created_by,1,4);
                            $plnLevels = PlanLevel::where('planid',$deposit->planid)->where('level',$levelids)->select('commision')->first();

                            $plncommision = 0;
                            if(count($plnLevels) > 0)
                            {
                                $plncommision = $plnLevels->commision;
                            }

                            $userLevelCommision     = 0;
                            $userLevelCommision     = LevelCommision::countCommission($sustainability_mode,$totalInterest,$redeposite->amount,$plncommision);
                            $userLevelCommisionRate = $plncommision;
                            $userLevelCommisionStatus = 'pending';
                        }

                        if($a == 1)
                        {
                            $startDate      = $redeposite->created_at;
                            $amount         = $redeposite->amount;

                             $temp = array(
                                'startDate'                 => $startDate,
                                'endDate'                   => $endDate,
                                'amount'                    => $amount,
                                'depositid'                 => $deposit->depositid,
                                'interest_period_type'      => $deposit->interest_period_type,
                                'level'                     => $levelUser,
                                'levelCommission'           => $levelCommission,
                                'totalInterest'             => $totalInterest,
                                'userLevelCommision'        => $userLevelCommision,
                                'userLevelCommisionRate'    => $userLevelCommisionRate,
                                'userLevelCommisionStatus'  => $userLevelCommisionStatus,
                                'refernceid'                => $refernceid,
                                'depositspecialstatus'      => $depositSpecialStatus,
                                );

                             if($type == 'chart')
                             {
                                $data = $temp;
                             }
                             else if($type == 'dashboard')
                             {
                                $data['data'.$deposit->depositid] = $temp;
                             }
                             else
                             {
                                array_push($data,$temp);
                             }
                        }
                    }
                }
            }
        }

        if($type == 'chart')
        {
            return $data;
        }
        else if($type == 'dashboard')
        {
            return array('result1' => $deposits,'result2' => $data);
        }
        else
        {
            return array('result1' => $deposits,'result2' => $data);
        }
    }


    public static function getPendingCommission($deposits = null,$type = null ,$id = null)
    {
        $sustainability_mode = Setting::getData('sustainability_mode');
        $pendingCommiionArr = array();
        $totalPendingCommission = 0;
        if(count($deposits) > 0)
        {
            foreach ($deposits as $key => $deposit) 
            {
                $depositid              = $deposit->depositid;
                $planid                 = $deposit->planid;
                $created_by             = $deposit->created_by;
                $interestPer            = $deposit->profit;
                $nature_of_plan         = $deposit->nature_of_plan;
                $intPercent             = $deposit->profit;
                $planEndorNot           = $deposit->plan_status;
                $planPeriod             = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                $planDuration           = intval($deposit->duration); //001
                $loanRepaymentPeriod    = $deposit->interest_period_type;
                $depositeCreatedDate    = $deposit->created_at;
                $depositeAmount         = $deposit->amount;
                $startDate              = "";
                $amount                 = "";
                $date                   = "";

                $redeposit  = ReDeposit::where('depositid',$deposit->depositid)
                ->select('amount','created_at')
                ->orderby('created_at','desc')
                ->first();

                if(count($redeposit) > 0)
                {
                    $depositeCreatedDate    = $redeposit->created_at;
                    $depositeAmount         = $redeposit->amount;

                    $dpdt                   = clone $depositeCreatedDate;
                    $date                   = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                }
                else
                {
                    $dpdt            = clone $depositeCreatedDate;
                    $date            = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                }

                //***************************************************//
                if($date != "")
                {
                    if(strtotime(Carbon::now()) <= strtotime($date))
                    {
                        $interestAmt     = $depositeAmount * $intPercent  / 100;
                        Referral::$mixArrayUpline = array();
                        $referralsUps = Referral::getReferralUpline($created_by,1);
                        if(count($referralsUps) > 0)
                        {
                            $planLevels = PlanLevel::where('planid',$planid)->select('commision','level')->get();

                            $plnlev = [];
                            foreach ($planLevels as $planLevel)
                            {
                                $plnlev[$planLevel->level] = $planLevel->commision;
                            }

                            if(count($plnlev) > 0)
                            {
                                $parentUpline_id            = 0;
                                $parentUpline_name          = "";
                                $parentUpline_username      = "";
                                foreach ($referralsUps as $key => $value) 
                                {
                                    if (array_key_exists($value['level'], $plnlev))
                                    {
                                        $totalcom = 0;
                                        $totalcom = LevelCommision::countCommission($sustainability_mode,$interestAmt,$depositeAmount,$plnlev[$value['level']]);
                                        $comAmount = $depositeAmount;
                                        if($sustainability_mode == 1)
                                        {
                                            $comAmount = $interestAmt;
                                        }

                                        if($value['level'] == 1)
                                        {

                                            $parentUpline_name      = $value['first_name'].' '.$value['last_name'];
                                            $parentUpline_username  = $value['username'];
                                            $parentUpline_id        = $value['userid'];
                                        }

                                        $fetch = 0;
                                        if($type == 'user')
                                        {
                                            if($id == $value['userid'])
                                            {
                                                $fetch = 1;
                                            }
                                        }
                                        else
                                        {
                                            $fetch = 1;
                                        }

                                        if($fetch == 1)
                                        {
                                            $temp = array(
                                                'startDate'                 => $depositeCreatedDate,
                                                'endDate'                   => $date,
                                                'parentUpline_name'         => $parentUpline_name,
                                                'parentUpline_username'     => $parentUpline_username,
                                                'parentUpline_id'           => $parentUpline_id,
                                                'upline_name'               => $value['first_name'].' '.$value['last_name'],
                                                'upline_username'           => $value['username'],
                                                'upline_id'                 => $value['userid'],
                                                'name'                      => $deposit->first_name.' '.$deposit->last_name,
                                                'username'                  => $deposit->username,
                                                'id'                        => $deposit->id,
                                                'level'                     => $value['level'],
                                                'com_amount'                => $comAmount,
                                                'amount'                    => $depositeAmount,
                                                'interest'                  => $interestAmt,
                                                'commission'                => $totalcom,
                                                'commission_rate'           => $plnlev[$value['level']],
                                                'deposit_type'              => 'redeposit',
                                                'depositid'                 => $deposit->depositid,
                                                );
                                            
                                            $totalPendingCommission = $totalcom + $totalPendingCommission;
                                            array_push($pendingCommiionArr, $temp);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                //***************************************************//
            }
        }
        $data = array('list' => $pendingCommiionArr,'pendingCommission' => $totalPendingCommission );
        return $data;
    }

    public static function getPendingInterest($deposits = null,$natureofplanId = null)
    {
        if(count($deposits) > 0)
        {
            $pendingInterestArr = array();
            $totalPendingInterest = 0;
            foreach ($deposits as $key => $deposit) 
            {
                $depositid              = $deposit->depositid;
                $planid                 = $deposit->planid;
                $created_by             = $deposit->created_by;
                $interestPer            = $deposit->profit;
                $nature_of_plan         = $deposit->nature_of_plan;
                $intPercent             = $deposit->profit;
                $planEndorNot           = $deposit->plan_status;
                $planPeriod             = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                $planDuration           = intval($deposit->duration); //001
                $loanRepaymentPeriod    = $deposit->interest_period_type;
                $depositeCreatedDate    = $deposit->created_at;
                $depositeAmount         = $deposit->amount;
                $startDate              = "";
                $amount                 = "";
                $date                   = "";

                $redeposit  = ReDeposit::where('depositid',$deposit->depositid)
                ->select('amount','created_at')
                ->orderby('created_at','desc')
                ->first();

                if(count($redeposit) > 0)
                {
                    $depositeCreatedDate    = $redeposit->created_at;
                    $depositeAmount         = $redeposit->amount;

                    $dpdt                   = clone $depositeCreatedDate;
                    $date                   = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                }
                else
                {
                    $dpdt            = clone $depositeCreatedDate;
                    $date            = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                }

                //***************************************************//
                if($date != "")
                {
                    $fetch = 0;
                    if(strtotime(Carbon::now()) <= strtotime($date))
                    {
                        $fetch = 1;
                    }

                        if($fetch  == 1)
                        {
                            $interestAmt     = $depositeAmount * $intPercent  / 100;
                            $temp = array(
                                    'startDate'     => $depositeCreatedDate,
                                    'endDate'       => $date,
                                    'name'          => $deposit->first_name.' '.$deposit->last_name,
                                    'username'      => $deposit->username,
                                    'id'            => $deposit->id,
                                    'amount'        => $depositeAmount,
                                    'interest'      => $interestAmt,
                                    'depositid'     => $deposit->depositid,
                                    'plan_name'     => $deposit->plan_name,
                                    );

                            if($natureofplanId != "")
                            {
                                if($natureofplanId == $nature_of_plan)
                                {
                                    $totalPendingInterest = $interestAmt + $totalPendingInterest;
                                    array_push($pendingInterestArr, $temp);
                                }
                            }
                            else
                            {
                                $totalPendingInterest = $interestAmt + $totalPendingInterest;
                                array_push($pendingInterestArr, $temp);
                            }
                        }
                }
                //***************************************************//
            }
        }
        $data = array('list' => $pendingInterestArr,'pendingInterest' => $totalPendingInterest );
        return $data;
    }

    public static function payoutReport($customeData = null)
    {
        $pendingInterestArr = array();
        $totalPendingInterest = 0;
        $deposits = Deposit::getActiveDepositNatureOfPlanWise();
        if(count($deposits) > 0)
        {
            foreach ($deposits as $key => $deposit) 
            {
                if($deposit->dpstatus ==  'approved')
                {
                    $depositid              = $deposit->depositid;
                    $planid                 = $deposit->planid;
                    $created_by             = $deposit->created_by;
                    $interestPer            = $deposit->profit;
                    $nature_of_plan         = $deposit->nature_of_plan;
                    $intPercent             = $deposit->profit;
                    $planEndorNot           = $deposit->plan_status;
                    $planPeriod             = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                    $planDuration           = intval($deposit->duration); //001
                    $loanRepaymentPeriod    = $deposit->interest_period_type;
                    $depositeCreatedDate    = $deposit->created_at;
                    $depositeAmount         = $deposit->amount;
                    $initialAmount          = $deposit->amount;
                    $startDate              = "";
                    $amount                 = "";
                    $date                   = "";

                    $redeposit  = ReDeposit::where('depositid',$deposit->depositid)
                    ->select('amount','created_at')
                    ->orderby('created_at','desc')
                    ->first();

                    if(count($redeposit) > 0)
                    {
                        $depositeCreatedDate    = $redeposit->created_at;
                        $depositeAmount         = $redeposit->amount;

                        $dpdt                   = clone $depositeCreatedDate;
                        $date                   = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                    }
                    else
                    {
                        $dpdt            = clone $depositeCreatedDate;
                        $date            = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);
                    }

                    //***************************************************//
                    if($date != "")
                    {
                        $fetch = 0;
                        if(strtotime(Carbon::now()->toDateString()) <= strtotime($date->toDateString()))
                        {
                            $fetch = 1;
                        }

                        if($planEndorNot == 1)
                        {
                            $fetch = 0;
                            $dpdate             = clone $depositeCreatedDate;
                            $planEndDate        = myCustome::getAccDate($deposit->created_at,$planPeriod,$planDuration);

                           if(strtotime($date->toDateString()) <= strtotime($planEndDate->toDateString()))
                            {
                                $fetch = 1;
                            }
                        }

                            if($fetch == 1)
                            {
                                $interestAmt     = $depositeAmount * $intPercent  / 100;

                                $fetch2 = 1;
                                if($nature_of_plan == 4)
                                {
                                    $tempdt =  clone $deposit->created_at;
                                    $date   = myCustome::getAccDate($tempdt,$planPeriod,$planDuration);

                                    $minusOneMonth   = clone $date;
                                    $minusOneMonth   = myCustome::getAccDate($minusOneMonth,4,-1);

                                    $fetch2 = 0;
                                    if(strtotime(Carbon::now()->toDateString()) >= strtotime($minusOneMonth->toDateString()))
                                    {
                                        $fetch2 = 1;
                                    }
                                }

                                $fetch3 = 1;
                                if($customeData)
                                {
                                    $filterStartdt      = $customeData['startdt'];
                                    $filterEnddt        = $customeData['enddt'];
                                    $filternatureofplan = $customeData['natureofplan'];

                                    $fetch3 = 0;
                                    if((strtotime($date->toDateString()) >= strtotime($filterStartdt->toDateString())) && (strtotime($date->toDateString()) <= strtotime($filterEnddt->toDateString())))
                                    {
                                        $fetch3 = 1;
                                    }
                                }

                                if($fetch2 == 1 && $nature_of_plan != 3 && $fetch3 == 1)
                                {
                                    $temp = array(
                                    'startDate'         => $depositeCreatedDate,
                                    'endDate'           => $date,
                                    'name'              => $deposit->first_name.' '.$deposit->last_name,
                                    'username'          => $deposit->username,
                                    'id'                => $deposit->id,
                                    'initialamt'        => $initialAmount,
                                    'amount'            => $depositeAmount,
                                    'interest'          => $interestAmt,
                                    'depositid'         => $deposit->depositid,
                                    'plan_name'         => $deposit->plan_name,
                                    'nature_of_plan'    => $nature_of_plan,
                                    );

                                    $totalPendingInterest = $interestAmt + $totalPendingInterest;
                                    array_push($pendingInterestArr, $temp);
                                }
                            }
                    }
                    //***************************************************//
                }
            }
        }
        $data = array('list' => $pendingInterestArr,'pendingInterest' => $totalPendingInterest );
        return $data;
    }

    public static function payout_past_Report($customeData = null)
    {
        $deposits = Deposit::getActiveDepositNatureOfPlanWise();
        if(count($deposits) > 0)
        {
            $pendingInterestArr = array();
            $totalPendingInterest = 0;
            foreach ($deposits as $key => $deposit) 
            {
                $depositid              = $deposit->depositid;
                $planid                 = $deposit->planid;
                $created_by             = $deposit->created_by;
                $interestPer            = $deposit->profit;
                $nature_of_plan         = $deposit->nature_of_plan;
                $intPercent             = $deposit->profit;
                $planEndorNot           = $deposit->plan_status;
                $planPeriod             = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                $planDuration           = intval($deposit->duration); //001
                $loanRepaymentPeriod    = $deposit->interest_period_type;
                $depositeCreatedDate    = $deposit->created_at;
                $depositeAmount         = $deposit->amount;
                $initialAmount          = $deposit->amount;
                $startDate              = "";
                $amount                 = "";
                $date                   = "";

                
                $Start_Date = $customeData['startdt'];
                $End_Date = $customeData['enddt'];
                $Today_Date = Carbon::today();
                
                if($nature_of_plan != 4){
                  
                  $interest_payment_details = '';

                    /* if deposit then get intrest_payment details for given depositid */
                    $interest_payment_details = InterestPayment::where('depositid','=',$deposit->depositid)
                        ->where('interest_type','=','deposit')
                        ->whereDate('created_at','>=',$Start_Date->toDateString())
                        ->whereDate('created_at','<=',$End_Date->toDateString())
                        ->orderby('created_at','desc')
                        ->get();
                   if(count($interest_payment_details) > 0 ){
                        
                        $dpdt  = clone $depositeCreatedDate;
                        $date  = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1); 

                        $interestAmt = $depositeAmount * $intPercent  / 100;
                       
                        /* crate array */
                        $temp = array(
                            'startDate'         => $depositeCreatedDate,
                            'endDate'           => $date,
                            'name'              => $deposit->first_name.' '.$deposit->last_name,
                            'username'          => $deposit->username,
                            'id'                => $deposit->id,
                            'amount'            => $depositeAmount,
                            'interest'          => $interestAmt,
                            'depositid'         => $deposit->depositid,
                            'plan_name'         => $deposit->plan_name,
                            'nature_of_plan'    => $nature_of_plan,
                        );
                        $totalPendingInterest = $interestAmt + $totalPendingInterest;
                         array_push($pendingInterestArr, $temp);
                    }
                    /* get all redeposit of given deposit id */

                    $redeposits = ReDeposit::where('depositid',$deposit->depositid)
                                            ->orderby('created_at','desc')
                                            ->get();
                    if(count($redeposits) > 0){
                       
                        foreach ($redeposits as $redeposit) {
                            /* if redeposit found then get intrest_payment details for given redeposit id */
                            $interest_payment_details = InterestPayment::where('depositid','=',$redeposit->redepositid)
                            ->where('interest_type','=','redeposit')
                            ->whereDate('created_at','>=',$Start_Date->toDateString())
                            ->whereDate('created_at','<=',$End_Date->toDateString())
                            ->orderby('created_at','desc')
                            ->get();
                           
                            if(count($interest_payment_details) > 0 ){
                                
                                $depositeCreatedDate = $redeposit->created_at; 
                                $depositeAmount      = $redeposit->amount;
                                
                                $dpdt  = clone $depositeCreatedDate;
                                $date  = myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1) ; 
                               
                                $interestAmt = $depositeAmount * $intPercent  / 100;
                                
                                /* crate array */
                                $temp = array(
                                    'startDate'         => $depositeCreatedDate,
                                    'endDate'           => $date,
                                    'name'              => $deposit->first_name.' '.$deposit->last_name,
                                    'username'          => $deposit->username,
                                    'id'                => $deposit->id,
                                    'initialamt'        => $initialAmount,
                                    'amount'            => $depositeAmount,
                                    'interest'          => $interestAmt,
                                    'depositid'         => $deposit->depositid,
                                    'plan_name'         => $deposit->plan_name,
                                    'nature_of_plan'    => $nature_of_plan,
                                );
                                $totalPendingInterest = $interestAmt + $totalPendingInterest;
                                 array_push($pendingInterestArr, $temp);
                            }
                        }
                    }
                }
            }
        }
        $data = array('list' => $pendingInterestArr,'pendingInterest' => $totalPendingInterest );
        return $data;
    }
}
