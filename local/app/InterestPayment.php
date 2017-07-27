<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use App\Plan;
use App\WalletAmountInOut;
use App\Notifications;
use App\ReDeposit;
use App\DepositCycleCount;
use App\Withdraw;
use Session;

class InterestPayment extends Model
{
	protected $table = 'interest_payment';
	protected $primaryKey = 'int_proid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['planid', 'profit', 'intno','amount', 'pro_amount', 'status','interest_type', 'depositid', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public static function userProfitAmount($data = null)
    {
    	if($data)
    	{
    		$nature_of_plan 		= $data['nature_of_plan'];
    		$planid 				= $data['planid'];
    		$amount 				= $data['amount'];
    		$initialDepositAmt 		= $data['initialDepositAmt'];
    		$intPercent		 		= $data['loanRepaymentPer'];
    		$loanRepaymentPeriod    = $data['loanRepaymentPeriod'];
			$totalInterest 			= 0;
			$interestAmt 			= 0;
			$afterInterestAddAmt 	= 0;
			$intAmt 				= 0;
			$depositid 				= $data['depositid'];
			$planEndDate 			= $data['planEndDate'];
			$planEndorNot 			= $data['planEndorNot'];
			$cycleStartDt 			= $data['cycleStartDt'];
			$cycleEndDt 			= $data['cycleEndDt'];
			$founder               	= $data['founder'];
			$new_founder           	= $data['new_founder'];
			$usrfounder           	= $data['usrfounder'];

			$redepositamount 	= 0;
			$commissionamount 	= 0;


			$rd = ReDeposit::where('depositid',$data['depositid'])
			->where('created_by',$data['user_id'])
			->select('amount','redepositid')
			->orderby('created_at','desc')
			->first();

			$interest_type = 'deposit';
			$depositid_ch = $depositid;
			if(count($rd) > 0)
			{
				$interest_type 	= 'redeposit';
				$depositid_ch 	= $rd->redepositid;
			}

			if($nature_of_plan == 1 || $nature_of_plan == 2)
			{
				//Withdraw All or Withdraw Profit
				if(count($rd) > 0)
				{
					//SECOND TIME INTEREST
					$amount    			 = $rd->amount;
				}

				$interestAmt  			 = $amount * $intPercent / 100;
				$afterInterestAddAmt     = $amount;
				$intAmt 				 = $amount;

				$redepositamount 		 = $amount;
				$commissionamount 		 = $amount;
				
			}
			else if($nature_of_plan == 3 || $nature_of_plan == 4 || $nature_of_plan == 5)
			{
				//Compounding Rollover
				if(count($rd) > 0)
				{
					//SECOND TIME INTEREST
					$amount = $rd->amount;
				}

				$interestAmt  			 = $amount * $intPercent / 100;
				$intAmt 				 = $amount;
				$afterInterestAddAmt     = $amount + $interestAmt;


				$redepositamount 	= $afterInterestAddAmt;
				$commissionamount 	= $amount;

				if($nature_of_plan == 5)
				{
					if($interest_type == 'deposit')
					{
						$interestAmt 		= 0;
						$redepositamount 	= $amount;
					}
				}
			}

			//CHECK THIS PLAN IS OLD OR NEW
			$userinterest 		=  $interestAmt;
			$wealthbotinterest 	=  0;
			$new_sustainability_mode_on_existing_old_plans = Session::get('settings')[0]['new_sustainability_mode_on_existing_old_plans'];
			if($usrfounder == 1)
			{
				if($new_sustainability_mode_on_existing_old_plans == 1)
				{
					$founder_sustainablity 	= Session::get('settings')[0]['founder_sustainablity'];
					if($founder_sustainablity == 1)
					{
						$lender 	= Session::get('settings')[0]['lender'];
						$wealthbot 	= Session::get('settings')[0]['wealthbot'];
						if($interestAmt > 0)
						{
							$userinterest 		=  ( $interestAmt * $lender ) / 100;
							$wealthbotinterest 	=  ( $interestAmt * $wealthbot ) / 100;
						}
					}
				}
			}
			else
			{
				if($new_sustainability_mode_on_existing_old_plans == 1)
				{
					$non_founder_sustainablity 	= Session::get('settings')[0]['non_founder_sustainablity'];
					if($non_founder_sustainablity == 1)
					{
						$non_lender 	= Session::get('settings')[0]['non_lender'];
						$non_wealthbot 	= Session::get('settings')[0]['non_wealthbot'];
						if($interestAmt > 0)
						{
							$userinterest 		=  ( $interestAmt * $non_lender ) / 100;
							$wealthbotinterest 	=  ( $interestAmt * $non_wealthbot ) / 100;
						}
					}
				}
			}



			$insertdate = $data['customeDate'];
			$intno = InterestPayment::createInterestPaymentNo();

	    	$insert = InterestPayment::create([
	                'planid'    	=>  $data['planid'],
					'intno' 		=>	$intno,
					'profit' 		=>	$intPercent,
					'amount' 		=>	$intAmt,
					'pro_amount' 	=>	$userinterest,
					'depositid' 	=>	$depositid_ch,
					'interest_type' =>	$interest_type,
	                'status'   		=>  'approved',
					'created_by' 	=>	$data['user_id'],
					'modified_by' 	=>	$data['user_id'],
					'created_at' 	=>	$insertdate,
					'updated_at' 	=>	$insertdate,
				]);

	    	if($insert)
	    	{
	    		if($nature_of_plan == 2)
	    		{
	    			$walletarray = array(
                    'amount'            => $interestAmt,
                    'depositid'         => $depositid,
                    'deposit_type'      => $interest_type,
                    'redepositid'       => $depositid_ch,
                    'status'            => 'interest',
                    'created_by'        => $data['user_id'],
                    );

            		WalletAmountInOut::InsertinWallet($walletarray);
	    		}

	    		//PLAN CHANGED PROCEDURE FOR PLAN ID 1 AND 2
	    		if($planid == '1' || $planid == '2' || $planid == '15')
	    		{
	    			$cycleData = array(
	    				'depositid' => $depositid,
	    				'userid' 	=> $data['user_id'],
	    				'cycle' 	=> 1
	    				);
	    			DepositCycleCount::insertDepsitCycleCount($cycleData);
	    		}

	    		/* $sendArray  = array(
                        'link_id'  =>  $insert->int_proid,
                        'user_id'  =>  $data['user_id'],
                        'amount'   =>  $interestAmt,
                        'type'     =>  'interest-payment',
                    );

                Notifications::Notify($sendArray); */

                $rData = array(
				'planid' 					=> $data['planid'],
				'user_id' 					=> $data['user_id'],
				'depositid' 				=> $depositid,
				'amount' 					=> $afterInterestAddAmt,
				'loanRepaymentPeriod' 		=> $loanRepaymentPeriod,
				'interest_type' 			=> $interest_type,
				'depositid_ch' 				=> $depositid_ch,
				'interest' 					=> $interestAmt,
				'customeDate' 				=> $insertdate,
				'planEndDate' 				=> $planEndDate,
				'planEndorNot' 				=> $planEndorNot,
				'redepositamount' 			=> $redepositamount,
				'commissionamount' 			=> $commissionamount,
				'cycleStartDt' 				=> $cycleStartDt,
				'cycleEndDt' 				=> $cycleEndDt,
				'nature_of_plan' 			=> $nature_of_plan,
				'initialDepositAmt' 		=> $initialDepositAmt,
				);

	    		return $rData;
	    	}
    	}
    }

    public static function getLastNewInterestPaymentByUser()
    {
        $id =  Auth::user()->id;
        $interestPayments = InterestPayment::where('interest_payment.created_by',$id)
        ->select('interest_payment.*')
        ->latest()
        ->limit(5)
        ->get();
        
        return $interestPayments;
    }

    public static function getLastNewPaymentInterest()
    {
    	$interestPayments = InterestPayment::join('users','users.id','=','interest_payment.created_by')
        ->select('users.first_name','users.id','users.username','interest_payment.created_at','interest_payment.int_proid','interest_payment.pro_amount')
        ->latest()
        ->limit(10)
        ->get();
        return $interestPayments;
    }

    public static function createInterestPaymentNo()
    {
        $int_proid  = InterestPayment::max('int_proid');
        if($int_proid == null || $int_proid == "")
        {
            $int_proid =  1;
        }
        else
        {
            $int_proid =  1 + $int_proid;
        }
        $letter = chr(rand(65,90));
        $number = rand(1,100);
        $orderNoString = 'INTP'.$number.$letter.$int_proid;
        return $orderNoString;
    }

}
