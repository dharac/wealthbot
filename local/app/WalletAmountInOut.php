<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use App\Withdraw;
use App\Deposit;
use App\myCustome\myCustome;

class WalletAmountInOut extends Model
{
	protected $table       = 'wallet_amount_in_out';
	protected $primaryKey  = 'wallid';
    protected $t_in        = ['1','2','3','4'];
    protected $t_out       = ['redeposit','withdraw','redeposit_another_user'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['wallid', 'amount', 'depositid', 'deposit_type', 'redepositid', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function deposit()
    {
        return $this->belongsTo('App\Deposit', 'depositid');
    }

    public function withdraw()
    {
        return $this->belongsTo('App\Withdraw', 'deposit_type');
    }

    public static function getAllByUser($id)
    {
        $foobar         = new WalletAmountInOut;
        $t              = array_merge($foobar->t_in,$foobar->t_out);
        $perPage        = config('services.DATATABLE.PERPAGE');
        $walletsRecords = WalletAmountInOut::where('created_by',$id)->whereIn('status',$t)->latest()->paginate($perPage);
        return $walletsRecords;
    }

    public static function getAll()
    {
        $foobar         = new WalletAmountInOut;
        $t              = array_merge($foobar->t_in,$foobar->t_out);
        $perPage        = config('services.DATATABLE.PERPAGE');
        $walletsRecords = WalletAmountInOut::whereIn('status',$t)->latest()->paginate($perPage);
        return $walletsRecords;
    }

    public static function getDeposit($id = null)
    {
        $deposit_in     = WalletAmountInOut::where('status','all_out')->where('created_by',$id)->sum('amount');
        $deposit_out    = WalletAmountInOut::where('status','1')->where('created_by',$id)->sum('amount');
        return array('deposit_in' => $deposit_in,'deposit_out' => $deposit_out);
    }

    public static function getInterest($id = null)
    {
        $interest_in        = WalletAmountInOut::where('status','interest')->where('created_by',$id)->sum('amount');
        $interest_out       = WalletAmountInOut::where('status','2')->where('created_by',$id)->sum('amount');
        return array('interest_in' => $interest_in,'interest_out' => $interest_out);
    }

    public static function getCommission($id = null,$type = null)
    {
        $commission_in          = myCustome::availableCommission($id,$type);
        $commission_out         = myCustome::withdrawCommission($id,$type);
        $commission_wallet      = myCustome::walletCommission($id,$type);
        $commission_total       = 0;
        $commission_total       = $commission_in - $commission_out - $commission_wallet;
        return array('commission_in' => $commission_in,'commission_out' => $commission_out,'commission_wallet' => $commission_wallet,'commission_total' => $commission_total);
    }

    public static function getInitialOut($id = null)
    {
        $initial_in         = WalletAmountInOut::where('status','withdraw_initial_deposit')->where('created_by',$id)->sum('amount');
        $initial_out        = WalletAmountInOut::where('status','4')->where('created_by',$id)->sum('amount');
        return array('initial_in' => $initial_in,'initial_out' => $initial_out);
    }

    public static function getWallet($id = null)
    {
        $foobar         = new WalletAmountInOut;
        $wallet_total   = 0;
        $initial_in     = WalletAmountInOut::where('created_by',$id)->whereIn('status', $foobar->t_in)->sum('amount');
        $initial_out    = WalletAmountInOut::where('created_by',$id)->whereIn('status',$foobar->t_out)->sum('amount');
        $wallet_total   = $initial_in - $initial_out;
        return array('wallet_in' => $initial_in,'wallet_out' => $initial_out,'wallet_total' => $wallet_total);
    }

    public static function InsertinWallet($data = null)
    {
        if($data)
        {
            $insert = WalletAmountInOut::create([
                    'amount'            => $data['amount'],
                    'depositid'         => $data['depositid'],
                    'deposit_type'      => $data['deposit_type'],
                    'redepositid'       => $data['redepositid'],
                    'status'            => $data['status'],
                    'created_by'        => $data['created_by'],
                    'modified_by'       => $data['created_by'],
                ]);

            if ($insert)
            {
                $dt = Carbon::now();
                if ($data['status'] == 'all_out')
                {
                    Deposit::where('depositid', $data['depositid'])->update(['status' => 'wallet','description' => 'Amount moved to Wallet' ,'updated_at' => $dt , 'modified_by' => $data['created_by'] ]);
                }
            }
            return $insert;
        }
    }
}
