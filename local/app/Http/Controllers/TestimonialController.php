<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Country;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Cookie;
use DB;
use Carbon\Carbon;

class TestimonialController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		abort(503);
		$perPage = config('services.DATATABLE.PERPAGE');
        $countries = Country::paginate($perPage);
		return view('admin.pages.country', compact('countries'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-country', compact('mode'));	
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'counm' 	=> 'required',
	    	],[
	    		'counm.required'=> 'The Country Name field is required.'
	   	]);

		$Country = Country::create([
					'counm' 			=> ucwords(strtolower($request->counm)),
					'cou_prefix' 		=> $request->cou_prefix,
					'cou_code' 			=> $request->cou_code,
					'created_by'		=> Auth::user()->id,
					'modified_by'		=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->counm;

		if($Country)
		{
			Session::flash('message', 'Success! Country '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/country');
	}

	public function editRecord($id)
	{
		$mode = 'Edit';
		$country = Country::findOrFail($id);
		return view('admin.pages.new-country', compact('mode','country'));	
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'counm' 	=> 'required',
	    	],[
	    		'counm.required'=> 'The Country Name field is required.'
	   	]);

	   	$id = $request->eid;
		$data = Country::findOrFail($id);

		$update = $data->update([
				'counm' 			=> ucwords(strtolower($request->counm)),
				'cou_prefix' 		=> $request->cou_prefix,
				'cou_code' 			=> $request->cou_code,
				'modified_by'		=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->counm;

		if($update)
		{
			Session::flash('message', 'Success! Country '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/country');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$data = Country::findOrFail($id);
		$data->delete();
		echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
	}
	
}
