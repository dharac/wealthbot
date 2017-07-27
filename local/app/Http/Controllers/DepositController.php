<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Plan;
use App\Coinpayment;
use App\User;
use App\Referral;
use App\Deposit;
use App\Country;
use App\SecurityQuestion;
use App\myCustome\myCustome;
use App\EmailNotify;
use App\Notifications;
use App\WalletAmountInOut;
use App\DepositAnotherUser;
use App\PaymentPeriod;
use Illuminate\Support\Facades\Crypt;
use Validator;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;


class DepositController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$user = Auth::user();
		$perPage = config('services.DATATABLE.PERPAGE');
        $investments = Deposit::where('deposit.created_by',$user->id)->latest()->paginate($perPage);
		$totalAmt = Deposit::where('deposit.created_by',$user->id)->sum('amount');

		return view('users.pages.deposite', compact('investments','response','totalAmt'));
	}

	public function viewRecord($id)
	{
		$mode = 'View';
		$user = Auth::user();
		$investment = Deposit::join('users', 'deposit.created_by', '=', 'users.id')
    	->join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
    	->where('deposit.created_by',$user->id)
    	->where('deposit.depositid',$id)
        ->select('users.first_name','users.last_name','users.username','plan_m.plan_name','plan_m.plan_status','deposit.*')
        ->first();
        Notifications::readNotification('deposit_user',$id);
        if(count($investment) > 0)
        {
        	return view('users.pages.new-deposite', compact('mode','investment'));
        }
        else
        {
        	abort(404);
        }
	}

	public function fromWallet()
	{
		return $this->newRecord('wallet');
	}

	public function fromWalletAnotherUser()
	{
		return $this->newRecord('user');
	}

	public function fromCoinpayment()
	{
		return $this->newRecord('coinpayment');
	}

	public function newRecord($type = null)
	{
		$wallet 	= 0;
		$users 		= 0;
		$id 		= Auth::user()->id;
		if($type == 'user' || $type == 'wallet')
		{
			$wallet 	= WalletAmountInOut::getWallet($id);
			if($type == 'user')
			{
				$users 	= Referral::getReferralDownlineList($id,1,10);
			}
		}
		
		$paymentPeriods  = PaymentPeriod::pluck('period_sing','pay_period_id')->all();
		$mode 		= 'Add';
		$plans 		 = Plan::whereIn('planid',Plan::$activePlan)->where('status','active')->get();
		$type 	 	 = $type;
		return view('users.pages.new-deposite', compact('mode','plans','wallet','type','users','paymentPeriods'));
	}

	public function storeDeposite(Request $request)
    {
		if($request->type == 'coinpayment' || $request->type == 'wallet' || $request->type == 'user')
		{
	        if($request->type == 'user')
	        {
	        	$validator = $this->validate($request, [
	        	'user'                 	 => 'required|numeric',
            	'rdplan'                 => 'required',
            	'amount'                 => 'required|numeric',
		        ],[
		        	'user.required'  		  => '* The User field is required.',
		            'rdplan.required'         => '* Please select one plan.',
		            'amount.required'  		  => '* The Amount field is required.',
		            'amount.numeric'   		  => '* The Amount field is numeric.',
		        ]);
	        }
	        else
	        {
	        	$validator = $this->validate($request, [
            	'rdplan'                 => 'required',
            	'amount'                 => 'required|numeric',
		        ],[
		            'rdplan.required'         => '* Please select one plan.',
		            'amount.required'  		  => '* The Amount field is required.',
		            'amount.numeric'   		  => '* The Amount field is numeric.',
		        ]);
	        }

	        $rdplan = $request->rdplan;
	        $plan 	= Plan::findOrFail($rdplan);

	        $validator = Validator::make($request->all(), [
	            'amount'           => 'required|numeric|max:'.$plan->spend_max_amount.'|min:'.$plan->spend_min_amount.''
	        ],[
	            'amount.required'  => '* The Amount field is required.',
	            'amount.numeric'   => '* The Amount field is numeric.',
	            'amount.min'       => '* The Amount value between $ '.number_format($plan->spend_min_amount,2).' to  $ '.number_format($plan->spend_max_amount,2).' .',
	            'amount.max'       => '* The Amount value between $ '.number_format($plan->spend_min_amount,2).' to  $ '.number_format($plan->spend_max_amount,2).' .',
	        ]);

	        if(in_array($rdplan,Plan::$activePlan)) { }
	        else
	        {
	        	$validator->after(function ($validator) {
                    $validator->errors()->add('rdplan', 'You have select invalid plan please check.');
                });
	        }

	        if($validator->fails())
            {
            	if($request->type == 'user')
            	{	
            		return redirect('user/deposit/new/wallet/another')->withErrors($validator)->withInput($request->all());
            	}
            	else if($request->type == 'wallet')
            	{	
            		return redirect('user/deposit/new/wallet')->withErrors($validator)->withInput($request->all());
            	}
            	return redirect('user/deposit/new/wallet')->withErrors($validator)->withInput($request->all());
            }

	        $amount  = $request->amount;

	        if($request->type == 'coinpayment')
	        {
	        	//STORE DATA IN SESSION AFTER VALIDATE..
	        	myCustome::setSeesionPlan($rdplan,$amount);

		    	$content = [
		            'EMAIL'             =>  Auth::user()->email,
		            'EMAIL-ID'          =>  Auth::user()->getresponseid,
		            'USERNAME'          =>  Auth::user()->username,
		            'FIRSTNAME'         =>  Auth::user()->first_name,
		            'LOGINURL'          =>  url('login'),
		            'ADMINMAIL'         =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
		            'SITENAME'          =>  config('services.SITE_DETAILS.SITE_NAME'),
		            'TYPE'              =>  'BITCOIN-HELP',
		            ];

		       	EmailNotify::sendEmailNotification($content);
		        return Redirect::to('/coinpayment');
	        }
	        else
	        {
	        	$userid =  Auth::user()->id;
	        	$wallet     = WalletAmountInOut::getWallet($userid);
	            $total_wallet_amount = $wallet['wallet_total'];
	            $total = number_format($total_wallet_amount,2);
	            $total = str_replace(",", "", $total);
	            if($total >= $amount)
	            {
	            	if(($amount >= $plan->spend_min_amount) && ($amount <= $plan->spend_max_amount))
	            	{
	            		$insertUser = $userid;
	            		$status = 'redeposit';
	            		if($request->type == 'user')
	            		{
	            			$insertUser = $request->user;
	            			$status = 'redeposit_another_user';
	            		}

	            		$randomString   = myCustome::randomString();

	            		$dataArray = array(
                        	'user_id' 			=> $insertUser,
                        	'planid' 			=> $rdplan,
                        	'payment_through' 	=> 'wallet',
                        	'description'       =>  '',
                        	'currency' 			=> 'USD',
                        	'amount' 			=> $amount,
                        	'status' 			=> 'approved',
                        	'transaction_id'    => 'DP-'.$randomString,
                        	'depositdt' 		=> "",
                        	);

                    	$insertDeposit = Deposit::insertDeposite($dataArray);

                    	if($insertDeposit)
                    	{
                    		$walletarray = array(
		                    'amount'            => $amount,
		                    'depositid'         => $insertDeposit->depositid,
		                    'deposit_type'      => '',
		                    'redepositid'       => 0,
		                    'status'            => $status,
		                    'created_by'        => $userid,
		                    );

                    		$insert = WalletAmountInOut::InsertinWallet($walletarray);

                    		if($request->type == 'user')
                    		{
                    			$anotherArray = array(
			                    'depositfor'            => $insertUser,
			                    'depositid'         	=> $insertDeposit->depositid,
			                    'amount'            	=> $amount,
			                    'created_by'       		=> $userid,
			                    );

                    			$insert = DepositAnotherUser::InsertinAnotherUser($anotherArray);

                    			Session::flash('message', 'Success! Deposit created successfully.');
								Session::flash('alert-class', 'alert-success');
								return Redirect::to('wallet');
                    		}

                    		Session::flash('message', 'Success! Deposit created successfully.');
							Session::flash('alert-class', 'alert-success');
                    	}
                    	else
                    	{
                    		Session::flash('message', 'Error! Something went wrong.');
							Session::flash('alert-class', 'alert-danger');
                    	}
                    	return Redirect::to('user/deposit');
	            	}
	            }
	            else
	            {
	            	$validator->after(function ($validator) {
                        $validator->errors()->add('amount', 'Your deposit amount should be equal to or less than your wallet balance.');
                    });

                    if($validator->fails())
		            {
		            	if($request->type == 'user')
		            	{
		            		return redirect('user/deposit/new/wallet/another')->withErrors($validator)->withInput($request->all());	
		            	}
		            	return redirect('user/deposit/new/wallet')->withErrors($validator)->withInput($request->all());	
		            }
	            }
	        }
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
			return Redirect::to('dashboard');
		}
    }

	public function autoCoinpayment()
	{
		$userid = Auth::user()->id;
		$amount = session('amount');
		$id 	= session('plan');
		
		if($id != "" || $amount != "")
		{
			$user = User::where('id',$userid)
			->join('country_m', 'country_m.coucod', '=', 'users.coucod')
			->select('country_m.counm','users.*')
			->first();
			$credential = Coinpayment::where('status','active')->select('merchant_id')->first();
			$plan = Plan::findOrFail($id);
			return view('public.pages.deposite_amount',compact('plan','credential','amount','userid','user'));
		}
		else
		{
			return Redirect::to('dashboard');
		}
	}
}
