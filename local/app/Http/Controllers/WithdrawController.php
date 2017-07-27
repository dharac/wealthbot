<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Withdraw;
use App\Notifications;
use App\EmailNotify;
use App\myCustome\myCustome;
use App\WalletAmountInOut;
use Auth;
use Session;
use Redirect;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
		$perPage = config('services.DATATABLE.PERPAGE');

        $withdraws = Withdraw::join('users', 'withdraw.created_by', '=', 'users.id')
        ->where('withdraw.created_by',$user->id)
        ->latest()
        ->select('withdraw.*')
        ->paginate($perPage);

        $WithdrawType = myCustome::withdrawTypeStatus();
        return view('users.pages.withdraw', compact('withdraws','WithdrawType'));
    }

    public function indexAdmin()
    {
        $perPage  = config('services.DATATABLE.PERPAGE');
        $withdraws  = Withdraw::join('users', 'withdraw.created_by', '=', 'users.id')
        ->select('users.id','users.first_name','users.last_name','users.username','withdraw.*')
        ->orderby('withdraw.created_at','desc')
        ->paginate($perPage);
        $WithdrawType = myCustome::withdrawTypeStatus();
        return view('admin.pages.withdraw', compact('withdraws','WithdrawType'));
    }

    public  function viewRecord($id = null)
    {
        $withdraw  = Withdraw::join('users', 'withdraw.created_by', '=', 'users.id')
        ->select('users.first_name','users.last_name','users.username','withdraw.*')
        ->where('withdraw.withdrawcod',$id)
        ->first();
        $mode = 'View';
        $WithdrawType = myCustome::withdrawTypeStatus();
        Notifications::readNotification('withdraw',$id);

        if(count($withdraw) > 0)
        {
            return view('admin.pages.new-withdraw', compact('withdraw','mode','WithdrawType'));
        }
        else
        {
            abort(404);
        }
    }

    public  function viewRecordUser($id = null)
    {
        $user_id = Auth::user()->id;
        $withdraw  = Withdraw::join('users', 'withdraw.created_by', '=', 'users.id')
        ->select('users.first_name','users.last_name','users.username','withdraw.*')
        ->where('withdraw.withdrawcod',$id)
        ->where('withdraw.created_by',$user_id)
        ->first();
        $mode = 'View';
        Notifications::readNotification('withdraw-approve',$id);
        $WithdrawType = myCustome::withdrawTypeStatus();
        if(count($withdraw) > 0)
        {
            return view('users.pages.new-withdraw', compact('mode','withdraw','WithdrawType'));
        }
        else
        {
            abort(404);
        }
    }

    public function withdraw_pay(Request $request)
    {
        $id = $request->eid;
        $withdraw = Withdraw::approveWithdrawal(array($id));
        Session::flash('message', 'Success! Withdrawal approved. ');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('/admin/withdraw');
    }

    public function withdraw_pay_checked(Request $request)
    {
        $data = $request->values;
        $msg  = 'error';
        if(count($data) > 0)
        {
            $withdraw = Withdraw::approveWithdrawal($data);
            $msg = 'success';
        }
        echo json_encode(array('msg' => $msg));
    }

    public function newRecord()
    {
        $id   = Auth::user()->id;
        $mode = 'Add';
        $WithdrawType = myCustome::withdrawTypeStatus();
        $wallet     = WalletAmountInOut::getWallet($id);
    	return view('users.pages.new-withdraw', compact('mode','WithdrawType','wallet'));
    }

    public function storeWithraw(Request $request)
    {
        $validator = $this->validate($request, [
                'amount'    => 'required|numeric'
            ],[
                'amount.required'           => '* The Amount field is required.',
                'amount.numeric'            => '* The Amount field is numeric.',
            ]);

            $id         = Auth::user()->id;
            $total      = 0;
            $min        = 1.00;
            $wallet     = WalletAmountInOut::getWallet($id);
            $total_wallet_amount = $wallet['wallet_total'];

            $total = number_format($total_wallet_amount,2);
            $total = str_replace(",", "", $total);


            $validator = $this->validate($request, [
                'amount'           => 'required|numeric|max:'.$total.'|min:'.$min.'',
            ],[
                'amount.required'           => '* The Amount field is required.',
                'amount.numeric'            => '* The Amount field is numeric.',
                'amount.max'                => '* The Amount value should be less than or equal to $ '.number_format($total,2).'.',
                'amount.min'                => '* The Amount value should be greater than or equal to $ '.number_format($min,2).'.',
            ]);


            $dataArray = array(
                'status'                => 'pending',
                'deposit_type'          => '',
                'redepositid'           => 0,
                'depositid'             => 0,
                'amount'                => $request->amount,
                'withdraw_type'         => 'wallet',
                'userid'                => $id,
                );

        $withdraw = Withdraw::withdrawInsert($dataArray);


        $walletarray = array(
                'amount'            => $request->amount,
                'depositid'         => 0,
                'deposit_type'      => $withdraw->withdrawcod,
                'redepositid'       => 0,
                'status'            => 'withdraw',
                'created_by'        => $id,
                );
            
        $insertwall = WalletAmountInOut::InsertinWallet($walletarray);

        if($withdraw)
        {
            $sendArray  = array(
                'link_id'  =>  $withdraw->withdrawcod,
                'type'     =>  'withdraw',
                'user_id'  =>  $id,
            );
            
            Notifications::Notify($sendArray);

            $content = [
                'USERNAME'      =>  Auth::user()->username,
                'FIRSTNAME'     =>  Auth::user()->first_name,
                'EMAIL'         =>  Auth::user()->email,
                'EMAIL-ID'      =>  Auth::user()->getresponseid,
                'TXTAMT'        =>  number_format($request->amount,2),
                'WITHDRAWDATE'  =>  dispayTimeStamp(Carbon::now())->toDayDateTimeString(),
                'LOGINURL'      =>  url('login'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'WITHDRAW',
                ];

            EmailNotify::sendEmailNotification($content);

            $bitcoin_id = Auth::user()->bitcoin_id;
            $b_status = 0;
            if($bitcoin_id == "")
            {
                $b_status = 1;
            }
            else
            {
                $a = myCustome::bitCoinAddressValidate($bitcoin_id);
                if(!$a)
                {
                    $b_status = 1;
                }
            }

            if($b_status == 1)
            {
                $content1 = [
                'USERNAME'      =>  Auth::user()->username,
                'FIRSTNAME'     =>  Auth::user()->first_name,
                'EMAIL'         =>  Auth::user()->email,
                'EMAIL-ID'      =>  Auth::user()->getresponseid,
                'PROFILELINK'   =>  url('admin/user/profile'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'EMPTY-BITCOIN',
                ];

                EmailNotify::sendEmailNotification($content1);
            }
            
            Session::flash('message', 'Success! Withdrawal request created. wait... for Admin Approval.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }

        return Redirect::to('/user/withdraw');
    }

    public function export(Request $request)
    {
        $users = Withdraw::withdrawMassPayment();
        $withdrawArray = [];
        $a = 1;
        foreach ($users as $user)
        {
            $withdrawArray[] = [
            'name'          => ucfirst($user->first_name).' '.ucfirst($user->last_name),
            'username'      => $user->username,
            'LTCT'          => 'BTC',
            'bitcoin_id'    => $user->bitcoin_id,
            'amount'        => number_format($user->amount,5),
            ];
        }

        $result = myCustome::Excel($withdrawArray,'Withdrawal List','csv');
    }

}