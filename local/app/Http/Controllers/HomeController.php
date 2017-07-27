<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\myCustome\myCustome;
use App\Referral;
use App\EmailNotify;
use Auth;
use Redirect;
use Cookie;
use Session;
use App\User;
use App\Country;
use App\SecurityQuestion;
use App\Notifications;
use App\News;
use App\Plan;
use Validator;
use App\Setting;
use App\EmailChangeVerification;
use Carbon\Carbon;
use App\LoginDetail;
use App\GetResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        return view('public.pages.home');
    }
    public function home()
    {
        $newss = News::where('status','active')->select('news_header','newsid','created_at')->limit(10)->latest()->get();
        return view('home',compact('newss'));
    }

    public function register()
    {
        if(Auth::guest())
        {
            $mode = 'register';
            $referral = User::getReferralUsername();
            $countries  = [ '' => '-- Country --' ] + Country::orderBy('counm')->pluck('counm','coucod')->all();
            $questions  = [ '' => '-- Select One --' ] + SecurityQuestion::pluck('question','secid')->all();
            return view('public.pages.register',compact('mode','countries','questions','referral'));
        }
        else
        {
            return Redirect::to('/dashboard');
        }
    }

    public function newUserRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'                    => 'required|min:3',
            'last_name'                     => 'required',
            'username'                      => 'required|unique:users|min:3|alpha_dash',
            'email'                         => 'required|email|unique:users|same:email_confirmation',
            'email_confirmation'            => 'required|email',
            'password'                      => 'required|min:6|confirmed',
            'password_confirmation'         => 'required|min:6',
            'country'                       => 'required',
            'sec_question'                  => 'required',
            'phone'                         => 'required|numeric',
            'sec_answer'                    => 'required',
            'terms'                         => 'required',
            ],[
                'first_name.required'           => 'The First name field is required.',
                'last_name.required'            => 'The Last name field is required.',
                'username.required'             => 'The Username field is required.',
                'email_confirmation.required'   => 'The Email Confirmation field is required.',
                'email_confirmation.email'      => 'The Email Confirmation field is invalid.',
                'password.confirmed'            => 'The password and Confirm password does not match.',
                'country.required'              => 'The Country field is required.',
                'phone.required'                => 'The Cell Phone field is required.',
                'phone.int'                     => 'The Cell Phone number must be numeric.',
                'sec_question.required'         => 'The Security Question field is required.',
                'sec_answer.required'           => 'The Security Answer field is required.',
                'terms.required'                => 'The  Terms and Conditions field is required.',
        ]);

        if($request->bitcoin_id != "")
        {
            $a = myCustome::bitCoinAddressValidate($request->bitcoin_id);
            if(!$a)
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add('bitcoin_id', 'Invalid Bitcoin Wallet Address.');
                });
            }
        }

        if($request->username == 'wealthbot')
        {
            $validator->after(function ($validator) {
                    $validator->errors()->add('username', 'The username has already been taken.');
                });
        }

        if($validator->fails()) 
        {
            return redirect('register')->withErrors($validator)->withInput($request->all());
        }

        //CHECK COOKIE AND SET REFREALL
        $referral       = 0;
        $referralFname  = '';
        $referralLname  = '';
        $referralEmail  = '';

        $refUser = $request->referral;
        $cookie_ref= Cookie::get('WEALTHBOT_REF_TOKEN');
        $userCookie = User::where('id',$cookie_ref)->first();
        if(count($userCookie) > 0)
        {
            if($userCookie->id == $cookie_ref)
            {
                $referral       = $userCookie->id;
                $referralFname  = $userCookie->first_name;
                $referralLname  = $userCookie->last_name;
                $referralEmail  = $userCookie->email;
            }
        }

        $creator = 0;
        if(!Auth::guest())
        {
            $user = Auth::user();
            $creator = $user->id;
        }

        $confirmation_code = User::getConfirmationCode();

        $confirmed = 1;
        $site_email_verification = Setting::getData('site_email_verification');
        if($site_email_verification == '1')
        {
            $confirmed = 0;
        }

        User::checkCountry($request->country);

        $today = Carbon::now();
        $dt = "2017-08-01";
        $founder = 1;
        if($today->toDateString() >= $dt)
        {
            $founder = 0;
        }
        
        $user = User::create([
                    'first_name'        => ucwords(strtolower($request->first_name)),
                    'last_name'         => ucwords(strtolower($request->last_name)),
                    'referral'          => $referral,
                    'username'          => $request->username,
                    'email'             => $request->email,
                    'gender'            => '',
                    'city'              => '',
                    'state'             => '',
                    'coucod'            => $request->country,
                    'phone'             => $request->phone,
                    'zip'               => '',
                    'bitcoin_id'        => $request->bitcoin_id,
                    'sec_question'      => $request->sec_question,
                    'sec_answer'        => $request->sec_answer,
                    'address'           => '',
                    'status'            => 'active',
                    'istype'            => 'new',
                    'founder'           => $founder,
                    'confirmation_code' => $confirmation_code,
                    'terms'             => $request->terms,
                    'confirmed'         => $confirmed,
                    'password'          => bcrypt($request->password),
                    'created_by'        => $creator,
                    'modified_by'       => $creator
                ]);


        if($user)
        {
            //PUBLIC USER
            $user->roles()->attach(3);
            if($referral > 0)
            {
                $referralInsert = Referral::create([
                    'userid'            => $user->id,
                    'refid'             => $referral,
                    'created_by'        => $user->id,
                    'modified_by'       => $user->id,
                ]);
            }
            LoginDetail::userLogin($user->id);

            $BW_MESSAGE = ucwords(strtolower($request->first_name)) .' '.ucwords(strtolower($request->last_name));

            $sendArray  = array(
                        'link_id'  =>  $user->id,
                        'type'     =>  'user-register',
                        'name'     =>  $BW_MESSAGE,
                        'user_id'  =>  $user->id
                    );
            Notifications::Notify($sendArray);

            $name = ucwords(strtolower($request->first_name));
            $acon = GetResponse::addContact(array('name' =>  $name, 'email' => $request->email ) );
            $contact = GetResponse::getContact($request->email);
            if(count($contact) > 0)
            {
                if(trim(strtolower($contact[0]['email'])) == trim(strtolower($request->email)))
                {   
                    User::where('id', $user->id)
                    ->update(['getresponseid' => $contact[0]['contactId']]);
                }
            }

            $getresponseid = "";
            $usergetResponse = User::where('id',$user->id)->select('getresponseid')->first();
            if(count($usergetResponse) > 0)
            {
                $getresponseid = $usergetResponse->getresponseid;
            }

            if($site_email_verification == '1')
            {
                $content = [
                'EMAIL'                 =>  $request->email,
                'EMAIL-ID'              =>  $getresponseid,
                'USERNAME'              =>  $request->username,
                'FIRSTNAME'             =>  ucwords(strtolower($request->first_name)),
                'VERIFYURL'             =>  url('register/verify/'.$confirmation_code),
                'TYPE'                  =>  'VERIFY',
                'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL')
                ];
                EmailNotify::sendEmailNotification($content);

                Session::flash('message', 'Success! '.$BW_MESSAGE.' please verify your email address.');
            }
            else
            {
                $content = [
                'EMAIL'                 =>  $request->email,
                'EMAIL-ID'              =>  $getresponseid,
                'USERNAME'              =>  $request->username,
                'FIRSTNAME'             =>  $request->first_name,
                'REFERRAL_ID'           =>  $request->username,
                'MYREFERRALLINK'        =>  url('user/referral'),
                'ROLE'                  =>  3,
                'REFERRAL_LINK'         =>  url('track/'.$request->username.''),
                'REFERRER_FIRSTNAME'    =>  $referralFname,
                'REFERRER_LASTNAME'     =>  $referralLname,
                'CC'                    =>  $referralEmail,
                'LOGINURL'              =>  url('login'),
                'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'              =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'                  =>  'WELCOME',
                ];

                EmailNotify::sendEmailNotification($content);

                Session::flash('message', 'Success! '.$BW_MESSAGE.' your account created in '.config('services.SITE_DETAILS.SITE_NAME').'.');
            }

            Session::flash('message2', 'Please check your spam email folder just in case the email got delivered there instead of your inbox. Also please add our Webinar@wealthbot.me & system@wealthbot.info email Address to Your Email Safe Senders List or Contact List.');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('login');
        }
        else
        {
            Session::flash('message', 'Error! User register error.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('login/register');
        }
    }

    public function confirm($confirmation_code)
    {
        if(!$confirmation_code)
        {
            Session::flash('message', 'Error! Invalid URL.');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('login');
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user)
        {
            Session::flash('message', 'Error! Invalid confirmation code.');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('login');
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        $content = [
                'EMAIL'         =>  $user->email,
                'USERNAME'      =>  $user->username,
                'FIRSTNAME'     =>  $user->first_name,
                'REFERRAL_ID'   =>  $user->username,
                'REFERRAL_LINK' =>  url('track/'.$user->username.''),
                'LOGINURL'      =>  url('login'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'WELCOME',
                ];

        EmailNotify::sendEmailNotification($content);

        Session::flash('message', 'You have successfully verified your account.');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('login');
    }

    public function emailConfirm($confirmation_code)
    {
        $url = 'dashboard';
        if (Auth::guest())
        {
            $url = 'login';
        }

        if(!$confirmation_code)
        {
            Session::flash('message', 'Error! Invalid URL.');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to($url);
        }

        $chMail = EmailChangeVerification::where('confirmation_code',$confirmation_code)->where('status','new')->first();

        if (!$chMail)
        {
            Session::flash('message', 'Error! Invalid confirmation code.');
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to($url);
        }

        $user = User::where('email',$chMail->old_email)->first();
        if(count($user) > 0)
        {
            $newEmail               = $chMail->new_email;
            $user->email            = $newEmail;
            $user->getresponseid    = "";
            $user->updated_at       = Carbon::now();
            $user->modified_by      = $chMail->userid;
            $user->save();

            // EmailChangeVerification::where('confirmation_code',$confirmation_code)->delete();
            EmailChangeVerification::where('userid',$chMail->userid)
                ->update(['status'  => 'expire']);

            Session::flash('message', 'You have successfully change your email. You can now login witn new email address <span class="text-danger">'.$newEmail.'</span>.');
            Session::flash('alert-class', 'alert-success');
        }

        return Redirect::to($url);
    }


    public function paymentSuccess()
    {
        myCustome::deleteSeesionPlan();
        return view('public.pages.success');
    }

    public function paymentError()
    {
        myCustome::deleteSeesionPlan();
        return view('public.pages.error');
    }

    public function countryCode(Request $request)
    {
        $country = \App\Country::where('coucod',$request->id)->select('cou_code')->first();
        $code  = "";
        $msg = 'error';
        if(count($country) > 0)
        {
            $code = $country->cou_code;
            $msg = 'success';
        }
        echo json_encode(array('msg' => $msg,'code' => $code));
    }

    public function newsSingle($id)
    {
        $news = News::findOrFail($id);
        $newss = News::where('status','active')->select('news_header','newsid','excerpt')->limit(20)->latest()->get();
        return view('public.pages.news',compact('news','newss'));
    }
}
