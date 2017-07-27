<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Session;
use App\ReDeposit;
use Hash;
use Config;
use Redirect;
use Auth;

class PayOutController extends Controller
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
            $mode       = 'search';
            $startdt    = Carbon::parse($request->startdt)->format('m-d-Y');
            $startdt    = Carbon::createFromFormat('m-d-Y', $startdt);
            $enddt      = Carbon::parse($request->enddt)->format('m-d-Y');
            $enddt      = Carbon::createFromFormat('m-d-Y', $enddt);

            $stdt = $request->startdt;
            $endt = $request->enddt;

            $dataSend = array(
                'startdt' => $startdt,
                'enddt' => $enddt,
                'natureofplan' => 0
                );

            $today_date = Carbon::today();
            if(strtotime($stdt) < strtotime($today_date) && strtotime($endt) <= strtotime($today_date))
            {
                $payouts = ReDeposit::payout_past_Report($dataSend);
            }
            else
            {
                 $payouts = ReDeposit::payoutReport($dataSend);
            }

            $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
        }
        else
        {
            $mode   = 'view';
            $payouts = ReDeposit::payoutReport();
        }
        return view('admin.pages.payout-report', compact('payouts','dateRange','mode','stdt','endt'));
    }
}
