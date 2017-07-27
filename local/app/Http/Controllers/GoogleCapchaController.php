<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\GoogleCapcha;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;

class GoogleCapchaController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$perPage = config('services.DATATABLE.PERPAGE');
        $googleCapchas = GoogleCapcha::paginate($perPage);
		return view('admin.pages.google-capcha', compact('googleCapchas'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-google-capcha', compact('mode'));	
	}

	public function editRecord($id)
	{
		$mode = 'Edit';
		$googlecapcha = GoogleCapcha::findOrFail($id);
		return view('admin.pages.new-google-capcha', compact('mode','googlecapcha'));
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'cap_key' 		=> 'required',
	        'cap_secret' 	=> 'required',
	        'email' 		=> 'required|email',
	    	],[
	    		'cap_key.required'		=> 'The Google Capcha Key field is required.',
	    		'cap_secret.required'	=> 'The Google Capcha Secret field is required.',
	    		'email.required'		=> 'The Google Capcha Email field is required.',
	    		'email.email'			=> 'The Google Capcha Email is invalid.',
	   	]);

		$GoogleCapcha = GoogleCapcha::create([
					'cap_key' 		=> $request->cap_key,
					'cap_secret' 	=> $request->cap_secret,
					'email' 		=> $request->email,
					'status' 		=> 'inactive',
					'created_by'	=> Auth::user()->id,
					'modified_by'	=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->email;

		if($GoogleCapcha)
		{
			Session::flash('message', 'Success! Google Capcha '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/google-capcha');
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'cap_key' 		=> 'required',
	        'cap_secret' 	=> 'required',
	        'email' 		=> 'required|email',
	    	],[
	    		'cap_key.required'		=> 'The Google Capcha Key field is required.',
	    		'cap_secret.required'	=> 'The Google Capcha Secret field is required.',
	    		'email.required'		=> 'The Google Capcha Email field is required.',
	    		'email.email'			=> 'The Google Capcha Email is invalid.',
	   	]);

	   	$id = $request->eid;
		$data = GoogleCapcha::findOrFail($id);

		$update = $data->update([
				'cap_key' 		=> $request->cap_key,
				'cap_secret' 	=> $request->cap_secret,
				'email' 		=> $request->email,
				'modified_by'	=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->email;

		if($update)
		{
			Session::flash('message', 'Success! Google Capcha '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/google-capcha');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$data = GoogleCapcha::findOrFail($id);
		$data->delete();
		echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
	}

	public function chStatus($id)
	{
		$data = GoogleCapcha::findOrFail($id);

		if($data->status == 'active')
		{
			$update = GoogleCapcha::where('capcod', $id)
            ->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);
		}
		else
		{
			GoogleCapcha::where('capcod', '!=' ,  $id)->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);

			$update = GoogleCapcha::where('capcod', $id)
            ->update(['status' => "active" , 'modified_by' => Auth::user()->id ]);

		}

		$BW_MESSAGE = $data->email;

		if($update)
		{
			Session::flash('message', 'Success! Google Capcha '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/google-capcha');
	}
}
