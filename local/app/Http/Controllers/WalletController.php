<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Redirect;
use Auth;
use App\myCustome\myCustome;
use App\WalletAmountInOut;
use Illuminate\Support\Facades\Crypt;

class WalletController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
    {
        $status                         = myCustome::walletTypeStatus();
        $id                             = Auth::user()->id;
        $deposit                        = WalletAmountInOut::getDeposit($id);
        $commission                     = WalletAmountInOut::getCommission($id);
        $interest                       = WalletAmountInOut::getInterest($id);
        $initial                        = WalletAmountInOut::getInitialOut($id);
        $wallet                         = WalletAmountInOut::getWallet($id);
        $walletsRecords                 = WalletAmountInOut::getAllByUser($id);
        return view('users.pages.wallet', compact('wallet','walletsRecords','commission','status','initial','interest','deposit'));
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'amount'     => 'required|numeric',
            ],[
                'amount.required'=> 'The Amount field is required.'
        ]);

        $type = Crypt::decryptString($request->eid);
        $amount = $request->amount;
        $typeArray = myCustome::walletTypeStatus();
        $BW_MESSAGE = "";
        $insert = false;
        $userid = Auth::user()->id;

        if(array_key_exists($type,$typeArray))
        {
            $finalAmount = 0;
            if($type == 1)
            {
                $deposit  = WalletAmountInOut::getDeposit($userid);
                $finalAmount = $deposit['deposit_in'] - $deposit['deposit_out'];
            }
            else if($type == 2)
            {
                $interest   = WalletAmountInOut::getInterest($userid);
                $finalAmount = $interest['interest_in'] - $interest['interest_out'];
            }
            else if($type == 3)
            {
                $commission         = WalletAmountInOut::getCommission($userid);
                $finalAmount        = $commission['commission_total'];
            }
            else if($type == 4)
            {
                $initial = WalletAmountInOut::getInitialOut($userid);
                $finalAmount = $initial['initial_in'] - $initial['initial_out'];
            }

            $finalAmount = number_format((float)$finalAmount, 2, '.', '');

            if($amount > 0)
            {
                if($amount <= $finalAmount)
                {
                    $walletarray = array(
                    'amount'            => $amount,
                    'depositid'         => 0,
                    'deposit_type'      => '',
                    'redepositid'       => 0,
                    'status'            => $type,
                    'created_by'        => $userid,
                    );

                    $insert = WalletAmountInOut::InsertinWallet($walletarray);
                    $BW_MESSAGE = number_format($amount,2).' Transferred to Wallet.';
                }
                else
                {
                    $BW_MESSAGE = '$ '.number_format($amount,2).' Transfer in Wallet error';
                }
            }
            else
            {
                $BW_MESSAGE = '$ '.number_format($amount,2).' Transfer in Wallet error. The Transfer Amount should be greater than or equal to $ 1.00.';
            }
        }

        if($insert)
        {
            Session::flash('message', 'Success! $ '.$BW_MESSAGE);
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            if($BW_MESSAGE == "") { Session::flash('message', 'Error! Something went wrong.'); }
            else { Session::flash('message', 'Error! '.$BW_MESSAGE.' '); }
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('wallet');
    }

    public function listData()
    {
        $id = Auth::user()->id;
        $t              = ['interest','all_out','withdraw_initial_deposit'];
        $perPage        = config('services.DATATABLE.PERPAGE');
        $availabelPayouts = WalletAmountInOut::join('deposit', 'deposit.depositid', '=', 'wallet_amount_in_out.depositid')
        ->join('plan_m', 'plan_m.planid', '=', 'deposit.planid')
        ->where('wallet_amount_in_out.created_by',$id)
        ->whereIn('wallet_amount_in_out.status',$t)
        ->latest()
        ->select('wallet_amount_in_out.amount','wallet_amount_in_out.status','wallet_amount_in_out.created_at','plan_m.plan_name','deposit.depositno')
        ->paginate($perPage);
        return view('users.pages.available-payouts', compact('availabelPayouts'));
    }  

    public function walletTransaction()
    {
        $status            = myCustome::walletTypeStatus();
        $walletsRecords    = WalletAmountInOut::getAll();
        return view('admin.pages.wallet-transaction', compact('walletsRecords','status'));
    }
}
?>