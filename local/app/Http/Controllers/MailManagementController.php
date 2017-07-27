<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\MailManagement;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\myCustome\myCustome;

class MailManagementController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
    {
        $perPage = config('services.DATATABLE.PERPAGE');   
        $mailManagements = MailManagement::where('sanitiz_str','!=','signature')->orderby('mailid')->paginate($perPage);
        $mail_signatures = MailManagement::where('sanitiz_str','=','signature')->orderby('mailid')->paginate($perPage);
        return view('admin.pages.mail-management', compact('mailManagements','mail_signatures'));
    }

	public function newRecord()
	{
		$mode = 'Add';
		return view('admin.pages.new-mail-management', compact('mode'));	
	}
	
	public function editRecord($id)
    {
        $mode = 'Edit';
        $mailManagement = MailManagement::findOrFail($id);
        if($mailManagement->sanitiz_str == 'signature')
        {
            $mode = 'Signature';
        }
        return view('admin.pages.new-mail-management', compact('mode','mailManagement'));
    }

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'subject' 	=> 'required',
	        'body' 		=> 'required',
	    	],[
	    		'subject.required' 	=> 'The Subject field is required.',
	    		'body.required'	 	=> 'The Mail Message field is required.',
	   	]);

	   	$sanitize_subject = myCustome::sanitize($request->subject);

		$insert = MailManagement::create([
					'subject' 		=> $request->subject,
					'body' 			=> $request->body,
					'sanitiz_str' 	=> $sanitize_subject,
					'created_by'	=> Auth::user()->id,
					'modified_by'	=> Auth::user()->id,
				]);

		$BW_MESSAGE = $request->subject;

		if($insert)
		{
			Session::flash('message', 'Success! Mail '.$BW_MESSAGE.' Created.');
			Session::flash('alert-class', 'alert-success');
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.');
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/mail-management');
	}

	public function update(Request $request)
	{
		$validator = $this->validate($request, [
	        'subject' 	=> 'required',
	        'body' 		=> 'required',
	    	],[
	    		'subject.required' 	=> 'The Subject field is required.',
	    		'body.required'	 	=> 'The Mail Message field is required.',
	   	]);

	   	$id = $request->eid;
		$data = MailManagement::findOrFail($id);

		$sanitize_subject = myCustome::sanitize($request->subject);

		$update = $data->update([
				'subject' 		=> $request->subject,
				'body' 			=> $request->body,
				'sanitiz_str' 	=> $sanitize_subject,
				'modified_by'	=> Auth::user()->id,
			]);

		$BW_MESSAGE = $request->subject;

		if($update)
		{
			Session::flash('message', 'Success! Mail '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}
		return Redirect::to('admin/mail-management');
	}

	public function chStatus($id)
	{
		$data = MailManagement::findOrFail($id);

		if($data->status == 'active')
		{
			$update = MailManagement::where('mailid', $id)
            ->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);
		}
		else
		{
			// MailManagement::where('mailid', '!=' ,  $id)->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);

			$update = MailManagement::where('mailid', $id)
            ->update(['status' => "active" , 'modified_by' => Auth::user()->id ]);

		}

		$BW_MESSAGE = $data->subject;

		if($update)
		{
			Session::flash('message', 'Success! Mail '.$BW_MESSAGE.' Updated.');
			Session::flash('alert-class', 'alert-success'); 
		}
		else
		{
			Session::flash('message', 'Error! Something went wrong.'); 
			Session::flash('alert-class', 'alert-danger');
		}

		return Redirect::to('admin/mail-management');
	}

	public function update_signature(Request $request)
	{
        $validator = $this->validate($request, [
            'body'         => 'required',
            ],[
                'body.required'         => 'The Mail Message field is required.',
           ]);

           $id = $request->eid;
        $data = MailManagement::findOrFail($id);

        $update = $data->update([
                'body'             => $request->body,
                'sanitiz_str'     => 'signature',
                'modified_by'    => Auth::user()->id,
            ]);

        if($update)
        {
            Session::flash('message', 'Success! Signature Updated.');
            Session::flash('alert-class', 'alert-success'); 
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/mail-management');

    }
}
