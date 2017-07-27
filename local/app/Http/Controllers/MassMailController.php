<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\User;
use App\EmailNotify;

class MassMailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{	
		return view('admin.pages.new-mass-mail');
	}

    public function send(Request $request)
    {
        if($request->type == 'all')
        {
            $validator = $this->validate($request, [
            'subject'     => 'required',
            'body'     => 'required',
            ],[
                'subject.required'  => 'The Subject field is required.',
                'body.required'     => 'The Body field is required.',
            ]);

            $users  = User::getUser();
        }
        else
        {
            $validator = $this->validate($request, [
            'users'     => 'required',
            'subject'     => 'required',
            'body'     => 'required',
            ],[
                'users.required'    => 'The Users field is required.',
                'subject.required'  => 'The Subject field is required.',
                'body.required'     => 'The Body field is required.',
            ]);

            $users  = User::getUserIdsWise($request->users);
        }

        if(count($users) > 0)
        {
            foreach ($users as $user) 
            {
                $content = [
                'EMAIL'         =>  $user->email,
                'EMAIL-ID'      =>  $user->getresponseid,
                'USERNAME'      =>  $user->username,
                'FIRSTNAME'     =>  $user->first_name,
                'LASTNAME'      =>  $user->last_name,
                'BODY'          =>  $request->body,
                'SUBJECT'       =>  $request->subject,
                'TYPE'          =>  'MASS-EMAIL',
                ];

                EmailNotify::sendEmailNotification($content);
            }

            Session::flash('message', 'Success! Email Send.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/mass-email');
        
    }
}
