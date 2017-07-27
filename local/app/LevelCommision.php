<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;
use App\Referral;
use App\Plan;
use App\PlanLevel;
use App\Notifications;
use Session;

class LevelCommision extends Model
{
    protected $table = 'level_commisions';
    protected $primaryKey = 'comid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['depositid', 'comno','interest','commission','com_rate','referral_level','downlineid','created_by','commission_type', 'modified_by', 'created_at', 'updated_at','status'];


    public static function getLastNewLevelCommision()
    {
        $levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.created_by')
        ->select('level_commisions.*','users.first_name','users.last_name','users.username','users.id')
        ->latest()
        ->limit(10)
        ->get();
        
        return $levelCommisions;
    }

    public static function getLastNewLevelCommisionByUser()
    {
        $id =  Auth::user()->id;
        $levelCommisions = LevelCommision::where('level_commisions.created_by',$id)
        ->select('level_commisions.*')
        ->latest()
        ->limit(5)
        ->get();
        
        return $levelCommisions;
    }

    public static function getUserCommision()
    {
    	$perPage = config('services.DATATABLE.PERPAGE');
    	$loginUsr = Auth::user()->id;
    	$levelCommisions = LevelCommision::join('users', 'users.id', '=', 'level_commisions.downlineid')
        ->where('level_commisions.created_by',$loginUsr)
        ->select('users.first_name','users.last_name','users.username','level_commisions.*')
        ->paginate($perPage);

        $amount = LevelCommision::where('level_commisions.created_by',$loginUsr)->sum('com_amount');
        $commision = LevelCommision::where('level_commisions.created_by',$loginUsr)->sum('commission');

        $data = array(
            'data'      => $levelCommisions,
            'commision' => $commision,
            'amount'    => $amount,
         );

        return $data;
    }

    public static function shareLevelCommision($data = null)
    {
        if($data)
        {
            $planid                 = $data['planid'];
            $user_id                = $data['user_id'];
            $amount                 = $data['commissionamount'];
            $depositid              = $data['depositid_ch'];
            $customeDate            = $data['customeDate'];
            $interest               = $data['interest'];
            $commission_type        = $data['interest_type'];

            $planLevels = PlanLevel::where('planid',$planid)->select('commision','level')->get();
            //CHECK PLAN LEVEL EXIST IN DATABASE 
            if(count($planLevels) > 0)
            {
                Referral::$mixArrayUpline = array();
                $referralsUps = Referral::getReferralUpline($user_id,1);
                $cnt = count($referralsUps);
                
                foreach ($planLevels as $planLevel)
                {
                    //CHECK REFERRAL EXIST IN DATABASE 
                    if($cnt > 0)
                    {
                        for($i=0 ;$i < $cnt; $i++)
                        {
                            //CHECK LEVEL METCH THEN COMMISION ALLOCATE
                            if($referralsUps[$i]['level'] == $planLevel->level)
                            {
                                $totalcom   = 0;
                                $plancom    = $planLevel->commision;

                                $sustainability_mode = Session::get('settings')[0]['sustainability_mode'];

                                $totalcom = LevelCommision::countCommission($sustainability_mode,$interest,$amount,$plancom);

                                $comInt = $amount;
                                if($sustainability_mode == 1)
                                {
                                    $comInt = $interest;
                                }

                                if($referralsUps[$i]['status'] == 'active')
                                {
                                    $comno = LevelCommision::createLevelCommisionNo();
                                    $comInsert  = LevelCommision::create([
                                        'depositid'         =>  $depositid,
                                        'comno'             =>  $comno,
                                        'interest'          =>  $comInt,
                                        'com_rate'          =>  $plancom,
                                        'commission'        =>  $totalcom,
                                        'downlineid'        =>  $user_id,
                                        'status'            =>  'approved',
                                        'commission_type'   =>  $commission_type,
                                        'referral_level'    =>  $planLevel->level,
                                        'created_by'        =>  $referralsUps[$i]['userid'],
                                        'modified_by'       =>  $referralsUps[$i]['userid'],
                                        'created_at'        =>  $customeDate,
                                        'updated_at'        =>  $customeDate,
                                    ]);

                                    return $comInsert;
                                    
                                    // if($comInsert)
                                    // {
                                    //     $sendArray  = array(
                                    //             'link_id'  =>  $comInsert->comid,
                                    //             'type'     =>  'level-commission',
                                    //             'user_id'  =>  $referralsUps[$i]['userid'],
                                    //             'amount'   =>  $totalcom,
                                    //         );
                                    //     Notifications::Notify($sendArray);
                                    // }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    public static function countCommission($sustainability_mode = null,$interest = null,$amount = null,$rate = null)
    {
        $commisionEarn = 0;
        if($sustainability_mode == 1)
        {
            $commisionEarn = $interest * $rate / 100;
        }
        else
        {
            $commisionEarn = $amount * $rate / 100;
        }

        return $commisionEarn;
    }

    public static function createLevelCommisionNo()
    {
        $comid  = LevelCommision::max('comid');
        if($comid == null || $comid == "")
        {
            $comid =  1;
        }
        else
        {
            $comid =  1 + $comid;
        }
        $letter = chr(rand(65,90));
        $number = rand(1,100);
        $orderNoString = 'COM'.$number.$letter.$comid;
        return $orderNoString;
    }
}