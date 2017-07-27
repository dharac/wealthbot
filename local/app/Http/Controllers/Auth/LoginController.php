<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Redirect;
use App\LoginDetail;
use App\Setting;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/login');
    }

    public function login(Request $request)
    {
        $googleCapcha = \App\GoogleCapcha::googleCapchaStatus();

        if(count($googleCapcha) > 0)
        {
            $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response'  => 'required',
            ],[
                    'g-recaptcha-response.required' => 'Captcha is required',
                ]
            );

            $cpa        = $_POST['g-recaptcha-response'];
            $secret     = $googleCapcha->cap_secret;

            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$cpa);
            $responseData = json_decode($verifyResponse);

            $g_result  = $responseData->success;
        }
        else
        {
            $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
            ]);

            $g_result = true;
        }

        $email      = $request->email;
        $password   = $request->password;
        $remember   = $request->remember;
        
        if($g_result)
        {
            if(Auth::attempt(['email' => $email, 'password' => $password],$remember))
            {
                $user = Auth::getLastAttempted();
                if($user->status == 'active')
                {
                    $site_email_verification = Setting::getData('site_email_verification');
                    if($site_email_verification == '1')
                    {
                        if($user->confirmed == 1)
                        {
                            LoginDetail::userLogin();
                            Auth::login($user, $request->has('remember'));
                            return redirect()->intended($this->redirectPath());
                        }
                        else 
                        {

                            Auth::logout();
                            return redirect('login')
                                ->withInput($request->only('email', 'remember'))
                                ->withErrors([
                                    'email' => 'You must be verify your email address to login.'
                                ]);
                        }
                    }
                    else
                    {
                        LoginDetail::userLogin();
                        Auth::login($user, $request->has('remember'));
                        return redirect()->intended($this->redirectPath());
                    }
                }
                else
                {
                    $message = config('services.SITE_DETAILS.SITE_USER_INACTIVE');
                    Auth::logout();
                    return redirect('login')
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                    'email' => $message
                    ]);
                }

            }
            else
            {
                return redirect('login')
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                'email' => 'These credentials do not match our records.'
                ]);
            }
        }
        else
        {
            return redirect('login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'g-recaptcha-response' => 'Wrong captcha, please try again.'
            ]);
        }
    }
}
