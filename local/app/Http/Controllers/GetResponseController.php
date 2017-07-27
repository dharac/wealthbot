<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\GetResponse;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;


class GetResponseController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$perPage = config('services.DATATABLE.PERPAGE');
        $getResponses = GetResponse::paginate($perPage);
		return view('admin.pages.getresponse', compact('getResponses'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-getresponse', compact('mode'));	
	}

	public function editRecord($id)
	{
		$mode = 'Edit';
		$getresponse = GetResponse::findOrFail($id);
		return view('admin.pages.new-getresponse', compact('mode','getresponse'));
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'username' 				=> 'required',
	        'campaignId' 			=> 'required',
	        'fromFieldId' 			=> 'required',
	        'getresponse_api_key' 	=> 'required',

	    	],[
	    		'username.required'=> 'The Username / Email field is required.',
	    		'campaignId.required'=> 'The Campaign Id field is required.',
	    		'fromFieldId.required'=> 'The From Field Id field is required.',
	    		'getresponse_api_key.required'=> 'The Getresponse Api Key field is required.',
	   	]);

		$insert = GetResponse::create([
					'username' 				=> $request->username,
					'bcc' 					=> $request->bcc,
					'campaignId' 			=> $request->campaignId,
					'fromFieldId' 			=> $request->fromFieldId,
					'getresponse_api_key' 	=> $request->getresponse_api_key,
					'status' 		=> 'inactive',
					'created_by'	=> Auth::user()->id,
					'modified_by'	=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->username;

		if($insert)
		{
			Session::flash('message', 'Success! Getresponse '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/getresponse');
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'username' 				=> 'required',
	        'campaignId' 			=> 'required',
	        'fromFieldId' 			=> 'required',
	        'getresponse_api_key' 	=> 'required',

	    	],[
	    		'username.required'=> 'The Username / Email field is required.',
	    		'campaignId.required'=> 'The Campaign Id field is required.',
	    		'fromFieldId.required'=> 'The From Field Id field is required.',
	    		'getresponse_api_key.required'=> 'The Getresponse Api Key field is required.',
	   	]);

	   	$id = $request->eid;
		$data = GetResponse::findOrFail($id);

		$update = $data->update([
				'username' 				=> $request->username,
				'bcc' 					=> $request->bcc,
				'campaignId' 			=> $request->campaignId,
				'fromFieldId' 			=> $request->fromFieldId,
				'getresponse_api_key' 	=> $request->getresponse_api_key,
				'modified_by'			=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->username;

		if($update)
		{
			Session::flash('message', 'Success! Getresponse '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/getresponse');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$data = GetResponse::findOrFail($id);
		$data->delete();
		echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
	}

	public function chStatus($id)
	{
		$data = GetResponse::findOrFail($id);

		if($data->status == 'active')
		{
			$update = GetResponse::where('getresid', $id)
            ->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);
		}
		else
		{
			GetResponse::where('getresid', '!=' ,  $id)->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);

			$update = GetResponse::where('getresid', $id)
            ->update(['status' => "active" , 'modified_by' => Auth::user()->id ]);

		}

		$BW_MESSAGE = $data->username;

		if($update)
		{
			Session::flash('message', 'Success! Getresponse '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/getresponse');
	}
}
