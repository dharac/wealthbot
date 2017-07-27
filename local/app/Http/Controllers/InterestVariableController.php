<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Redirect;
use App\InterestVariable;
use Auth;
use Session;
use Carbon\Carbon;
use App\myCustome\myCustome;
use Validator;

class InterestVariableController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index(Request $request)
	{
		$perPage = config('services.DATATABLE.PERPAGE');
		$interestvariables = InterestVariable::paginate($perPage);
		return view('admin.pages.interest-variable', compact('interestvariables'));
	}

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-interest-variable', compact('mode'));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
				'interest' 	=> 'required|numeric|max:99',
				'month' 	=> 'required',
				'year' 		=> 'required',
			]);

		$ch = InterestVariable::where('year',$request->year)->where('month',$request->month)->count();
		if($ch > 0)
		{
			$validator->after(function ($validator) {
                $validator->errors()->add('month', 'Please check month and year its already exist.');
            });
		}

		if($validator->fails())
        {
            return redirect('admin/interest-variable/new')->withErrors($validator)->withInput($request->all());
        }

		$insert = InterestVariable::create([
			'interest' 			=> $request->interest,
			'year' 				=> $request->year,
			'month' 			=> $request->month,
			'planid' 			=> 16,
			'created_by'		=> Auth::user()->id,
			'modified_by'		=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->interest;

		if($insert)
		{
			Session::flash('message', 'Success! Interest Variable '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/interest-variable');
	}

	public function editRecord($id)
	{
		$mode = 'Edit';
		$interestvariable = InterestVariable::findOrFail($id);
		return view('admin.pages.new-interest-variable', compact('mode','interestvariable'));	
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'interest' 	=> 'required|numeric|max:99',
			'month' 	=> 'required',
			'year' 		=> 'required',
			]);

		$id = $request->eid;

		$ch = InterestVariable::where('year',$request->year)->where('month',$request->month)->where('varid','!=',$id)->count();
		if($ch > 0)
		{
			$validator->after(function ($validator) {
                $validator->errors()->add('month', 'Please check month and year its already exist.');
            });
		}

		if($validator->fails())
        {
            return redirect('admin/interest-variable/edit/'.$id.'')->withErrors($validator)->withInput($request->all());
        }

        $data = InterestVariable::findOrFail($id);

		$update = $data->update([
			'interest' 			=> $request->interest,
			'year' 				=> $request->year,
			'month' 			=> $request->month,
			'modified_by'		=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->interest;

		if($update)
		{
			Session::flash('message', 'Success! Interest Variable '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/interest-variable');
	}

	public function delete(Request $request)
	{
		$id = $request->id;
		$data = InterestVariable::findOrFail($id);
		$data->delete();
		echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
	}
}
