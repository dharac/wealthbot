<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Cookie;
use App\Role;
use App\myCustome\myCustome;
use DB;
use Carbon\Carbon;
use App\Setting;
use App\Country;
use App\SendSms;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'username', 'referral', 'first_name', 'last_name', 'email', 'phone', 'password', 'gender', 'dob', 'address', 'city', 'state', 'coucod', 'zip', 'bitcoin_id', 'sec_question', 'sec_answer','terms','istype', 'status','confirmation_code','founder','confirmed', 'created_by', 'modified_by','getresponseid', 'remember_token', 'created_at', 'updated_at','profile_picture'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function getConfirmationCode()
    {
        $maxid  = User::max('id');
        if($maxid == null || $maxid == "")
        {
            $orderNo =  1;
        }
        else
        {
            $maxid =  1 + $maxid;
        }
        $letter = uniqid(chr(rand(65,90)));
        $number = uniqid(rand(1,1000));
        $customeString = $number.$letter.$maxid;
        return $customeString;
    }

    public static function getEmailVerificationCode($user_id = null)
    {
        $maxid  = $user_id;
        $letter = uniqid(chr(rand(65,90)));
        $number = uniqid(rand(1,1000));
        $customeString = $number.$letter.$maxid;
        return $customeString;
    }

    public static function getAllUser($query = null)
    {
        $perPage = config('services.DATATABLE.PERPAGE');

        if($query == "")
        {
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('referral', 'referral.userid', '=', 'users.id')
            ->leftJoin('users as u1', 'referral.refid', '=', 'u1.id')
            ->orderBy('created_at','desc')
            ->select('users.first_name','users.id','users.username','users.email','users.last_name','users.status','users.phone','users.created_at','users.updated_at','users.founder','users.confirmed','roles.display_name','u1.first_name as ufirst_name','u1.last_name as ulast_name')
            ->paginate($perPage);
        }
        else
        {
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('referral', 'referral.userid', '=', 'users.id')
            ->leftJoin('country_m', 'country_m.coucod', '=', 'users.coucod')
            ->leftJoin('users as u1', 'referral.refid', '=', 'u1.id')
            ->where('users.username', 'like','%'.$query.'%')
            ->orWhereRaw("concat(users.first_name, ' ', users.last_name) like '%".$query."%' ")
            ->orwhere('users.email', 'like','%'.$query.'%')
            ->orwhere('users.id', 'like','%'.$query.'%')
            ->orwhere('users.status', 'like','%'.$query.'%')
            ->orwhere('users.phone', 'like','%'.$query.'%')
            ->orwhere('country_m.counm', 'like','%'.$query.'%')
            ->orwhere('roles.display_name', 'like','%'.$query.'%')
            ->orderBy('created_at','desc')
            ->select('users.first_name','users.id','users.username','users.email','users.last_name','users.status','users.phone','users.created_at','users.updated_at','users.founder','users.confirmed','roles.display_name','u1.first_name as ufirst_name','u1.last_name as ulast_name')
            ->paginate($perPage);
        }
        return $users;
    }

    public static function userSearchAll($queryData = null)
    {
        $users = User::leftJoin('country_m', 'country_m.coucod', '=', 'users.coucod')
        ->where('username', 'like','%'.$queryData.'%')
        ->orWhereRaw("concat(first_name, ' ', last_name) like '%".$queryData."%' ")
        ->orwhere('email', 'like','%'.$queryData.'%')
        ->orwhere('country_m.counm', 'like','%'.$queryData.'%')
        ->orwhere('users.id', 'like','%'.$queryData.'%')
        ->orderBy('first_name')
        ->select('first_name','last_name','id','username')
        ->get();
        return $users;
    }

    public static function getTotalUser()
    {
         $count = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' , 3)
        ->count();
        return $count;
    }

    public static function getTotalUserToday()
    {
        $count = User::whereDate('created_at', '=', Carbon::now()->toDateString())->count();
        return $count;
    }


    public static function getAllUserforCsv()
    {
        $users = User::leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
        ->leftJoin('country_m', 'country_m.coucod', '=', 'users.coucod')
        ->leftJoin('referral', 'referral.userid', '=', 'users.id')
        ->leftJoin('users as u1', 'referral.refid', '=', 'u1.id')
        ->orderBy('first_name')
        ->select('users.first_name','users.id','users.username','users.email','users.last_name','users.status','users.phone','users.created_at','users.updated_at','users.bitcoin_id','roles.display_name','u1.first_name as ufirst_name','u1.last_name as ulast_name','country_m.counm','country_m.cou_code')
        ->get();
        return $users;
    }

    public static function getSingleUser($id = null)
    {
        $user = User::leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
        ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
        ->leftJoin('country_m', 'country_m.coucod', '=', 'users.coucod')
        ->leftJoin('security_question', 'security_question.secid', '=', 'users.sec_question')
        ->leftJoin('referral', 'referral.userid', '=', 'users.id')
        ->leftJoin('users as u1', 'referral.refid', '=', 'u1.id')
        ->where('users.id',$id)
        ->orderBy('users.first_name')
        ->select('users.*','roles.display_name','u1.first_name as ufirst_name','u1.last_name as ulast_name','country_m.counm','country_m.cou_code','security_question.question')
        ->first();
        
        return $user;
    }

    public static function getLastNewUser()
    {
        $users = User::latest()->select('first_name','last_name','username','created_at','id')->limit(10)->get();
        return $users;
    }

    public static function getReferralUsername()
    {
        $referral = config('services.SITE_DETAILS.SITE_NAME');
        $WEALTHBOT_REF_TOKEN = Cookie::get('WEALTHBOT_REF_TOKEN');
        $refUser = User::where('id',$WEALTHBOT_REF_TOKEN)->select('username')->first();
        if(count($refUser) > 0)
        {
            $referral = $refUser->username;
        }
        return $referral;
    }

    public static function likeWiseUser($queryData = null)
    {
        $roleId = 3;
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' , $roleId)
        ->where('username', 'like','%'.$queryData.'%')
        ->select('first_name','last_name','id','username')
        ->get();
        return $users;
    }

    public static function getAdminId($column_nm = null)
    {
        if($column_nm == null)
        {
            $column_nm = array('users.id');
        }
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' ,'1')
        ->select($column_nm)
        ->get();
        return $users;
    }

    public static function getUser()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' ,'3')
        ->where('users.status', '=' ,'active')
        ->select('users.id','users.last_name','users.first_name','users.username','users.email','users.getresponseid')
        ->get();
        return $users;
    }

    public static function getReferralNm($username = null)
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' ,'3')
        ->where('users.status', '=' ,'active')
        ->where('users.username',$username)
        ->select('users.id','users.last_name','users.first_name','users.username','email')
        ->first();

        return $users;
    }

    public static function getFirstUser()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' ,'3')
        ->where('users.status', '=' ,'active')
        ->orderby('users.id','asc')
        ->select('users.id','users.username')
        ->first();

        return $users;
    }

    public static function getUserAutoFillup()
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' ,'3')
        ->orderby('users.first_name')
        ->select('users.id','users.username','users.first_name','users.last_name')
        ->get();
        return $users;
    }

    public static function getUserIdsWise($ids = null)
    {
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' ,'3')
        ->where('users.status', '=' ,'active')
        ->whereIn('users.id', $ids)
        ->select('users.id','users.last_name','users.first_name','users.username','users.email','users.getresponseid')
        ->get();
        return $users;
    }

    public static function updateBitcoinEmail($data = null)
    {
        $OLDBTC = $data['OLDBTC'];
        $NEWBTC = $data['NEWBTC'];

        if($OLDBTC != "" || $NEWBTC != "")
        {
            if($OLDBTC != $NEWBTC)
            {

                $sms = [
                    'ID'                    =>  $data['ID'],
                    'FIRSTNAME'             =>  $data['FIRSTNAME'],
                    'USERNAME'              =>  $data['USERNAME'],
                    'PHONE'                 =>  $data['PHONE'],
                    'COUCOD'                =>  $data['COUCOD'],
                    'TYPE'                  =>  'BITCOIN-CHANGE',
                    ];

                SendSms::WealthbotSMS($sms);


                $ip= myCustome::getIp();
                $content = [
                    'USERNAME'      =>  $data['USERNAME'],
                    'FIRSTNAME'     =>  $data['FIRSTNAME'],
                    'EMAIL'         =>  $data['EMAIL'],
                    'EMAIL-ID'      =>  $data['EMAIL-ID'],
                    'OLDBTC'        =>  $OLDBTC,
                    'NEWBTC'        =>  $NEWBTC,
                    'IP_ADDR'       =>  $ip,
                    'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                    'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                    'TYPE'          =>  'BITCOIN-CHANGE',
                ];
                
                EmailNotify::sendEmailNotification($content);
            }
        }
    }

    public static function passwordRestLink($user = null)
    {
        $token = str_random(64);
        $tablenm = config('auth.passwords.users.table');
        $affectedRows = DB::table($tablenm)->where('email',$user->email)->delete();

        DB::table($tablenm)->insert([
            'email' => $user->email, 
            'token' => $token,
            'created_at' => \Carbon\Carbon::now(),
        ]);

         $content = [
                'USERNAME'    =>  $user->username,
                'FIRSTNAME'   =>  $user->first_name,
                'EMAIL'       =>  $user->email,
                'EMAIL-ID'    =>  $user->getresponseid,
                'RESETURL'    =>  route('password.reset', $token),
                'ADMINMAIL'   =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'    =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'        =>  'RESET-PASSWORD',
            ];

        EmailNotify::sendEmailNotification($content);
    }

    public static function getTotalUserCountry() 
    {
       $count = User::all()->groupBy('coucod')->count();   
       return $count;
    }

    public static function getAllUsers()
    {
        $count = User::join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', '=' , 3)
        ->select('created_at','last_name','id','username')
        ->get();
        return $count;
    }

    public static function getCountryWiseUsersCount() 
    {
        $result =  User::leftJoin('country_m','country_m.coucod', '=', 'users.coucod')
                 ->select(DB::raw('count(id) as user_count, country_m.counm'))
                 ->groupBy('users.coucod')
                 ->get();
        return $result;
    }

    public static function getDuplicators($level='')
    {
        $main_users_details = User::join('deposit','deposit.created_by','=','users.id')
            ->where('users.status','active')
            ->distinct('users.id')
            ->select('users.id','users.first_name','users.last_name','users.referral','deposit.status')
            ->get();

        $users = [];
        foreach ($main_users_details as $user)
        {
            $users[$user->id]  = [
                'name'      => $user->first_name.' '.$user->last_name,
                'referral'  => $user->referral,
                'dpstatus'  => $user->status,
            ];
        }

        $first_dup[]   = 0;
        $third_level[] = 0;
        $fifth_level[] = 0;
        foreach($main_users_details as $main_user)
        {
            $first_dup[$main_user->id] = array();
            /* get it's referrals details */
            // Generate a new array with 'keys' and values in 'name'
            $users_first_level_referals_new   = array_combine(array_keys($users), array_column($users, 'referral'));
            $users_first_level_referals       = array_keys($users_first_level_referals_new,$main_user->id);

            ///$users_first_level_referals = User::where('status','active')->select('id')->where('referral',$main_user->id)->get();

            if(count($users_first_level_referals) > 0)
            {   
                $i            = 0;
                $j            = 0;                    
                $full_name    = '';
                $refer_count  = 0;
                $frefer_count = 0;
                foreach ($users_first_level_referals as $users_first_level_referal) 
                {
                    if($users[$users_first_level_referal]['dpstatus']  == "approved")
                    {
                        $users_second_level_referals_new   = array_combine(array_keys($users), array_column($users, 'referral'));
                        $users_second_level_referals       = array_keys($users_second_level_referals_new,$users_first_level_referal);
                        
                        if(count($users_second_level_referals) > 0 )
                        {
                            foreach ($users_second_level_referals as $second_level_user) 
                            {
                                if($users[$second_level_user]['dpstatus']  == "approved")
                                {   
                                    /* create your duplicator */
                                    $i = $i+1;
                                    $first_dup[$main_user->id]['main_user'] = $main_user->first_name." ".$main_user->last_name;
                                    $first_dup[$main_user->id]['referal_count']        = $i; 
                                    $j++;

                                    // $users_third_level_referals = User::where('status','active')
                                    //     ->select('id','first_name','last_name')
                                    //     ->where('referral',$second_level_user)
                                    //     ->get();
                                    $users_third_level_referals_new   = array_combine(array_keys($users), array_column($users, 'referral'));
                                    $users_third_level_referals       = array_keys($users_third_level_referals_new,$second_level_user);

                                    if(count($users_third_level_referals) > 0 )
                                    {
                                        foreach ($users_third_level_referals as $third_level_user) 
                                        {
                                            if($users[$third_level_user]['dpstatus']  == "approved")
                                            {
                                                $refer_count = $refer_count + 1;
                                                $third_level[$main_user->id]['referal_count'] = $refer_count;
                                                $third_level[$main_user->id]['main_user']     = $main_user->first_name." ".$main_user->last_name;

                                                $users_fourth_level_referals_new   = array_combine(array_keys($users), array_column($users, 'referral'));
                                                $users_fourth_level_referals       = array_keys($users_fourth_level_referals_new,$third_level_user); 

                                                if(count($users_fourth_level_referals) > 0 )
                                                {     
                                                    foreach ($users_fourth_level_referals as $fourth_level_user) 
                                                    {
                                                        if($users[$fourth_level_user]['dpstatus']  == "approved")
                                                        {
                                                            $users_fifth_level_referals_new   = array_combine(array_keys($users), array_column($users, 'referral'));
                                                            $users_fifth_level_referals       = array_keys($users_fifth_level_referals_new,$fourth_level_user); 

                                                            if(count($users_fifth_level_referals) > 0 )
                                                            {
                                                                foreach ($users_fifth_level_referals as $fifth_level_user) 
                                                                {
                                                                    if($users[$fifth_level_user]['dpstatus']  == "approved")
                                                                    {
                                                                        $frefer_count = $frefer_count + 1;
                                                                        $fifth_level[$main_user->id]['referal_count'] = $frefer_count;
                                                                        $fifth_level[$main_user->id]['main_user']     = $main_user->first_name." ".$main_user->last_name;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }          
                                            }
                                        }
                                    }
                                }
                            }                                
                        }       
                    }
                }
            }                
        }    
        $dup['second_level']    = User::CreateDuplicatorArray($first_dup,count($first_dup));
        $dup['third_level']     = User::CreateDuplicatorArray($third_level,count($first_dup));
        $dup['fifth_level']     = User::CreateDuplicatorArray($fifth_level,count($first_dup));
        return $dup;
    } 
    
    public static function CreateDuplicatorArray($level_dup,$count_total)
    {
        $one_duplicator        = 0;
        $two_duplicator        = 0;
        $three_duplicator      = 0;
        $ten_plus_duplicator   = 0;
        $dup                   = [];

        $dup['one']            = 0;
        $dup['two']            = 0;
        $dup['three']          = 0;
        $dup['ten']            = 0;
        $dup['one_username']   = '';
        $dup['two_username']   = '';
        $dup['three_username'] = '';
        $dup['ten_username']   = '';
        $dup['one_userId']   = '';
        $dup['two_userId']   = '';
        $dup['three_userId'] = '';
        $dup['ten_userId']   = '';   
        foreach ($level_dup as $key => $duplicator) 
        {
            
            if(!empty($duplicator)) 
            {

                if($duplicator['referal_count'] == 1) 
                {
                    $dup['one'] = ++$one_duplicator;
                    $dup['one_username'] = $dup['one_username'].",".$duplicator['main_user'];
                    $dup['one_userId']   = $dup['one_userId'].",".$key;
                }
                if($duplicator['referal_count'] == 2) 
                {
                    $dup['two'] = ++$two_duplicator;
                    $dup['two_username'] = $dup['two_username'].",".$duplicator['main_user'];
                    $dup['two_userId'] = $dup['two_userId'].",".$key;
                }
                if($duplicator['referal_count'] == 3) 
                {
                    $dup['three'] = ++$three_duplicator;
                    $dup['three_username'] = $dup['three_username'].",".$duplicator['main_user'];
                    $dup['three_userId'] = $dup['three_userId'].",".$key;
                }
                if($duplicator['referal_count'] >= 10) 
                {
                    $dup['ten'] = ++$ten_plus_duplicator;
                    $dup['ten_username'] = $dup['ten_username'].",".$duplicator['main_user'];
                    $dup['ten_userId'] = $dup['ten_userId'].",".$key;
                }
            }
        }

        $total = $count_total;
        $dup['one_percentage']   = number_format(($dup['one']*100) / $total,2);
        $dup['two_percentage']   = number_format(($dup['two']*100) / $total,2);
        $dup['three_percentage'] = number_format(($dup['three']*100) / $total,2);
        $dup['ten_percentage']   = number_format(($dup['ten']*100) / $total,2);
        $dup['one_username']     = ltrim($dup['one_username'],",");
        $dup['one_userId']       = ltrim($dup['one_userId'],",");
        $dup['two_username']     = ltrim($dup['two_username'],",");
        $dup['two_userId']       = ltrim($dup['two_userId'],",");
        $dup['three_username']   = ltrim($dup['three_username'],",");
        $dup['three_userId']     = ltrim($dup['three_userId'],",");
        $dup['ten_username']     = ltrim($dup['ten_username'],",");
        $dup['ten_userId']       = ltrim($dup['ten_userId'],",");
        
        return $dup;
    }
    
    public static function checkCountry($cou_code) 
    {
        $users = User::where('coucod', '=' ,$cou_code)->count();
        if($users == '0')
        {
            $update_country = Setting::where('data_key', "latest_country")
            ->update(array('data_value' => $cou_code));
        } 
    }

    public static function getLatestCountry()
    {
        $cou_code = Setting::getData('latest_country');
        $country = Country::where('coucod',$cou_code)->select('counm')->first();
        return $country->counm;    
    }
}
