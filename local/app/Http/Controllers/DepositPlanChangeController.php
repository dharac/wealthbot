<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\DepositPlanChange;
use App\myCustome\myCustome;
use Carbon\Carbon;
use App\ReDeposit;
use App\Deposit;
use App\Plan;
use App\EmailNotify;

class DepositPlanChangeController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$depositPlanChanges =   DepositPlanChange::getRequestUserWise();
		return view('admin.pages.deposit-change-plan',compact('depositPlanChanges'));
	}

	public function newRecord()
	{
		$mode  			= 'Add';
		$deposits      	= ReDeposit::getDetails('','',Auth::user()->id,'dashboard');
		return view('users.pages.new-deposit-change-plan',compact('mode','deposits'));
	}

	public function indexAdmin()
	{
		$depositPlanChanges =   DepositPlanChange::getRequest();
		return view('admin.pages.deposit-change-plan',compact('depositPlanChanges'));
	}

	public function getPlanData(Request $request)
	{

		$deposit = Deposit::where('depositid',$request->id)->select('planid')->first();
		if(count($deposit) > 0)
		{
			//$plans = Plan::getActivePlans();
			$completedCycle = 0;
			if($deposit->planid == 1)
            {
            	//CHECK IF COMPUNDING PLAN IS ACTIVE AND CYCLE COMPLETED 3 THAN GIVE HIM PAYOUT PLAN
            	$completedCycle = 1;
            	$rdcycl = ReDeposit::where('depositid',$request->id)->count();
            	if($rdcycl >= 2)
            	{
            		$completedCycle = $completedCycle + 2;
            	}
            }

            $is30DayCompunding = 0;
            if($deposit->planid == 11)
            {
            	$is30DayCompunding = 1;
            }

            $ids = Plan::$activePlan;
            if($completedCycle == 3 || $is30DayCompunding == 1)
            {
            	array_push($ids,3);
            	array_push($ids,2);
            	array_push($ids,15);
            }

            $plans 	= Plan::whereIn('planid',$ids)->where('status','active')->get();
            $html 	= "";
	        foreach($plans as $plan)
	        {
	           if($deposit->planid != $plan->planid)
            	{
            		$html .= '<tr><td align="left">'.$plan->plan_name.'</td><td>$ '.number_format($plan->spend_min_amount,2).'</td><td>$ '.number_format($plan->spend_max_amount,2).'</td><td>'.number_format($plan->profit,2).'%</td><td align="left"><a href="javascript:void(0);" data-id="'.$plan->planid.'"><button class="btn primary btn-sm change-plan-cls" data-pl="'.$plan->planid.'" data-dp="'.$request->id.'"><i class="fa fa-location-arrow"></i> Change Plan</button></a></td></tr>';
            	}
	        }

			$response = array(
					'msg'   => 'success', 
					'data'  => $html
					);

	       	return response()->json($response);
		}
	}

	public function updateDeposit(Request $request)
	{
		$planid 		= $request->pl;
		$depositid 		= $request->dp;
		
		 $deposit = Deposit::join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->where('plan_m.status','active')
        ->where('deposit.status','approved')
        ->where('deposit.depositid',$depositid)
        ->select('plan_m.planid','plan_m.plan_name','plan_m.interest_period_type','plan_m.plan_status','plan_m.duration','plan_m.duration_time','deposit.created_at','deposit.depositid','deposit.created_by','deposit.amount')
        ->first();


		if(count($deposit) > 0)
		{
			$depositeCreatedDate    = $deposit->created_at;
			$loanRepaymentPeriod    = $deposit->interest_period_type;
			$redeposit  = ReDeposit::where('depositid',$depositid)->select('amount','created_at')->orderby('created_at','desc')->first();
            if(count($redeposit) > 0)
            {
            	$depositeCreatedDate    = $redeposit->created_at;
            }

            $dpdt            	= clone $depositeCreatedDate;
            $date 				= myCustome::getAccDate($dpdt,$loanRepaymentPeriod,1);

            $startdt  	= $depositeCreatedDate;
			$enddt  	= $date;


            if($deposit->plan_status == 1)
            {
                $planDuration       = intval($deposit->duration); //001
                $planPeriod         = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                $planEndDate        = myCustome::getAccDate($deposit->created_at,$planPeriod,$planDuration);
                $enddt 				= $planEndDate;
            }

			$startdt1 	= clone $enddt;
			$cutofdt  	= myCustome::getAccDate($startdt1,2,-15);

			$plan = Plan::where('status','active')->select('planid','plan_name')->where('planid',$planid)->first();

			if(count($plan) > 0)
			{
				$dataArray = array(
					'old_planid' 	    => $deposit->planid,
					'new_planid' 		=> $planid,
					'depositid' 		=> $depositid,
					'status' 			=> 'approved',
					'userid'			=> Auth::user()->id,
				);
				$insert = DepositPlanChange::insertPlanChange($dataArray);

				if($insert)
				{
					$lbltext = "";
					$lbltext = '<tr class="dp-message-'.$depositid.'"><td colspan="7" class="text-center text-danger"><i class="material-icons">&#xE003;</i> You have changed the current plan to <b>'.$plan->plan_name.'</b>. You can cancel this change up to 15 days before the end of the current cycle up until <b>'.dispayTimeStamp($cutofdt)->toDayDateTimeString().' '.Config::get('app.timezone_display2').'</b></td></tr>';

	                $content = [
	                'EMAIL'             =>  Auth::user()->email,
	                'EMAIL-ID'          =>  Auth::user()->getresponseid,
	                'USERNAME'          =>  Auth::user()->username,
	                'FIRSTNAME'         =>  Auth::user()->first_name,
	                'LOGINURL'          =>  url('login'),
	                'PLAN_NM_OLD' 		=>  $deposit->plan_name,
	                'PLAN_NM_NEW'		=>  $plan->plan_name,
	                'CUTTOFFDATE'		=>  dispayTimeStamp($cutofdt)->toDayDateTimeString(),
	                'CYCLE_END_DATE'	=>  dispayTimeStamp($enddt)->toDayDateTimeString(),
	                'ADMINMAIL'         =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
	                'SITENAME'          =>  config('services.SITE_DETAILS.SITE_NAME'),
	                'TYPE'              =>  'PLAN-CHANGE',
	                ];

	                EmailNotify::sendEmailNotification($content);

					$response = array(
						'msg'   	=> 'success',
						'dpMessage' => $lbltext,
						'cycleEnd' 	=> dispayTimeStamp($enddt)->toDayDateTimeString(),
						'plan_name' => $deposit->plan_name,
						);

		       		return response()->json($response);
				}
			}

		}
	}

	public function cancelRequest(Request $request)
	{
		$depositid 		= $request->id;
		$planid 		= $request->pl;

		$deposit = Deposit::findOrFail($depositid);
		$plan = Plan::where('status','active')->where('planid',$planid)->first();


		$dataArray = array(
					'old_planid' 	    => $deposit->planid,
					'new_planid' 		=> $planid,
					'depositid' 		=> $depositid,
					'status' 			=> 'cancel',
					'userid'			=> Auth::user()->id,
				);
		$insert = DepositPlanChange::insertPlanChange($dataArray);

		if($insert)
		{
			$response = array('msg' => 'success');
	       	return response()->json($response);
		}

	}
}
