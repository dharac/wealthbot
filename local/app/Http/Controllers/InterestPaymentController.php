<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Redirect;
use Auth;
use Session;
use Carbon\Carbon;
use App\InterestPayment;
use App\Notifications;
use App\Deposit;
use App\ReDeposit;
use App\myCustome\myCustome;

class InterestPaymentController extends Controller
{
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

			$interestPayments = InterestPayment::join('users','users.id','=','interest_payment.created_by')
	        ->join('plan_m','plan_m.planid','=','interest_payment.planid')
	        ->whereDate('interest_payment.created_at', '>=', $startdt->toDateString())
	        ->whereDate('interest_payment.created_at', '<=', $enddt->toDateString())
	        ->orderby('interest_payment.created_at','asc')
	        ->select('plan_m.plan_name','users.id','users.first_name','users.last_name','users.username','interest_payment.*')
	        ->latest()
	        ->paginate($perPage);

	        $totalAmt = InterestPayment::whereDate('interest_payment.created_at', '>=', $startdt->toDateString())->whereDate('interest_payment.created_at', '<=', $enddt->toDateString())->sum('pro_amount');

	        $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
		}
		else
		{
			$mode 	= 'view';
	        $interestPayments = InterestPayment::join('users','users.id','=','interest_payment.created_by')
	        ->join('plan_m','plan_m.planid','=','interest_payment.planid')
	        ->select('plan_m.plan_name','users.id','users.first_name','users.last_name','users.username','interest_payment.*')
	        ->latest()
	        ->paginate($perPage);

	        $totalAmt = InterestPayment::sum('pro_amount');
		}
		return view('admin.pages.interest-payment', compact('interestPayments','dateRange','mode','stdt','endt','totalAmt'));
	}


	public function indexUser(Request $request)
	{
		$dateRange = "";
		$stdt = "";
		$endt = "";
		$perPage = config('services.DATATABLE.PERPAGE');
		$user    = Auth::user();
		if($request->startdt != null && $request->enddt != null)
		{
			$mode 		= 'search';
			$startdt  	= Carbon::parse($request->startdt)->format('m-d-Y');
			$startdt 	= Carbon::createFromFormat('m-d-Y', $startdt);
			$enddt  	= Carbon::parse($request->enddt)->format('m-d-Y');
			$enddt 		= Carbon::createFromFormat('m-d-Y', $enddt);

			$stdt = $request->startdt;
			$endt = $request->enddt;

			$interestPayments = InterestPayment::join('plan_m','plan_m.planid','=','interest_payment.planid')
			->whereDate('interest_payment.created_at', '>=', $startdt->toDateString())
	        ->whereDate('interest_payment.created_at', '<=', $enddt->toDateString())
			->where('interest_payment.created_by',$user->id)
			->orderby('interest_payment.created_at','asc')
			->select('plan_m.plan_name','interest_payment.*')
			->paginate($perPage);

			$totalAmt = InterestPayment::whereDate('interest_payment.created_at', '>=', $startdt->toDateString())
	        ->whereDate('interest_payment.created_at', '<=', $enddt->toDateString())
			->where('created_by',$user->id)
			->latest()
			->sum('pro_amount');

	        $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
		}
		else
		{
			$mode 	= 'view';
			$interestPayments = InterestPayment::join('plan_m','plan_m.planid','=','interest_payment.planid')
			->where('interest_payment.created_by',$user->id)
			->latest()
			->select('plan_m.plan_name','interest_payment.*')
			->paginate($perPage);

			$totalAmt = InterestPayment::where('created_by',$user->id)
			->sum('pro_amount');
		}
		return view('users.pages.interest-payment', compact('interestPayments','dateRange','mode','stdt','endt','totalAmt'));
	}

	public function viewRecord($id = null)
	{
		$user       		= Auth::user();
        $interestPayment 	= InterestPayment::join('plan_m','plan_m.planid','=','interest_payment.planid')
        ->where('interest_payment.created_by',$user->id)
        ->where('interest_payment.int_proid',$id)
        ->select('plan_m.plan_name','interest_payment.*')
        ->first();
        $mode  = 'View';
        Notifications::readNotification('interest-payment',$id);
        Notifications::readNotification('interest_payment_approve',$id);
        if(count($interestPayment) > 0)
        {
        	return view('users.pages.new-interest-payment', compact('interestPayment','mode'));
        }
        else
        {
        	abort(404);
        }
	}

	public function pending($natureofplanId = null)
	{
		$deposits 	= Deposit::getActiveDeposit();
		$result 	= ReDeposit::getPendingInterest($deposits,$natureofplanId);
		$natureOfPlan = myCustome::natureOfPlan();
		return view('admin.pages.interest-payment-pending', compact('result','natureOfPlan','natureofplanId'));
	}
	
}
