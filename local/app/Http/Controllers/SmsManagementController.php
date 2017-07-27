<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\SmsManagement;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\myCustome\myCustome;

class SmsManagementController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
    {
        $perPage = config('services.DATATABLE.PERPAGE');   
        $SmsManagements = SmsManagement::where('sanitiz_str','!=','signature')->orderby('smsid')->paginate($perPage);
        $sms_signatures = SmsManagement::where('sanitiz_str','=','signature')->orderby('smsid')->paginate($perPage);
        return view('admin.pages.sms-management', compact('SmsManagements','sms_signatures'));
    }
    public function newRecord()
    {
        $mode = 'Add';
        return view('admin.pages.new-sms-management', compact('mode'));    
    }

    public function editRecord($id)
    {
        $mode = 'Edit';
        $smsManagement = SmsManagement::findOrFail($id);
        if($smsManagement->sanitiz_str == 'signature')
        {
            $mode = 'Signature';
        }
       return view('admin.pages.new-sms-management', compact('mode','smsManagement'));
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'subject'   => 'required',
            'body'      => 'required|max:350',
            ],[
                'subject.required'  => 'The Sms Subject field is required.',
                'body.required'     => 'The Sms Message field is required.',
                'body.max'     => 'The Sms Message field may not be greater than 250 characters.',
        ]);

        $sanitize_subject = myCustome::sanitize($request->subject);

        $insert = SmsManagement::create([
                    'subject'       => $request->subject,
                    'body'          => $request->body,
                    'sanitiz_str'   => $sanitize_subject,
                    'created_by'    => Auth::user()->id,
                    'modified_by'   => Auth::user()->id,
                ]);

        $BW_MESSAGE = $request->subject;

        if($insert)
        {
            Session::flash('message', 'Success! Sms '.$BW_MESSAGE.' Created.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/sms-management');
    }

    public function update(Request $request)
    {
         $validator = $this->validate($request, [
            'subject'   => 'required',
            'body'      => 'required|max:250',
            ],[
                'subject.required'  => 'The Sms Subject field is required.',
                'body.required'     => 'The Sms Message field is required.',
                'body.max'     => 'The Sms Message field may not be greater than 250 characters.',
        ]);

        $id = $request->eid;
        $data = SmsManagement::findOrFail($id);

        $sanitize_subject = myCustome::sanitize($request->subject);

        $update = $data->update([
                'subject'       => $request->subject,
                'body'          => $request->body,
                'sanitiz_str'   => $sanitize_subject,
                'modified_by'   => Auth::user()->id,
            ]);

        $BW_MESSAGE = $request->subject;

        if($update)
        {
            Session::flash('message', 'Success! Sms '.$BW_MESSAGE.' Updated.');
            Session::flash('alert-class', 'alert-success'); 
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/sms-management');
    }

    public function chStatus($id)
    {
         $data = SmsManagement::findOrFail($id);

        if($data->status == 'active')
        {
            $update = SmsManagement::where('smsid', $id)
            ->update(['status' => "inactive" , 'modified_by' => Auth::user()->id ]);
        }
        else
        {
            $update = SmsManagement::where('smsid', $id)
            ->update(['status' => "active" , 'modified_by' => Auth::user()->id ]);

        }

        $BW_MESSAGE = $data->subject;

        if($update)
        {
            Session::flash('message', 'Success! Sms '.$BW_MESSAGE.' Updated.');
            Session::flash('alert-class', 'alert-success'); 
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
        }

        return Redirect::to('admin/sms-management');
    }

    public function update_signature(Request $request)
    {
        $validator = $this->validate($request, [
            'body'         => 'required',
            ],[
                'body.required'         => 'The Sms Signature field is required.',
           ]);

        $id = $request->eid;
        $data = SmsManagement::findOrFail($id);

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
        return Redirect::to('admin/sms-management');
   }
}
?>