<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use App\IpnRequest;
use App\Coinpayment;
use App\Deposit;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;

class IpnController extends Controller
{
	public function __construct()
	{
		
	}
	public function index(Request $request)
	{
		if(Auth::guest())
		{
			$this->middleware('auth');
			abort(404);
		}
		else
		{
			$perPage = config('services.DATATABLE.PERPAGE');
			if($request->q == "")
			{
				$q = "";
				$ipns = IpnRequest::join('users', 'ipn_request.created_by', '=', 'users.id')
        		->select('users.first_name','users.last_name','users.username','users.id', 'ipn_request.*')
        		->orderby('ipn_request.created_at','desc')
        		->paginate($perPage);
			}
			else
			{
				$q = $request->q;
				$ipns = IpnRequest::join('users', 'ipn_request.created_by', '=', 'users.id')
        		->select('users.first_name','users.last_name','users.username','users.id', 'ipn_request.*')
        		->orwhere('ipn_request.transaction_id', 'like','%'.$q.'%')
        		->orWhereRaw("concat(users.first_name, ' ', users.last_name) like '%".$q."%' ")
        		->orwhere('users.username', 'like','%'.$q.'%')
        		->orwhere('ipn_request.status', 'like','%'.$q.'%')
        		->orderby('ipn_request.created_at','desc')
        		->paginate($perPage);
			}
			return view('admin.pages.ipn', compact('ipns','q'));
		}
		
	}

	public function delete(Request $request)
	{
		if(Auth::guest())
		{
			abort(404);
		}
		else
		{
			$id = $request->id;
			$data = IpnRequest::findOrFail($id);
			$data->delete();
			echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
		}
		
	}

	public function ipnRequestCoinpayment(Request $request)
	{
		$_REQUEST 	= $request->input();

		if($_REQUEST != null)
		{
			$_SERVER 	= $request->server();
			$datetime   = Carbon::now(); 
			$coinpayment = Coinpayment::where('status','active')->first();

		    $cp_merchant_id = '';
		    $cp_ipn_secret  = '';
		    $cp_debug_email = '';
		    $order_currency = 'USD';

		    if (count($coinpayment) > 0) 
		    {
	        	$cp_merchant_id     = $coinpayment->merchant_id;
	            $cp_ipn_secret      = $coinpayment->ipn_secret;
	            $cp_debug_email     = $coinpayment->ipn_email;
		    }
		    else
		    {
		        die('Error');
		    }

		    $error_msg = "";
		    if (isset($_SERVER['HTTP_HMAC']) && !empty($_SERVER['HTTP_HMAC']))
		    {
		        $request = file_get_contents('php://input');
		        if ($request !== FALSE && !empty($request))
		        {
		            if (isset($_REQUEST['merchant']) && $_REQUEST['merchant'] == trim($cp_merchant_id))
		            {
		                $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));

		                if($hmac == $_SERVER['HTTP_HMAC'])
		                {
		                    $auth_ok = true;
		                } 
		                else
		                {
		                    $error_msg = 'HMAC signature does not match';
		                }
		            }
		            else 
		            {
		                $error_msg = 'No or incorrect Merchant ID passed';
		            }
		        }
		        else 
		        {
		            $error_msg = 'Error reading POST data';
		        }
		    }
		    else 
		    {
		        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) &&  $_SERVER['PHP_AUTH_USER'] == trim($cp_merchant_id) &&  $_SERVER['PHP_AUTH_PW'] == trim($cp_ipn_secret)) 
		        {
		            $auth_ok = true;
		        } 
		        else 
		        {
		            $error_msg = "Invalid merchant id/ipn secret";
		        }
		    }

		    if($error_msg != "")
		    {
		    	$dataArray = array(
						'transaction_id'     =>   $_REQUEST['txn_id'],
			        	'user_id'            =>   $_REQUEST['custom'],
			        	'amount'             =>   $_REQUEST['amount1'],
			        	'post_contents'      =>   json_encode($_REQUEST),
			        	'message'            =>   $error_msg,
			        	'status'             =>   $_REQUEST['status_text'],
		    		);

		    	IpnRequest::insertIpn($dataArray);
		    }

		    /* IF CORRECT */
		    $error_msg = "";
		    if($auth_ok) 
		    {
		        if ($_REQUEST['ipn_type'] == 'button') 
		        {
		            if ($_REQUEST['merchant'] == $cp_merchant_id) 
		            {
		                if ($_REQUEST['currency1'] == $order_currency)
		                {

		                    $txn_id         = $_REQUEST['txn_id'];
		                    $item_name      = $_REQUEST['item_name'];
		                    $item_number    = $_REQUEST['item_number'];
		                    $custom         = $_REQUEST['custom'];
		                    $amount1        = floatval($_REQUEST['amount1']);
		                    $amount2        = floatval($_REQUEST['amount2']);
		                    $currency1      = $_REQUEST['currency1'];
		                    $currency2      = $_REQUEST['currency2'];
		                    $status         = intval($_REQUEST['status']);
		                    $amount         = floatval($_REQUEST['amount1']);
		                    $status_text    = $_REQUEST['status_text'];

		                    $received_amount    = $_REQUEST['received_amount'];


		                    if ($status >= 100 || $status == 2)
		                    { 
		                        if($amount == $amount1)
		                        {   
		                            $error_msg = 'payment is complete or queued for nightly payout, success';

		                            $dataArray = array(
		                            	'user_id' 			=> $custom,
		                            	'planid' 			=> $item_number,
		                            	'payment_through' 	=> 'coinpayment',
		                            	'description'       =>  '',
		                            	'currency' 			=> $currency1,
		                            	'amount' 			=> $amount,
		                            	'status' 			=> 'approved',
		                            	'transaction_id' 	=> $txn_id,
		                            	'depositdt' 		=> "",
		                            	);

		                        	Deposit::insertDeposite($dataArray);
		                    	}
		                    }
		                    else if($status < 0)
		                    {
		                        $error_msg = "payment error, this is usually final but payments will sometimes be reopened if there was no exchange rate conversion or with seller consent";
		                    } 
		                    else 
		                    {
		                        $error_msg = "payment is pending, you can optionally add a note to the order page";
		                    }

		                    if($error_msg != "")
		                    {
								$dataArray = array(
								'transaction_id'     =>   $txn_id,
								'user_id'            =>   $custom,
								'amount'             =>   $amount,
								'post_contents'      =>   json_encode($_REQUEST),
								'message'            =>   $error_msg,
								'status'             =>   $status_text,
								'status_code'        =>   $status,
								'amount2'            =>   $amount2,
								'received_amount'    =>   $received_amount,
								);

								IpnRequest::insertIpn($dataArray);
		                    }
		                }
		            }
		        }
		    }
			die('IPN OK');
		}
		else
		{
			abort(403, 'Unauthorized action.');
		}
	}
}
