<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Coinpayment;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Cookie;
use DB;
use Carbon\Carbon;

class CoinpaymentController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$perPage = config('services.DATATABLE.PERPAGE');
        $coinpayments = Coinpayment::paginate($perPage);
		return view('admin.pages.coinpayment', compact('coinpayments'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-coinpayment', compact('mode'));	
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'merchant_id' 	=> 'required',
	        'public_id' 	=> 'required',
	        'private_id' 	=> 'required',
	        'ipn_secret' 	=> 'required',
	        'ipn_email' 	=> 'required|email',

	    	],[
	    		'merchant_id.required'=> 'The Merchant Id field is required.',
	    		'public_id.required'=> 'The Public Id field is required.',
	    		'private_id.required'=> 'The Private Id field is required.',
	    		'ipn_secret.required'=> 'The Ipn Secret field is required.',
	    		'ipn_email.required'=> 'The Ipn Email field is required.',
	    		'ipn_email.email'=> 'The Ipn Email is invalid Format.',
	   	]);

		$Coinpayment = Coinpayment::create([
					'merchant_id' 	=> $request->merchant_id,
					'public_id' 	=> $request->public_id,
					'private_id' 	=> $request->private_id,
					'ipn_secret' 	=> $request->ipn_secret,
					'ipn_email' 	=> $request->ipn_email,
					'status' 		=> 'inactive',
					'created_by'	=> Auth::user()->id,
					'modified_by'	=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->merchant_id;

		if($Coinpayment)
		{
			Session::flash('message', 'Success! Coinpayment '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/coinpayment');
	}

	public function editRecord($id)
	{
		$mode = 'Edit';
		$coinpayment = Coinpayment::findOrFail($id);
		return view('admin.pages.new-coinpayment', compact('mode','coinpayment'));
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'merchant_id' 	=> 'required',
	        'public_id' 	=> 'required',
	        'private_id' 	=> 'required',
	        'ipn_secret' 	=> 'required',
	        'ipn_email' 	=> 'required|email',

	    	],[
	    		'merchant_id.required'=> 'The Merchant Id field is required.',
	    		'public_id.required'=> 'The Public Id field is required.',
	    		'private_id.required'=> 'The Private Id field is required.',
	    		'ipn_secret.required'=> 'The Ipn Secret field is required.',
	    		'ipn_email.required'=> 'The Ipn Email field is required.',
	    		'ipn_email.email'=> 'The Ipn Email is invalid Format.',
	   	]);

	   	$id = $request->eid;
		$data = Coinpayment::findOrFail($id);

		$update = $data->update([
				'merchant_id' 	=> $request->merchant_id,
				'public_id' 	=> $request->public_id,
				'private_id' 	=> $request->private_id,
				'ipn_secret' 	=> $request->ipn_secret,
				'ipn_email' 	=> $request->ipn_email,
				'modified_by'	=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->merchant_id;

		if($update)
		{
			Session::flash('message', 'Success! Coinpayment '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/coinpayment');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$data = Coinpayment::findOrFail($id);
		$data->delete();
		echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
	}

	public function chStatus($id)
	{
		$data = Coinpayment::findOrFail($id);

		if($data->status == 'active')
		{
			$update = Coinpayment::where('coinid', $id)
            ->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);
		}
		else
		{
			Coinpayment::where('coinid', '!=' ,  $id)->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);

			$update = Coinpayment::where('coinid', $id)
            ->update(['status' => "active" , 'modified_by' => Auth::user()->id ]);

		}

		$BW_MESSAGE = $data->merchant_id;

		if($update)
		{
			Session::flash('message', 'Success! Coinpayment '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/coinpayment');
	}
}
