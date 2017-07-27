<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\User;
use DB;
use Redirect;
use Session;


class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $tablenm = config('auth.passwords.users.table');
        $Rows = DB::table($tablenm)->where('email',$request->email)->where('token',$request->token)->first();

        if(count($Rows) > 0)
        {

            $user_data  = User::where('email',$request->email)->first();

            $update = $user_data->update([
                'password'  => bcrypt($request->password),
            ]);

            $affectedRows = DB::table($tablenm)->where('email',$request->email)->delete();

            Session::flash('message', 'Success! Password change successfully.');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('login');
        }
        else
        {
            return redirect('password/reset/'.$request->token.'')
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors([
                            'email' => 'This password reset token is invalid.'
                    ]);
        }
    }
}
