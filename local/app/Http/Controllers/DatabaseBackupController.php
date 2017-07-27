<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Response;
use App\EmailNotify;
use App\myCustome\myCustome;

class DatabaseBackupController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$mode = 'Email';
		return view('admin.pages.new-database-backup',compact('mode'));
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'subject' 	=> 'required',
	    	],[
	    		'subject.required'	=> 'The Subject field is required.',
	   	]);

	   	$email = config('services.SITE_DETAILS.SITE_ADMIN_EMAIL');

		$filenm = myCustome::getDbBackup();
		if($filenm != "")
		{
			$content = [
                'EMAIL'         =>  $email,
                'SUBJECT'      	=>  $request->subject,
                'MESSAGE'     	=>  $request->message,
                'FILEPATH'      =>  $filenm,
                'LOGINURL'      =>  url('login'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'DATABASE-BACKUP',
                ];
        	EmailNotify::sendEmailNotification($content);
		}

		Session::flash('message', 'Success! Database Backup Email Send to '.$email.'.');
		Session::flash('alert-class', 'alert-success');
		return Redirect::to('admin/database-backup');
	}
}
