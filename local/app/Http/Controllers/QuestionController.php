<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\SecurityQuestion;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Cookie;
use DB;
use Carbon\Carbon;

class QuestionController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$perPage = config('services.DATATABLE.PERPAGE');
        $questions = SecurityQuestion::orderby('question')->paginate($perPage);
		return view('admin.pages.question', compact('questions'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-question', compact('mode'));	
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'question' 	=> 'required',
	    	],[
	    		'question.required'=> 'The Question field is required.'
	   	]);

		$questionInsert = SecurityQuestion::create([
					'question' 			=> $request->question,
					'created_by'		=> Auth::user()->id,
					'modified_by'		=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->question;

		if($questionInsert)
		{
			Session::flash('message', 'Success! Security Question '.$BW_MESSAGE.' Created.'); 
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/question');
	}

	public function editRecord($id)
	{
		$mode = 'Edit';
		$questions = SecurityQuestion::findOrFail($id);
		return view('admin.pages.new-question', compact('mode','questions'));
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'question' 	=> 'required',
	    	],[
	    		'question.required'=> 'The Question field is required.'
	   	]);

	   	$id = $request->eid;
		$data = SecurityQuestion::findOrFail($id);

		$update = $data->update([
				'question' 		=> $request->question,
				'modified_by'		=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->question;

		if($update)
		{
			Session::flash('message', 'Success! Security Question '.$BW_MESSAGE.' Updated.');  
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/question');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$data = SecurityQuestion::findOrFail($id);
		$data->delete();
		echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
	}
	
}
