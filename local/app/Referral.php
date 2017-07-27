<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Users;

class Referral extends Model
{
	protected $table 				= 'referral';
	protected $primaryKey 			= 'refcod';
	public static $mixArray 		= array();
	public static $mixArrayUpline 	= array();
	public static $downlineData 	= [];
	public static $cmbUser 			= "";
	public static $levelStop 		= 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['userid', 'refid', 'created_by', 'modified_by', 'created_at', 'updated_at'];

	public static function getReferralDownlineData($refid = null, $levelStatus = null,$levelStop = null)
	{
		$referrals = Referral::join('users', 'referral.userid', '=', 'users.id')
		->join('users as user_ref', 'referral.refid', '=', 'user_ref.id')
		->where('referral.refid',$refid)
		->select('users.first_name','users.id','users.last_name','users.username','users.email','referral.*','user_ref.first_name as referrer_first_name','user_ref.last_name as referrer_last_name','user_ref.id as referrer_id','user_ref.username as referrer_username')
		->get();

		if(count($referrals) > 0)
		{
			foreach ($referrals as $referral)
			{
				if($levelStatus > $levelStop)
				{
					break;
				}

				Referral::$downlineData[$referral->id] = [
					'level' 				=> $levelStatus,
					'first_name' 			=> $referral->first_name,
					'last_name' 			=> $referral->last_name,
					'username' 				=> $referral->username,
					'id' 					=> $referral->id,
					'email' 				=> $referral->email,
					'referrer_first_name' 	=> $referral->referrer_first_name,
					'referrer_last_name' 	=> $referral->referrer_last_name,
					'referrer_username' 	=> $referral->referrer_username,
					'referrer_id' 			=> $referral->referrer_id,
					];
				Referral::getReferralDownlineData($referral->userid , $levelStatus+1,$levelStop);
			}
		}
		return Referral::$downlineData;
	}

	public static function getReferralDownlineDataOnlyId($refid = null, $levelStatus = null,$levelStop = null)
	{
		$referrals = Referral::where('referral.refid',$refid)
		->select('referral.userid')
		->get();

		if(count($referrals) > 0)
		{
			foreach ($referrals as $referral)
			{
				if($levelStatus > $levelStop)
				{
					break;
				}

				Referral::$downlineData[$referral->userid] = ['userid' => $referral->userid];

				Referral::getReferralDownlineDataOnlyId($referral->userid , $levelStatus+1,$levelStop);
			}
		}
		return Referral::$downlineData;
	}

	public static function getReferralDownlineList($refid = null, $levelStatus = null,$levelStop = null,$temp = null)
	{
		if($temp == null || $temp == "")
		{
			$temp = $refid;
		}

		$referrals = Referral::join('users', 'referral.userid', '=', 'users.id')
		->where('referral.refid',$refid)
		->select('referral.userid','users.first_name','users.last_name','users.username')
		->get();

		if(count($referrals) > 0)
		{
			foreach ($referrals as $referral)
			{
				if($levelStatus > $levelStop)
				{
					break;
				}

				$name = $referral->username;
				if($temp == $refid)
				{
					$name = $referral->first_name.' '.$referral->last_name.' | '.$referral->username;
				}

				Referral::$downlineData[$referral->userid] = $name;

				Referral::getReferralDownlineList($referral->userid , $levelStatus+1,$levelStop,$temp);
			}
		}
		return Referral::$downlineData;
	}

