<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use App\myCustome\myCustome;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use Cookie;
use DB;
use Response;
use Validator;
use App\User;
use App\Country;
use App\Referral;
use App\Role;
use App\SecurityQuestion;
use App\Notifications;
use App\RoleUser;
use App\EmailNotify;
use App\LoginDetail;
use App\Setting;
use App\EmailChangeVerification;
use App\GetResponse;
use App\Deposit;
use App\SendSms;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::getAllUser();
        $mode = 'view';
        return view('admin.pages.user', compact('users','mode'));
    }

    public function search(Request $request)
    {
        $validator = $this->validate($request, [
            'q'                    => 'required',
            ],[
                'q.required'   => 'The Search Users field is required.',
        ]);

        $query = trim($request->q);
        $users = User::getAllUser($query);
        $mode = 'search';
        $searchValue = $query;
        return view('admin.pages.user', compact('users','mode','searchValue'));
    }

    public function newRecord()
    {
        $mode       = 'Add';
        $mode_type  = "";
        $roles      = [ '' => '-- Role --' ] + Role::pluck('display_name','id')->all();
        $countries  = [ '' => '-- Country --' ] + Country::orderBy('counm')->pluck('counm','coucod')->all();
        $questions  = [ '' => '-- Select One --' ] + SecurityQuestion::pluck('question','secid')->all();
        return view('admin.pages.new-user', compact('mode','countries','questions','roles','mode_type'));
    }

    public function editRecord($id)
    {
        $mode       = 'Edit';
        $mode_type  = '';
        $user       = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->leftJoin('country_m', 'country_m.coucod', '=', 'users.coucod')
        ->where('id',$id)
        ->select('role_user.*','users.*','country_m.cou_code')
        ->first();
        $roles      = [ '' => '-- Role --' ] + Role::pluck('display_name','id')->all();
        $countries  = [ '' => '-- Country --' ] + Country::orderBy('counm')->pluck('counm','coucod')->all();
        $questions  = [ '' => '-- Select One --' ] + SecurityQuestion::pluck('question','secid')->all();
        $refData    = User::where('id',$user->referral)->select('first_name','last_name','username')->first();
        return view('admin.pages.new-user',compact('mode','user','countries','questions','refData','roles','mode_type'));
    }

    public function profileUpdate()
    {
        $mode       = 'Edit';
        $mode_type  = 'profile';
        $id         = Auth::user()->id;
        $user       = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->join('country_m', 'country_m.coucod', '=', 'users.coucod')
        ->where('id',$id)
        ->select('role_user.*','users.*','country_m.cou_code')
        ->first();
        $roles      = [ '' => '-- Role --' ] + Role::pluck('display_name','id')->all();
        $countries  = [ '' => '-- Country --' ] + Country::orderBy('counm')->pluck('counm','coucod')->all();
        $questions  = [ '' => '-- Select One --' ] + SecurityQuestion::pluck('question','secid')->all();
        $refData    = User::where('id',$user->referral)->select('first_name','last_name','username')->first();
        return view('admin.pages.new-user',compact('mode','user','countries','questions','refData','roles','mode_type'));
    }

    public function email($id)
    {
        $mode       = 'Email';
        $user       = User::findOrFail($id);
        return view('admin.pages.email-user',compact('mode','user'));
    }

    public function viewRecord($id)
    {
        $mode       = 'View';
        $mode_type  = "";
        Notifications::readNotification('user-register',$id);
        $user       = User::getSingleUser($id);
        if(count($user) > 0)
        {
            return view('admin.pages.new-user',compact('mode','user','mode_type'));
        }
        else
        {
            abort(404);
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            $validator = Validator::make($request->all(), [
                'first_name'                    => 'required|min:3',
                'last_name'                     => 'required',
                'username'                      => 'required|unique:users|min:3|alpha_dash',
                'email'                         => 'required|email|unique:users',
                'password'                      => 'required|min:6|confirmed',
                'password_confirmation'         => 'required|min:6',
                'phone'                         => 'nullable|numeric',
                'dob'                           => 'nullable|date|date_format:m/d/Y|before:tomorrow',
                'country'                       => 'required',
                'sec_question'                  => 'required',
                'role'                          => 'required',
                'sec_answer'                    => 'required',
                'terms'                         => 'required',
                ],[
                    'first_name.required'   => 'The First name field is required.',
                    'last_name.required'    => 'The Last name field is required.',
                    'username.required'     => 'The Username field is required.',
                    'password.confirmed'    => 'The password and Confirm password does not match.',
                    'dob.required'          => 'The Date of Birth field is required.',
                    'dob.before'            => 'The Date of Birth must be a date of today or before today.',
                    'dob.date'              => 'Invalid Date of Birth format.',
                    'dob.date_format'       => 'The Date of Birth  does not match the format MM/DD/YYYY.',
                    'country.required'      => 'The Country field is required.',
                    'phone.int'             => 'The Cell Phone number must be numeric.',
                    'sec_question.required' => 'The Security Question field is required.',
                    'sec_answer.required'   => 'The Security Answer field is required.',
                    'role.required'         => 'The Role field is required.',
                    'terms.required'        => 'The  Terms and Conditions field is required.',
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
                return redirect('admin/user/new')->withErrors($validator)->withInput($request->all());
            }

            $user       = Auth::user();
            $status     = 'active';
            $dob        = Carbon::parse($request->dob)->format('Y-m-d');

            $referralComplated = 0;
            $referralFname  = '';
            $referralLname  = '';
            $referralEmail  = '';

            if($request->role == 3)
            {
                $roleUser = RoleUser::where('user_id',$request->referral_id)->first();
                if(count($roleUser) > 0)
                {
                    if($roleUser->role_id == 3)
                    {
                        $referralComplated = $request->referral_id;

                        $userCookie = User::where('id',$referralComplated)->select('first_name','last_name','email')->first();
                        if(count($userCookie) > 0)
                        {
                            $referralFname  = $userCookie->first_name;
                            $referralLname  = $userCookie->last_name;
                            $referralEmail  = $userCookie->email;
                        }
                    }
                }
            }

            $confirmation_code = User::getConfirmationCode();

            $site_email_verification = Setting::getData('site_email_verification');
            $confirmed = 1;
            if($site_email_verification == '1')
            {
                $confirmed = 0;
            }

            $founder = $request->founder ? $request->founder : 0;
                
            User::checkCountry($request->country);

            $userInsert = User::create([
                        'first_name'        => ucwords(strtolower($request->first_name)),
                        'last_name'         => ucwords(strtolower($request->last_name)),
                        'referral'          => $referralComplated,
                        'username'          => $request->username,
                        'email'             => $request->email,
                        'gender'            => $request->gender,
                        'dob'               => $dob,
                        'city'              => $request->city,
                        'state'             => $request->state,
                        'coucod'            => $request->country,
                        'zip'               => $request->zip,
                        'bitcoin_id'        => $request->bitcoin_id,
                        'sec_question'      => $request->sec_question,
                        'sec_answer'        => $request->sec_answer,
                        'address'           => $request->address,
                        'phone'             => $request->phone,
                        'status'            => $request->status,
                        'istype'            => 'new',
                        'terms'             => $request->terms,
                        'founder'           => $founder,
                        'confirmation_code' => $confirmation_code,
                        'confirmed'         => $confirmed,
                        'password'          => bcrypt($request->password),
                        'created_by'        => $user->id,
                        'modified_by'       => $user->id
                    ]);

            if($userInsert)
            {
                $BW_MESSAGE = ucwords(strtolower($request->first_name)) .' '.ucwords(strtolower($request->last_name));

                //PUBLIC USER
                $userInsert->roles()->attach($request->role);

                if($referralComplated > 0)
                {
                    $referralInsert = Referral::create([
                        'userid'            => $userInsert->id,
                        'refid'             => $referralComplated,
                        'created_by'        => $user->id,
                        'modified_by'       => $user->id,
                    ]);
                }

                LoginDetail::userLogin($userInsert->id);

                $sendArray  = array(
                            'link_id'  =>  $userInsert->id,
                            'type'     =>  'user-register',
                            'name'     =>  $BW_MESSAGE,
                            'user_id'  =>  $userInsert->id
                        );
                Notifications::Notify($sendArray);

                $name = ucwords(strtolower($request->first_name));
                $acon = GetResponse::addContact(array('name' =>  $name, 'email' => $request->email ) );
                $contact = GetResponse::getContact($request->email);
                if(count($contact) > 0)
                {
                    if(trim(strtolower($contact[0]['email'])) == trim(strtolower($request->email)))
                    {   
                        User::where('id', $userInsert->id)
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
                }
                else
                {
                    $content = [
                    'EMAIL'                 =>  $request->email,
                    'EMAIL-ID'              =>  $getresponseid,
                    'USERNAME'              =>  $request->username,
                    'FIRSTNAME'             =>  $request->first_name,
                    'REFERRAL_ID'           =>  $request->username,
                    'ROLE'                  =>  $request->role,
                    'REFERRAL_LINK'         =>  url('track/'.$request->username.''),
                    'MYREFERRALLINK'        =>  url('user/referral'),
                    'REFERRER_FIRSTNAME'    =>  $referralFname,
                    'REFERRER_LASTNAME'     =>  $referralLname,
                    'CC'                    =>  $referralEmail,
                    'LOGINURL'              =>  url('login'),
                    'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                    'SITENAME'              =>  config('services.SITE_DETAILS.SITE_NAME'),
                    'TYPE'                  =>  'WELCOME',
                    ];

                    EmailNotify::sendEmailNotification($content);
                }

                Session::flash('message', 'Success! User '.$BW_MESSAGE.' Created.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('admin/user/view/'.$userInsert->id.'');
            }
            else
            {
                Session::flash('message', 'Error! Something went wrong.'); 
                Session::flash('alert-class', 'alert-danger');
                return Redirect::to('admin/user');
            }
        }
    }

    public function update(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            $validator = Validator::make($request->all(), [
            'first_name'                    => 'required|min:3',
            'last_name'                     => 'required',
            'dob'                           => 'nullable|date|date_format:m/d/Y|before:tomorrow',
            'email'                         => 'required|email',
            'country'                       => 'required',
            'phone'                         => 'nullable|numeric',
            'sec_question'                  => 'required',
            'sec_answer'                    => 'required',
            'role'                          => 'required',
            ],[
                'first_name.required'       => 'The First name field is required.',
                'last_name.required'        => 'The Last name field is required.',
                'dob.before'                => 'The Date of Birth must be a date of today or before today.',
                'dob.date'                  => 'Invalid Date of Birth format.',
                'dob.date_format'           => 'The Date of Birth  does not match the format MM/DD/YYYY.',
                'country.required'          => 'The Country field is required.',
                'phone.int'                 => 'The Cell Phone number must be numeric.',
                'sec_question.required'     => 'The Security Question field is required.',
                'sec_answer.required'       => 'The Security Answer field is required.',
                'role.required'             => 'The Role field is required.',
            ]);

            $admin_confirmation_status = 0;
            $user_id    = $request->eid;
            $user_data  = User::findOrFail($user_id);

            $old_referral_id = $user_data->referral;
            if($user_data->referral == 0)
            {
                $old_referral_id = 0;
            }

            $new_referral_id = 0;
            if(isset($request->referral_id))
            {
                $new_referral_id = $request->referral_id;
            }

            if(isset($request->admin_confirmation_status) && !empty($request->admin_confirmation_status))
            {
                $admin_confirmation_status = $request->admin_confirmation_status;
            }

            if($old_referral_id != $new_referral_id)
            {   
                    if($old_referral_id != 0 && $new_referral_id == 0)
                    {
                        Session::flash('message', 'Error! Referrer field cannot be left blank.'); 
                        Session::flash('alert-class', 'alert-danger');
                        return Redirect::to('admin/user/view/'.$user_id.'');
                    }
                    else
                    {
                        if($admin_confirmation_status != 1)
                        {
                            $confirm_details['_token']          = $request->_token;
                            $confirm_details['eid']             = $request->eid;
                            $confirm_details['referral']        = $request->referral;
                            $confirm_details['referral_id']     = $new_referral_id;
                            $confirm_details['first_name']      = $request->first_name;
                            $confirm_details['last_name']       = $request->last_name;
                            $confirm_details['username']        = $request->username;
                            $confirm_details['email']           = $request->email;
                            $confirm_details['gender']          = $request->gender;
                            $confirm_details['dob']             = $request->dob;
                            $confirm_details['address']         = $request->address;
                            $confirm_details['city']            = $request->city;
                            $confirm_details['state']           = $request->state;
                            $confirm_details['zip']             = $request->zip;
                            $confirm_details['country']         = $request->country;
                            $confirm_details['countrycode']     = $request->countrycode;
                            $confirm_details['phone']           = $request->phone;
                            $confirm_details['bitcoin_id']      = $request->bitcoin_id;
                            $confirm_details['sec_question']    = $request->sec_question;
                            $confirm_details['sec_answer']      = $request->sec_answer;
                            $confirm_details['role']            = $request->role;
                            $confirm_details['founder']         = $request->founder;
                            $confirm_details['status']          = $request->status;
                            $confirm_details['old_referral_id'] = $old_referral_id;
                            $confirm_details['admin_confirmation_status'] = $admin_confirmation_status  ;
                            $confirm_details['page_name'] = 'user';
                            $confirm_details['id'] = $user_id;
                            $confirm_details['mode'] = 'referal_change';
                            return view('admin.pages.confirm-admin',compact('confirm_details'));
                        }
                    }
            }
            else
            {
                $admin_confirmation_status = 1;
            }

            if($admin_confirmation_status == 1)
            {
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

            
                $emailChangeEmail = 0;
                if($request->email != $user_data->email)
                {
                    $chEmail = User::where('email',$request->email)->count();
                    if($chEmail > 0)
                    {
                        $validator->after(function ($validator) {
                            $validator->errors()->add('email', 'The email has already been taken.');
                        });
                    }
                    else
                    {
                        $emailChangeEmail = 1;
                        $old_email = $user_data->email;
                        $new_email = $request->email;
                    }
                }

                if($request->username != $user_data->username)
                {
                    $chusername = User::where('username',$request->username)->count();
                    if($chusername > 0)
                    {
                        $validator->after(function ($validator) {
                            $validator->errors()->add('username', 'The username has already been taken.');
                        });
                    }
                }

                if($validator->fails()) 
                {
                    return redirect('admin/user/edit/'.$request->eid.'')->withErrors($validator)->withInput($request->all());
                }

                $oldbtc     = $user_data->bitcoin_id;
                $dob  = null;
                if($request->dob != null)
                {
                    $dob    = Carbon::parse($request->dob)->format('Y-m-d');
                }
            
            
                $BW_MESSAGE = ucwords(strtolower($request->first_name)) .' '.ucwords(strtolower($request->last_name));

                $founder = $request->founder ? $request->founder : 0;


                $referralComplated = 0;
                if($request->role == 3)
                {
                    $roleUser = RoleUser::where('user_id',$request->referral_id)->first();
                    if(count($roleUser) > 0)
                    {
                        if($roleUser->role_id == 3)
                        {
                            $referralComplated = $request->referral_id;
                        }
                    }
                }
                if(($old_referral_id == 0 && $new_referral_id == 0) || ($old_referral_id == 0 && $new_referral_id != 0) ||($old_referral_id !=0 && $new_referral_id !=0)){
                    /* update */
                    $user = $user_data->update([
                        'first_name'        => ucwords(strtolower($request->first_name)),
                        'last_name'         => ucwords(strtolower($request->last_name)),
                        'gender'            => $request->gender,
                        'username'          => $request->username,
                        'referral'          => $referralComplated,
                        'dob'               => $dob,
                        'city'              => $request->city,
                        'state'             => $request->state,
                        'coucod'            => $request->country,
                        'zip'               => $request->zip,
                        'bitcoin_id'        => $request->bitcoin_id,
                        'sec_question'      => $request->sec_question,
                        'sec_answer'        => $request->sec_answer,
                        'address'           => $request->address,
                        'phone'             => $request->phone,
                        'status'            => $request->status,
                        'founder'           => $founder,
                        'modified_by'       => Auth::user()->id
                    ]);
                }else{
                    Session::flash('message', 'Error! Something went wrong.'); 
                    Session::flash('alert-class', 'alert-danger');
                    return Redirect::to('admin/user/view/'.$user_id.'');
                }
                if($user)
                {
                    $user_data->roles()->detach();
                    $user_data->roles()->attach($request->role);

                    if($referralComplated > 0)
                    {
                        $refcnt = Referral::where('userid',$user_id)->count();

                        if($refcnt > 0)
                        {
                           $referralInsert =  Referral::where('userid',$user_id)
                            ->update(['refid' => $referralComplated,'modified_by' => Auth::user()->id, ]);
                        }
                        else
                        {
                             $referralInsert = Referral::create([
                            'userid'            => $user_id,
                            'refid'             => $referralComplated,
                            'created_by'        => Auth::user()->id,
                            'modified_by'       => Auth::user()->id,
                            ]);
                        }
                    }
                    
                    if(isset($request->old_referral_id) && isset($request->referral_id))
                    {   
                        if($request->old_referral_id != $request->referral_id)
                        {
                            $old_referral_username = config('services.SITE_DETAILS.SITE_NAME');
                            
                            if($request->old_referral_id != 0)
                            {
                                $old_referral_details = user::find($request->old_referral_id);
                                $old_referral_username = $old_referral_details->username;
                            }

                            $new_referral_username      = "";
                            $new_referral_email         = "";
                            $new_referral_getresponseid = "";
                            $new_referral_details = user::where('id',$request->referral_id)->select('username','email','getresponseid')->first();
                            if(count($new_referral_details) > 0)
                            {
                                $new_referral_username      = $new_referral_details->username;
                                $new_referral_email         = $new_referral_details->email;
                                $new_referral_getresponseid = $new_referral_details->getresponseid;
                            }
                           
                            $content = [
                                'USERNAME'              =>  $request->username,
                                'FIRSTNAME'             =>  $request->first_name,
                                'EMAIL'                 =>  $user_data->email,
                                'EMAIL-ID'              =>  $user_data->getresponseid,
                                'OLD_REFERRER_NAME'     =>  $old_referral_username,
                                'NEW_REFERRER_NAME'     =>  $new_referral_username,
                                'LOGINURL'              =>  url('login'),
                                'CC'                    =>  $new_referral_email,
                                'CC-EMAIL-ID'           =>  $new_referral_getresponseid,
                                'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                                'SITENAME'              =>  config('services.SITE_DETAILS.SITE_NAME'),
                                'TYPE'                  =>  'REFERRER-CHANGE',
                            ];
                                   
                            EmailNotify::sendEmailNotification($content);
                        }
                    }
                    
                    $btcData = array(
                    'USERNAME'  =>  $user_data->username,
                    'FIRSTNAME' =>  $request->first_name,
                    'ID'        =>  $user_id,
                    'PHONE'     =>  $request->phone,
                    'COUCOD'    =>  $request->country,
                    'EMAIL'     =>  $request->email,
                    'EMAIL-ID'  =>  $user_data->getresponseid,
                    'NEWBTC'    =>  $request->bitcoin_id,
                    'OLDBTC'    =>  $oldbtc,
                    );

                    $result = User::updateBitcoinEmail($btcData);

                    if($emailChangeEmail == 1)
                    {
                        $confirmation_code = User::getEmailVerificationCode($user_id);

                        EmailChangeVerification::where('userid',$user_id)
                        ->update(['status'  => 'expire']);

                        $insertverification = EmailChangeVerification::create([
                            'new_email'             => $new_email,
                            'old_email'             => $old_email,
                            'confirmation_code'     => $confirmation_code,
                            'userid'                => $user_id,
                            'status'                => 'new',
                            'created_by'            => Auth::user()->id,
                            'modified_by'           => Auth::user()->id,
                            ]);

                        if($insertverification)
                        {
                            $content = [
                            'EMAIL'                 =>  $user_data->email,
                            'EMAIL-ID'              =>  $user_data->getresponseid,
                            'USERNAME'              =>  $user_data->username,
                            'OLD_EMAIL'             =>  $old_email,
                            'NEW_EMAIL'             =>  $new_email,
                            'FIRSTNAME'             =>  ucwords(strtolower($request->first_name)),
                            'VERIFYURL'             =>  url('email/verify/'.$confirmation_code),
                            'TYPE'                  =>  'ADMIN-EMAILCHANGE',
                            'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL')
                            ];

                            EmailNotify::sendEmailNotification($content);
                        }
                    }

                    Session::flash('message', 'Success! User '.$BW_MESSAGE.' Updated.'); 
                    Session::flash('alert-class', 'alert-success');
                }
                else
                {
                    Session::flash('message', 'Error! Something went wrong.'); 
                    Session::flash('alert-class', 'alert-danger');
                }
            }
            else
            {
                Session::flash('message', 'Error! Something went wrong.'); 
                Session::flash('alert-class', 'alert-danger');
            }
            return Redirect::to('admin/user/view/'.$user_id.'');
        }   
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'first_name'                    => 'required|min:3',
        'last_name'                     => 'required',
        'dob'                           => 'required|date|date_format:m/d/Y|before:tomorrow',
        'country'                       => 'required',
        'phone'                         => 'required|numeric',
        'sec_question'                  => 'required',
        'sec_answer'                    => 'required',
        ],[
            'first_name.required'       => 'The First name field is required.',
            'last_name.required'            => 'The Last name field is required.',
            'dob.required'              => 'The Date of Birth field is required.',
            'dob.date'                  => 'Invalid Date of Birth format.',
            'dob.date_format'           => 'The Date of Birth  does not match the format MM/DD/YYYY.',
            'dob.before'                => 'The Date of Birth must be a date of today or before today.',
            'country.required'          => 'The Country field is required.',
            'phone.required'            => 'The Cell Phone field is required.',
            'phone.numeric'             => 'The Cell Phone number must be numeric.',
            'sec_question.required'     => 'The Security Question field is required.',
            'sec_answer.required'       => 'The Security Answer field is required.',
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

        $user_id    = $request->eid;
        $user_data  = User::findOrFail($user_id);
        $getresponseid = $user_data->getresponseid;
        $emailChangeEmail = 0;
        $emailUpdateMessage = "";
        if($request->email != $user_data->email)
        {
            $chEmail = User::where('email',$request->email)->count();
            if($chEmail > 0)
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add('email', 'The Email has already been taken.');
                });
            }
            else
            {
                $getresponseid = "";
                $emailUpdateMessage = ' <span class="text-danger"><b>'.$request->email.'</b> is your new login E-Mail Address.</span>';
            }
        }

        if($validator->fails()) 
        {
            return redirect('admin/user/profile/update')->withErrors($validator)->withInput($request->all());
        }

        $oldbtc     = $user_data->bitcoin_id;
        $dob        = Carbon::parse($request->dob)->format('Y-m-d');
        $BW_MESSAGE = ucwords(strtolower($request->first_name)) .' '.ucwords(strtolower($request->last_name));

        $user = $user_data->update([
                'first_name'        => ucwords(strtolower($request->first_name)),
                'last_name'         => ucwords(strtolower($request->last_name)),
                'gender'            => $request->gender,
                'email'             => $request->email,
                'dob'               => $dob,
                'city'              => $request->city,
                'state'             => $request->state,
                'coucod'            => $request->country,
                'getresponseid'     => $getresponseid,
                'zip'               => $request->zip,
                'bitcoin_id'        => $request->bitcoin_id,
                'sec_question'      => $request->sec_question,
                'sec_answer'        => $request->sec_answer,
                'address'           => $request->address,
                'phone'             => $request->phone,
                'modified_by'       => Auth::user()->id
            ]);

        if($user)
        {

            $btcData = array(
                'USERNAME'  =>  Auth::user()->username,
                'FIRSTNAME' =>  Auth::user()->first_name,
                'ID'        =>  $user_data->id,
                'PHONE'     =>  $request->phone,
                'COUCOD'    =>  $request->country,
                'EMAIL'     =>  Auth::user()->email,
                'EMAIL-ID'  =>  Auth::user()->getresponseid,
                'NEWBTC'    =>  $request->bitcoin_id,
                'OLDBTC'    =>  $oldbtc,
                );

            $result = User::updateBitcoinEmail($btcData);

            $sms = [
            'ID'                    =>  $user_data->id,
            'FIRSTNAME'             =>  ucwords(strtolower($request->first_name)),
            'PHONE'                 =>  $request->phone,
            'USERNAME'              =>  $request->username,
            'COUCOD'                =>  $request->country,
            'TYPE'                  =>  'PROFILE-UPDATE',
            ];
            SendSms::WealthbotSMS($sms);

            $content = [
                'USERNAME'      =>  Auth::user()->username,
                'FIRSTNAME'     =>  Auth::user()->first_name,
                'EMAIL'         =>  Auth::user()->email,
                'EMAIL-ID'      =>  Auth::user()->getresponseid,
                'LOGINURL'      =>  url('login'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'PROFILE',
            ];

            EmailNotify::sendEmailNotification($content);

            Session::flash('message', 'Success! '.$BW_MESSAGE.' Profile Updated.'.$emailUpdateMessage); 
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('admin/user/profile');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('admin/user/profile/update');
        }
    }

    public function delete(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            $BW_MESSAGE = "Error! Something went wrong.";
            $s_alert_flag = 'alert-danger';
            $user_details = user::findOrFail($request->id);
            if(count($user_details) > 0 )
            {
                $user_has_deposits = Deposit::where('created_by','=',$request->id)->where('status','=','approved')->count();
                $user_has_referrals_users = User::where('referral','=',$request->id)->count();
                if($user_has_deposits == 0 && $user_has_referrals_users == 0)
                {
                    /* redirect to confirm page */
                    $confirm_details['page_name'] = $request->page_name;
                    $confirm_details['id'] = $request->id;
                    $confirm_details['mode'] = 'delete';
                    return view('admin.pages.confirm-admin',compact('confirm_details'));
                }
                else
                {
                    $BW_MESSAGE = 'User cannot be deleted. User has either deposits or referrals linked to it.';
                    $s_alert_flag = 'alert-danger';
                    Session::flash('message',$BW_MESSAGE);
                    Session::flash('alert-class',$s_alert_flag);
                    if(isset($request->search_string) && !empty($request->search_string))
                    {
                       return Redirect::to('admin/'.$request->page_name.'/search?q='.$request->search_string);
                    }
                }
            }
            return Redirect::to('admin/'.$request->page_name);
        }
    }

    public function ConfirmAdmin(Request $request)
    {
        if(Auth::user()->hasRole('admin'))
        {
            $BW_MESSAGE = 'Error! Password Must Be Required.';
            $s_alert_flag = 'alert-danger';
            
            if(isset($request->mode) && !empty($request->mode))
            {    
                $confirm_details['page_name'] = $request->request_page_name;
                $confirm_details['id'] = $request->id;
                $confirm_details['mode'] = $request->mode;
               
                if($request->mode == 'referal_change')
                {
                    $confirm_details['eid']            = $request->eid;
                    $confirm_details['referral']        = $request->referral;
                    $confirm_details['referral_id']     = $request->referral_id;
                    $confirm_details['first_name']      = $request->first_name;
                    $confirm_details['last_name']       = $request->last_name;
                    $confirm_details['username']        = $request->username;
                    $confirm_details['email']           = $request->email;
                    $confirm_details['gender']          = $request->gender;
                    $confirm_details['dob']             = $request->dob;
                    $confirm_details['address']         = $request->address;
                    $confirm_details['city']            = $request->city;
                    $confirm_details['state']           = $request->state;
                    $confirm_details['zip']             = $request->zip;
                    $confirm_details['country']         = $request->country;
                    $confirm_details['countrycode']     = $request->countrycode;
                    $confirm_details['phone']           = $request->phone;
                    $confirm_details['bitcoin_id']      = $request->bitcoin_id;
                    $confirm_details['sec_question']    = $request->sec_question;
                    $confirm_details['sec_answer']      = $request->sec_answer;
                    $confirm_details['role']            = $request->role;
                    $confirm_details['founder']         = $request->founder;
                    $confirm_details['status']          = $request->status;
                    $confirm_details['old_referral_id'] = $request->old_referral_id;
                    $confirm_details['admin_confirmation_status'] = $request->admin_confirmation_status;
                }
            }
         if(isset($request->password) && !empty($request->password))
         {
                $admin_id = Auth::user()->id;
                $current_admin_details = User::find($admin_id);
                
                if(!empty($current_admin_details))
                {
                    $ADMIN_CONFIRM_PASSWORD = Setting::getData('user_delete_or_referrer_change');
                    if ($request->password == $ADMIN_CONFIRM_PASSWORD)
                    {    
                        $action_details = '';
                        if($request->request_page_name == 'user')
                        {
                            $action_details = user::findOrFail($request->id);
                        }

                        if($request->mode == 'delete')
                        {    
                            $user_has_deposits = Deposit::where('created_by','=',$request->id)->where('status','=','approved')->count();
                            $user_has_referrals_users = User::where('referral',$request->id)->count();

                            if($user_has_deposits == 0 && $user_has_referrals_users == 0 )
                            {
                               $action_details->delete();
                               $refrall_data = Referral::where('userid',$request->id)->delete();

                                $referrer_getresponseid = '';
                                if($action_details->referral != 0)
                                {
                                    $referrer_details = User::where('id',$action_details->referral)->select('getresponseid')->first();
                                    if(count($referrer_details) > 0)
                                    {
                                        $referrer_getresponseid = $referrer_details->getresponseid;
                                    }
                                }

                                    $content = [
                                    'USERNAME'              =>  $action_details->username,
                                    'FIRSTNAME'             =>  $action_details->first_name,
                                    'EMAIL'                 =>  $action_details->email,
                                    'EMAIL-ID'              =>  $action_details->getresponseid,
                                    'CC-EMAIL-ID'           =>  $referrer_getresponseid,
                                    'LOGINURL'              =>  url('login'),
                                    'ADMINMAIL'             =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                                    'SITENAME'              =>  config('services.SITE_DETAILS.SITE_NAME'),
                                    'TYPE'                  =>  'USER-DELETED',
                                    ];

                                EmailNotify::sendEmailNotification($content);

                                $BW_MESSAGE = 'Success! User Deleted.';
                                $s_alert_flag = 'alert-success';
                                Session::flash('message',$BW_MESSAGE);
                                Session::flash('alert-class',$s_alert_flag);
                                return Redirect::to('admin/'.$request->request_page_name);
                            }
                            else
                            {
                                $BW_MESSAGE = 'Error! User Not Deleted. '.$request->request_page_name.' Has Deposits Or Referrals.';
                                $s_alert_flag = 'alert-danger';
                                Session::flash('message',$BW_MESSAGE);
                                Session::flash('alert-class',$s_alert_flag);
                                return Redirect::to('admin/'.$request->request_page_name);
                            }
                        }
                        if($request->mode == 'referal_change')
                        {
                            $confirm_details['admin_confirmation_status'] = 1;
                            return Redirect::action('UserController@update',$confirm_details);
                        }
                    }
                    else
                    {
                        Session::flash('message','Error Invalid Password.');
                        Session::flash('alert-class',$s_alert_flag);
                        return view('admin.pages.confirm-admin',compact('confirm_details'));
                    }
                }
            }

            Session::flash('message',$BW_MESSAGE);
            Session::flash('alert-class',$s_alert_flag);
            return view('admin.pages.confirm-admin',compact('confirm_details'));
        }
    }

    public function password()
    {
        return view('admin.pages.password');
    }
    

    public function profile()
    {
        $id             = Auth::user()->id;
        $profile        = User::getSingleUser($id);
        $loginDetails   =  LoginDetail::where('created_by',$id)->orderby('logid', 'desc')->limit(10)->get();
        return view('admin.pages.profile', compact('profile','loginDetails'));
    }

    public function update_password(Request $request)
    {
        $validator = $this->validate($request, [
            'opassword'                     => 'required',
            'password'                      => 'required|min:6|confirmed',
            'password_confirmation'         => 'required|min:6'
            ],[
                'opassword.required'=> 'The old password field is required.',
                'password.confirmed'=> 'The password and Confirm password does not match.',
            ]);


        $opassword  = $request->opassword;
        $password   = $request->password;
        $cpassword  = $request->password_confirmation;

        $user = User::findOrFail(Auth::user()->id);

        $redirect_url = 'admin/user/password';

        if (Hash::check($opassword, $user->password))
        { 
               $user->fill([
                    'password' => bcrypt($password)
                ])->save();

            Session::flash('message', 'Password changed !'); 
            Session::flash('alert-class', 'alert-success'); 
        } 
        else 
        {
            Session::flash('message', 'Password does not match'); 
            Session::flash('alert-class', 'alert-danger');
        }

        return Redirect::to($redirect_url);
    }

    public function autosuggestReferral(Request $request)
    {
        $queryData = $request->referral;
        $users = User::likeWiseUser($queryData);
        
        $abc = array();
        if(count($users) > 0)
        {
            foreach ($users as $user)
            {
                $name = ucfirst($user->first_name).' '.ucfirst($user->last_name).' ('.$user->username.')';
                $abc[] = array("id" => $user->id, "name" => $name);
            }   
        }
        return Response::json($abc);
    }


    public function liveSearch(Request $request)
    {
        $queryData = $request->referral;
        $users = User::userSearchAll($queryData);
        $abc = array();
        if(count($users) > 0)
        {
            foreach ($users as $user)
            {
                $name = $user->username.' | '.ucfirst($user->first_name).' '.ucfirst($user->last_name);

                $abc[] = array("id" => $user->id, "name" => $name);
            }
        }
        return Response::json($abc);
    }

    public function sendEmail(Request $request)
    {
         $validator = $this->validate($request, [
            'subject'   => 'required',
            'eid'       => 'required',
            'body'      => 'required'
            ],[
                'subject.required'  => 'The Subject field is required.',
                'eid.required'     => 'The Primary field is required.',
                'body.required'    => 'The Message field is required.',
            ]);

         $user_id    = $request->eid;
         $user       = User::findOrFail($user_id);

            $content = [
                    'FIRSTNAME'   => $user->first_name,
                    'LASTNAME'    => $user->last_name,
                    'EMAIL'       => $user->email,
                    'EMAIL-ID'    => $user->getresponseid,
                    'SUBJECT'     => $request->subject,
                    'BODY'        => $request->body,
                    'TYPE'        => 'EMAIL-USER',
                ];

        EmailNotify::sendEmailNotification($content);

        $BW_MESSAGE = ucfirst($user->first_name).' '.ucfirst($user->last_name);

        Session::flash('message', 'Success! Send Email to '.$BW_MESSAGE.'.'); 
        Session::flash('alert-class', 'alert-success');
        
        return Redirect::to('admin/user');
    }

    public function autoFill(Request $request)
    {
        $users = User::getUserAutoFillup();
        echo json_encode(array('msg' => 'sucess' ,'data' => $users));
    }

    public function export(Request $request)
    {
        $exporttype = $request->exporttype;
        $users = User::getAllUserforCsv();
        $usersArray = array();

        $data = array(
        'first_name'        => 'First Name',
        'last_name'         => 'Last Name',
        'username'          => 'Username',
        'referrer'          => 'Referrer',
        'display_name'      => 'Role',
        'email'             => 'Email',
        'phone'             => 'Cell Phone',
        'counm'             => 'Country',
        'bitcoin_id'        => 'Bitcoin Wallet Address',
        'status'            => 'Status',
        'registration_date' => 'Registration Date',
        'updated_at'        => 'Modified Date',
        );

        foreach ($data as $key => $value)
        {
            if(count($request->column) > 0)
            {
                if (in_array($key, $request->column))
                {
                    array_push($usersArray,$value);
                }   
            }
            else
            {
                array_push($usersArray,$value);
            }
        }

        $usersArray = array($usersArray);

        $a = 1;
        $usersArray1 = array();
        foreach ($users as $user)
        {
            $phone = "";
            if($user->phone != "")
            {
                $phone = $user->cou_code.' '.$user->phone;
            }

            $referrer  = config('services.SITE_DETAILS.SITE_NAME');
            if($user->ufirst_name != "" || $user->ulast_name != "")
            {
                $referrer = ucfirst($user->ufirst_name).' '.ucfirst($user->ulast_name);
            }

            $sf =array(
            'first_name'        => ucfirst($user->first_name),
            'last_name'         => ucfirst($user->last_name),
            'username'          => $user->username,
            'referrer'          => $referrer,
            'display_name'      => $user->display_name,
            'email'             => $user->email,
            'phone'             => $phone,
            'counm'             => $user->counm,
            'bitcoin_id'        => $user->bitcoin_id,
            'status'            => ucfirst($user->status),
            'registration_date' => dispayTimeStamp($user->created_at),
            'updated_at'        => $user->updated_at,
            );

            foreach ($data as $key => $value)
            {
                if(count($request->column) > 0)
                {
                    if (in_array($key, $request->column))
                    {
                        $usersArray1 = array_merge($usersArray1, array($key => $sf[$key]));
                    }
                }
                else
                {
                    $usersArray1 = array_merge($usersArray1, array($key => $sf[$key]));   
                }
            }

            $usersArray[] = $usersArray1;

            $a++;
        }

        $result = myCustome::Excel($usersArray,'User List',$exporttype);
    }

    function getColumn(Request $request)
    {
        $usersArray = array(
        'first_name'        => 'First Name',
        'last_name'         => 'Last Name',
        'username'          => 'Username',
        'referrer'          => 'Referrer',
        'display_name'      => 'Role',
        'email'             => 'Email',
        'phone'             => 'Cell Phone',
        'counm'             => 'Country',
        'bitcoin_id'        => 'Bitcoin Wallet Address',
        'status'            => 'Status',
        'registration_date' => 'Registration Date',
        'updated_at'        => 'Modified Date',
        );

        echo json_encode(array('msg' => 'success' ,'usersArray' => $usersArray));
    }

    public  function userPanel($id = null)
    {
        Auth::loginUsingId($id);
        return Redirect::to('dashboard');
    }

    public function sendEmailPassword(Request $request)
    {
        $validator = $this->validate($request, [
                'email' => 'required|email'
                ]);

        if($user = User::where('email', $request->input('email') )->first())
        {
            $st = User::passwordRestLink($user);

            $BW_MESSAGE = $user->first_name.' '.$user->last_name;

            Session::flash('message', 'Success! User '.$BW_MESSAGE.' Password Change Email Sent.'); 
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('admin/user/view/'.$user->id.'');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('admin/user');
        }

    }

    public function levelReferrer(Request $request)
    {
        $html = '';
        $referralsUps = Referral::getReferralUpline($request->id,1);
        $cnt = count($referralsUps);
        $html .= '<div class="table-responsive"><table class="table"><thead><tr><th>#</th><th>Name</th><th>Username</th><th>Email</th><th>Level</th></tr></thead><tbody></div>';
        $a = 1;
        for($i=0 ;$i < $cnt; $i++)
        {
            $html .= '<tr>';
                $html .= '<td>'.$a.'</td>';
                $html .= '<td><a href="javascript:void('.$referralsUps[$i]['userid'].')">'.$referralsUps[$i]['first_name'].' '.$referralsUps[$i]['last_name'].'</a></td>';
                $html .= '<td>'.$referralsUps[$i]['username'].'</td>';
                $html .= '<td>'.$referralsUps[$i]['email'].'</td>';
                $html .= '<td>'.myCustome::addOrdinalNumberSuffix($referralsUps[$i]['level']).' Level</td>';
            $html .= '</tr>';
            $a++;
        }
        $html .= '</tbody></table>';
        echo json_encode(array('msg' => 'success' ,'html' => $html));
    }

    public function profilePicture(Request $request)
    {  
        $data = $request->image;
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);

       $data = base64_decode($data);
        $user_data = User::findOrFail(Auth::user()->id);
        $image_name = $user_data->username.'-'.time().'.jpeg';
        $path = storage_path() . "/upload/profile-pictures/" . $image_name;
        file_put_contents($path, $data);
        
       $user_update = $user_data->update([
                    'profile_picture'   => $image_name,
                    'modified_by'       => Auth::user()->id
                ]);
        
       if($user_update)
       {
            $user_data = User::findOrFail(Auth::user()->id);
            $path = asset('local/storage/upload/profile-pictures/'.$user_data->profile_picture);
            return response()->json(['msg' => 'success','profile_name' => $path]);
        }

       return response()->json(['msg'=>'unsuccess']);

   }
}
