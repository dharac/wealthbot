<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\EmailNotify;

class InviteFriendController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
		$mode = 'Add';
		return view('users.pages.new-invite-friend', compact('mode'));
	}

	public function store(Request $request)
	{
		$validator = $this->validate($request, [
	        'subject' 		=> 'required',
	        'body' 			=> 'required',	
	    	]);

		$user   = Auth::user();

   		$yourName = ucfirst($user->first_name).' '.ucfirst($user->last_name);
   		$yourEmail = $user->email;

	   	$frnEmail = $request->frnEmail;
	   	$frnName = $request->frnName;
	   	for($i=0;$i<count($frnEmail);$i++)
	   	{
	   		if($frnEmail[$i] != "")
	   		{
	   			$content = [
	                'yourName'    	=>  $yourName,
                	'yourEmail'   	=>  $yourEmail,
                	'name'    		=>  $frnName[$i],
                	'EMAIL'   		=>  $frnEmail[$i],
                	'subject'   	=>  $request->subject,
                	'body'   		=>  $request->body,
                	'TYPE'        	=>  'INVITE-FRIEND',
                ];

        		EmailNotify::sendEmailNotification($content);
	   		}
	   	}


		Session::flash('message', 'Success! Invite friend Send email!.');
		Session::flash('alert-class', 'alert-success');
		return Redirect::to('user/invite-friend');
	}
}
