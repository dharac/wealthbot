<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\User;
use App\EmailNotify;
use Password;
use Auth;
use App\RoleUser;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = $this->validate($request, [
                'email' => 'required|email'
                ]);

        if($user = User::where('email', $request->input('email') )->first())
        {
            if($user->status == 'active')
            {
                $st = User::passwordRestLink($user);
                return redirect()->back()->with('status', trans(Password::RESET_LINK_SENT));
            }
            else
            {
                $message = config('services.SITE_DETAILS.SITE_USER_INACTIVE');
                return redirect()->back()->withErrors(['email' => $message]);
            }
        }
        return redirect()->back()->withErrors(['email' => trans(Password::INVALID_USER)]);
    }
}