    public static function getReferralDownline($refid = null, $levelStatus = null,$levelStop = null,$report = null)
    {
		$referrals = Referral::join('users', 'referral.userid', '=', 'users.id')
		->where('referral.refid',$refid)
		->select('users.first_name','users.id','users.last_name','users.username','users.email','users.status','users.phone','referral.refcod','referral.userid','referral.refid')
		->get();

		if(count($referrals) > 0)
		{
			foreach ($referrals as $referral)
			{
				$loanAmount 			= 0;
				$levelPercent 			= "";
				$referrer 				= "";
				$referrerunm 			= "";
				$plan_name 				= "";
				$status 				= "";
				$depositAmountArr 		= array();
				$planNameArr 			= array();
				$commissionAmountArr 	= array();
				$totalCommision 		= 0;

				//IT WILL USED WHEN REFERRAL REPORT GENERAT IN ADMIN PANEL OR USER PANEL
				if($report == 'geneology')
				{
					$deposits = Deposit::join('plan_m', 'deposit.planid', '=', 'plan_m.planid')
					->where('deposit.created_by',$referral->id)
					->select('deposit.planid','deposit.amount','deposit.depositid','plan_m.plan_name','plan_m.nature_of_plan')
					->get();

					if(count($deposits) > 0)
					{
						$status 		= 'active';
						foreach($deposits as $deposit)
						{
							$redeposits = ReDeposit::where('created_by',$referral->id)->where('depositid',$deposit->depositid)->select('amount')->orderby('redepositid','desc')->first();
							if(count($redeposits) > 0)
							{
								$loanAmount = $redeposits->amount;
								array_push($depositAmountArr, $redeposits->amount);
							}
							else
							{
								$loanAmount = $loanAmount + $deposit->amount;
								array_push($depositAmountArr, $deposit->amount);
							}
						}
					}
								
				}
				else if($report == 'report')
				{
					$deposits = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
					->where('plan_m.status','active')
					->where('deposit.created_by',$referral->id)
					->orderby('deposit.depositid','desc')
					->select('plan_m.planid','plan_m.interest_period_type','plan_m.duration_time','plan_m.duration','plan_m.plan_status','plan_m.nature_of_plan','plan_m.plan_name','plan_m.profit','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount')
					->get();

					if(count($deposits) > 0)
					{
						$status = 'active';
						$totalCommision = 0;
						$reDepositAmt = 0;
						foreach($deposits as $deposit)
						{

							$depositid              = $deposit->depositid;
			                $nature_of_plan         = $deposit->nature_of_plan;
			                $depositeCreatedDate    = $deposit->created_at;
			                $depositeCreatedBy      = $deposit->created_by;
			                $depositeAmount         = $deposit->amount;
			                $planEndorNot           = $deposit->plan_status;
			                $planDuration           = intval($deposit->duration);
			                $planPeriod             = $deposit->duration_time;
			                $loanRepaymentPer       = $deposit->profit;
			                $loanRepaymentPeriod    = $deposit->interest_period_type;

			                array_push($planNameArr, $deposit->plan_name);


							$redeposites  = ReDeposit::where('depositid',$depositid)
		                    ->select('amount','created_at','redepositid')
		                    ->orderby('created_at','asc')
		                    ->get();


		                    //when deposit amount
							$lc = LevelCommision::where('created_by',Referral::$cmbUser)
							->where('downlineid',$referral->userid)
							->where('depositid',$depositid)
							->where('commission_type','deposit')
							->sum('commission');

							$totalCommision = $lc + $totalCommision;
		                    
		                    //after redeposit amount
		                    $reDepositAmt = 0;
		                    foreach ($redeposites as $rd)
		                    {
		                    	$lc = LevelCommision::where('created_by',Referral::$cmbUser)
								->where('downlineid',$referral->userid)
								->where('depositid',$rd->redepositid)
								->where('commission_type','redeposit')
								->sum('commission');

								$totalCommision = $lc + $totalCommision;

								$reDepositAmt = $rd->amount;
		                    }

		                    if($reDepositAmt > 0)
							{
								array_push($depositAmountArr, $reDepositAmt);
							}
							else
							{
								array_push($depositAmountArr, $deposit->amount);
							}


							//Level Commision Count
							$plnLevels = PlanLevel::where('planid',$deposit->planid)->where('level',$levelStatus)->select('commision')->first();
							if(count($plnLevels) > 0)
							{
								if($levelPercent == "")
								{
									$levelPercent = number_format($plnLevels->commision,2).'% ';
								}
								else
								{
									$levelPercent .= ', '.number_format($plnLevels->commision,2).'% ';
								}
							}
						}
					}
					else
					{
						$status = 'inactive';
					}

					$refs = User::where('id',$referral->refid)->select('first_name','last_name','username')->first();
					if(count($refs) > 0)
					{
						$referrer = ucfirst($refs->first_name).' '.ucfirst($refs->last_name);
						$referrerunm = $refs->username;
					}
				}
				else if($report == 'pending_commission')
				{
					$deposits = Deposit::where('deposit.created_by',$referral->id)
					->where('deposit.created_by',$referral->id)
					->count();

					$status = 'inactive';
					if($deposits > 0)
					{
						$status = '';
					}
				}

				$tmpArr = array(
					'level' 			=> $levelStatus,
					'refcod' 			=> $referral->refcod,
					'uplineid' 			=> $referral->refid,
					'userid' 			=> $referral->id,
					'first_name'		=> $referral->first_name,
					'last_name'	 		=> $referral->last_name,
					'username' 			=> $referral->username,
					'email' 			=> $referral->email,
					'phone' 			=> $referral->phone,
					'status' 			=> $status,
					'loanAmount' 		=> $loanAmount,
					'levelPercent' 		=> $levelPercent,
					'amtlist' 			=> $depositAmountArr,
					'commlist' 			=> $commissionAmountArr,
					'totalCommision'	=> $totalCommision,
					'referrer' 			=> $referrer,
					'referrerunm' 		=> $referrerunm,
					'plannamelist'  	=> $planNameArr,
					'created_at' 		=> $referral->created_at,
					'updated_at' 		=> $referral->updated_at,
					);

				if($levelStatus > $levelStop)
				{
					break;
				}

				if($report == 'pending_commission')
				{
					if($status == '')
					{
						array_push(Referral::$mixArray,$tmpArr);
					}
				}
				else
				{
					array_push(Referral::$mixArray,$tmpArr);
				}

				Referral::getReferralDownline($referral->userid , $levelStatus+1,$levelStop,$report);
			}
		}
		return Referral::$mixArray;
    }


