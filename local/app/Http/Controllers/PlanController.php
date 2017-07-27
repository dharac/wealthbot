<?php
namespace App\Http\Controllers;
use App\PlanLevel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Plan;
use App\PaymentPeriod;
use App\PlanOld;
use Session;
use Redirect;
use Auth;
use Carbon\Carbon;
use App\myCustome\myCustome;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $perPage = config('services.DATATABLE.PERPAGE');
        $plans = Plan::join('payment_period', 'payment_period.pay_period_id', '=', 'plan_m.interest_period_type')
        ->join('payment_period as payment_period1', 'payment_period1.pay_period_id', '=', 'plan_m.duration_time')
        ->select('payment_period.period as periodofProfit','payment_period1.period_sing as periodofDuration','plan_m.*')
        ->latest()
        ->paginate($perPage);
        return view('admin.pages.plan', compact('plans','durations1','durations2'));
    }

    public function newRecord()
    {
        $mode = 'Add';
        $paymentPeriods  = [ '' => '-- Select --' ] + PaymentPeriod::pluck('period','pay_period_id')->all();
        $paymentPeriods1  = [ '' => '-- Select --' ] + PaymentPeriod::pluck('period_sing','pay_period_id')->all();
        $natureOfPlan = myCustome::natureOfPlan();
        return view('admin.pages.new-plan', compact('mode','paymentPeriods','paymentPeriods1','natureOfPlan'));
    }

    public function editRecord($id)
    {
        $mode = 'Edit';
        $plan = Plan::findOrFail($id);
        $planLevels = PlanLevel::where('planid',$plan->planid)->get();
        $paymentPeriods  = [ '' => '-- Select --' ] + PaymentPeriod::pluck('period','pay_period_id')->all();
        $paymentPeriods1  = [ '' => '-- Select --' ] + PaymentPeriod::pluck('period_sing','pay_period_id')->all();
        $natureOfPlan = myCustome::natureOfPlan();
        return view('admin.pages.new-plan', compact('mode','plan','paymentPeriods','paymentPeriods1','planLevels','natureOfPlan'));
    }

    public function viewRecord($id)
    {
        $mode = 'View';
        $plan = Plan::findOrFail($id);
        $planLevels = PlanLevel::where('planid',$plan->planid)->get();
        $paymentPeriods  = [ '' => '-- Select --' ] + PaymentPeriod::pluck('period','pay_period_id')->all();
        $paymentPeriods1  = [ '' => '-- Select --' ] + PaymentPeriod::pluck('period_sing','pay_period_id')->all();
        $natureOfPlan = myCustome::natureOfPlan();
        return view('admin.pages.new-plan', compact('mode','plan','paymentPeriods','paymentPeriods1','planLevels','natureOfPlan'));
    }

    public function store(Request $request)
    {
        $totalTime = 000;
        if($request->plan_status == 1)
        {
            $totalTime = ($request->duration1.$request->duration2.$request->duration3);

            $this->validate($request, [
                'plan_name'                 => 'required',
                'spend_min_amount'          => 'required|numeric',
                'spend_max_amount'          => 'required|numeric',
                'profit'                    => 'required|numeric',
                'duration_time'             => 'required',
                'interest_period_type'      => 'required',
            ],[
                'plan_name.required'                => 'The Plan Name field is required.',
                'spend_min_amount.required'         => 'The Minimum Amount field is required.',
                'spend_min_amount.numeric'          => 'The Minimum Amount field is Numeric.',
                'spend_max_amount.required'         => 'The Maximum Amount field is required.',
                'spend_max_amount.numeric'          => 'The Maximum Amount field is Numeric.',
                'profit.required'                   => 'The Loan Repayment field is required.',
                'profit.numeric'                    => 'The Loan Repayment field is Numeric.',
                'duration_time.required'            => 'The Duration Time field is required.',
                'interest_period_type.required'     => 'The Loan Repayment Duration field is required.',
            ]);
        }
        else
        {
                $request->duration_time = 1;

                $this->validate($request, [
                'plan_name'                 => 'required',
                'spend_min_amount'          => 'required|numeric',
                'spend_max_amount'          => 'required|numeric',
                'profit'                    => 'required|numeric',
                'interest_period_type'      => 'required',
            ],[
                'plan_name.required'                => 'The Plan Name field is required.',
                'spend_min_amount.required'         => 'The Minimum Amount field is required.',
                'spend_min_amount.numeric'          => 'The Minimum Amount field is Numeric.',
                'spend_max_amount.required'         => 'The Maximum Amount field is required.',
                'spend_max_amount.numeric'          => 'The Maximum Amount field is Numeric.',
                'profit.required'                   => 'The Loan Repayment field is required.',
                'profit.numeric'                    => 'The Loan Repayment field is Numeric.',
                'interest_period_type.required'     => 'The Loan Repayment Duration field is required.',
            ]);
        }

        $founder        = $request->founder ? $request->founder : 0;
        $new_founder    = $request->new_founder ? $request->new_founder : 0;

        $plan = Plan::create([
            'plan_name'                 => $request->plan_name,
            'spend_min_amount'          => $request->spend_min_amount,
            'spend_max_amount'          => $request->spend_max_amount,
            'profit'                    => $request->profit,
            'nature_of_plan'            => $request->nature_of_plan,
            'plan_status'               => $request->plan_status,
            'interest_period_type'      => $request->interest_period_type,
            'duration'                  => $totalTime,
            'duration_time'             => $request->duration_time,
            'founder'                   => $founder,
            'new_founder'               => $new_founder,
            'status'                    => $request->status,
            'created_by'                => Auth::user()->id,
            'modified_by'               => Auth::user()->id,
        ]);

        for ($i = 1; $i <= 4; $i++) 
        {
            if(!is_null($request['level_'.$i])) 
            {
                $testString = $request['level_' . $i];
                $commision = preg_replace("/[^0-9,.]/", "", $testString);

                $planLevel = PlanLevel::create([
                    'planid' => $plan->planid,
                    'level' => $i,
                    'commision' => $commision,
                    'created_by' => Auth::user()->id,
                    'modified_by' => Auth::user()->id,
                ]);
            }
        }

        $BW_MESSAGE = $request->plan_name;

        if($plan)
        {
            Session::flash('message', 'Success! Plan '.$BW_MESSAGE.' Created.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/plan');
    }

    public function update(Request $request)
    {

        $totalTime = 000;
        if($request->plan_status == 1)
        {
            $totalTime = ($request->duration1.$request->duration2.$request->duration3);
        }
        else
        {
            $request->duration_time = 1;
        }

        $this->validate($request, [
            'plan_name'                 => 'required',
            'spend_min_amount'          => 'required|numeric',
            'spend_max_amount'          => 'required|numeric',
            'profit'                    => 'required|numeric',
            'duration_time'             => 'required',
            'interest_period_type'      => 'required',
        ],[
            'plan_name.required'                => 'The Plan Name field is required.',
            'spend_min_amount.required'         => 'The Minimum Amount field is required.',
            'spend_min_amount.numeric'          => 'The Minimum Amount field is Numeric.',
            'spend_max_amount.required'         => 'The Maximum Amount field is required.',
            'spend_max_amount.numeric'          => 'The Maximum Amount field is Numeric.',
            'profit.required'                   => 'The Loan Repayment field is required.',
            'profit.numeric'                    => 'The Loan Repayment field is Numeric.',
            'duration_time.required'            => 'The Duration Time field is required.',
            'interest_period_type.required'     => 'The Loan Repayment Duration field is required.',
        ]);

        $id = $request->eid;
        $data = Plan::findOrFail($id);

        $oldplan = PlanOld::insertData($data);

        $founder        = $request->founder ? $request->founder : 0;
        $new_founder    = $request->new_founder ? $request->new_founder : 0; 

        $update = $data->update([
            'plan_name'                 => $request->plan_name,
            'spend_min_amount'          => $request->spend_min_amount,
            'spend_max_amount'          => $request->spend_max_amount,
            'profit'                    => $request->profit,
            'nature_of_plan'            => $request->nature_of_plan,
            'plan_status'               => $request->plan_status,
            'interest_period_type'      => $request->interest_period_type,
            'duration'                  => $totalTime,
            'duration_time'             => $request->duration_time,
            'founder'                   => $founder,
            'new_founder'               => $new_founder,
            'status'                    => $request->status,
            'modified_by'               => Auth::user()->id
        ]);

        $level = PlanLevel::where('planid',$data->planid)->delete();
        for ($i = 1; $i <= 4; $i++)
        {
            if(!is_null($request['level_'.$i])) 
            {
                $testString = $request['level_' . $i];
                $commision = preg_replace("/[^0-9,.]/", "", $testString);
                
                $planLevel = PlanLevel::create([
                    'planid' => $data->planid,
                    'level' => $i,
                    'commision' => $commision,
                    'created_by' => Auth::user()->id,
                    'modified_by' => Auth::user()->id,
                ]);
            }
        }

        $BW_MESSAGE = $request->plan_name;

        if($update)
        {
            Session::flash('message', 'Success! Plan '.$BW_MESSAGE.' Updated.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/plan');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $data = Plan::findOrFail($id);
        $data->delete();
        echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
    }
}
