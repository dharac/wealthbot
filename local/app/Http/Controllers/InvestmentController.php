<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Deposit;
use App\Notifications;
use App\Http\Requests;
use App\BitcoinPriceDeposit;
use App\Plan;
use App\myCustome\myCustome;
use Carbon\Carbon;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;

class InvestmentController extends Controller
{
	protected $filterArray;
	protected $filterArray1;

	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(Request $request)
	{
		$dateRange = "";
		$stdt = "";
		$endt = "";
		$perPage = config('services.DATATABLE.PERPAGE');

		if($request->startdt != null && $request->enddt != null)
		{
			$mode 		= 'search';
			$startdt  	= Carbon::parse($request->startdt)->format('m-d-Y');
			$startdt 	= Carbon::createFromFormat('m-d-Y', $startdt);
			$enddt  	= Carbon::parse($request->enddt)->format('m-d-Y');
			$enddt 		= Carbon::createFromFormat('m-d-Y', $enddt);

			$stdt = $request->startdt;
			$endt = $request->enddt;

			$investments = Deposit::join('users', 'deposit.created_by', '=', 'users.id')
			->join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
			->whereDate('deposit.created_at', '>=', $startdt->toDateString())
			->whereDate('deposit.created_at', '<=', $enddt->toDateString())
			->orderby('deposit.created_at','desc')
			->select('users.first_name','users.last_name','users.username','plan_m.plan_name','plan_m.plan_status', 'deposit.*')
			->paginate($perPage);

			$totalAmt = Deposit::whereDate('deposit.created_at', '>=', $startdt->toDateString())->whereDate('deposit.created_at', '<=', $enddt->toDateString())->sum('amount');
			$dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
		}
		else
		{
			$mode 	= 'view';
			$investments = Deposit::orderby('deposit.created_at','desc')->paginate($perPage);
			$totalAmt = Deposit::sum('amount');
		}

		return view('admin.pages.investment', compact('investments','dateRange','mode','stdt','endt','totalAmt','filterArray'));
	}

	public function viewRecord($id = null)
	{
		$investment = Deposit::getSingleRecord($id);
		$mode = 'View';
		Notifications::readNotification('deposit',$id);

		if(count($investment) > 0)
		{
			return view('admin.pages.new-investment', compact('investment','mode'));
		}
		else
		{
			abort(404);
		}
	}

	public function approveLoan(Request $request)
	{
		$id = $request->eid;
		$record = Deposit::findOrFail($id);
		if($record->status == 'pending')
		{
			$btcprice = BitcoinPriceDeposit::where('depositid',$id)
			->where('status','pending')
			->first();

			$bitcoin_in_dollar = 0;			
			$bitcoin_in_dollar = myCustome::bitcoinInDollar($btcprice->bitcoin_currency,$btcprice->currency);

			$bitcoinprice = 0;
			$bitcoinprice = ( $btcprice->amount * 1 ) / $btcprice->bitcoin_in_dollar;

			$actualbtcprice = ($bitcoinprice * $bitcoin_in_dollar) / 1;

			$dpamount = 0;
			if($actualbtcprice <= $btcprice->amount)
			{
				$dpamount = $actualbtcprice;
			}
			else
			{
				$dpamount = $btcprice->amount;
			}

			if(count($btcprice) > 0)
			{
				$plan = Plan::where('planid',$record->planid)->select('duration','duration_time','nature_of_plan','plan_status')->first();

				if(count($plan) > 0)
				{
					$duration               = $plan->duration;
					$duration_time          = $plan->duration_time;
					$nature_of_plan         = $plan->nature_of_plan;
					$plan_status            = $plan->plan_status;

					$today          = Carbon::now();
					$maturity_date  = $today->toDateString();

					if($plan_status == '1')
					{
						$duration       = intval($duration);
						$getDate        = myCustome::getAccDate($today,$duration_time,$duration);
						$maturity_date  = $getDate->toDateString();
					}

					$status = 'approved';

					$update = $record->update([
						'created_at' 		=> Carbon::now(),
						'updated_at' 		=> Carbon::now(),
						'maturity_date' 	=> $maturity_date,
						'status' 			=> $status,
						'amount' 			=> $dpamount,
						'modified_by'		=> Auth::user()->id,	
						]);

					if($update)
					{
						$btcInsertArr  = array(
							'depositid'     	=>  $id,
							'status'        	=>  $status,
							'amount'        	=>  $dpamount,
							'bitcoin_in_dollar' =>  $bitcoin_in_dollar,
							'created_by'    	=>  $record->created_by,
							'bitcoin_currency'  =>  $btcprice->bitcoin_currency,
                           	'currency'          =>  $btcprice->currency,
							);

						$btcInsert = BitcoinPriceDeposit::InsertSingleRecord($btcInsertArr);

						$BW_MESSAGE = $record->depositno;
						Session::flash('message', 'Success! Deposit Id '.$BW_MESSAGE.' has been approved.');
						Session::flash('alert-class', 'alert-success');
						return Redirect::to('admin/loan/view/'.$id.'');
					}
				}
			}
		}
		return Redirect::to('admin/loan');
	}
}