    public static function getReferralDownlineLevel($refid = null,$userid = null, $levelStatus = null,$levelStop = null)
    {
    	static $lid = 0;

		$referrals = Referral::join('users', 'referral.userid', '=', 'users.id')
		->where('referral.refid',$refid)
		->select('users.id','referral.*')
		->get();

		if(count($referrals) > 0)
		{
			foreach ($referrals as $referral)
			{
				if($userid === $referral->id)
				{
					$lid = $levelStatus;
					break;
				}

				if($levelStatus > $levelStop)
				{
					break;
				}

				Referral::getReferralDownlineLevel($referral->userid,$userid, $levelStatus+1,$levelStop);
			}
		}

		return $lid;
    }


    public static function getReferralUpline($uplineid = null,$levelStatus = null)
    {
		$referrals = Referral::where('referral.userid',$uplineid)->select('refcod','refid')->get();
		if(count($referrals) > 0)
		{
			foreach ($referrals as $referral)
			{
				$user = User::where('id',$referral->refid)
				->where('status','active')
				->select('id','first_name','last_name','username','email','status','created_at','updated_at')
				->first();
				if(count($user) > 0)
				{
					$tmpArr = array(
					'level' 		=> $levelStatus,
					'refcod' 		=> $referral->refcod,
					'userid' 		=> $user->id,
					'first_name'	=> $user->first_name,
					'last_name'	 	=> $user->last_name,
					'username' 		=> $user->username,
					'email' 		=> $user->email,
					'status' 		=> $user->status,
					'created_at' 	=> $user->created_at,
					'updated_at' 	=> $user->updated_at,
					);

					if($levelStatus > Referral::$levelStop)
					{
						break;
					}

					array_push(Referral::$mixArrayUpline,$tmpArr);
					Referral::getReferralUpline($referral->refid,$levelStatus+1);
				}
			}
		}
		return Referral::$mixArrayUpline;
    }
}
