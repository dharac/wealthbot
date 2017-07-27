<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\User;
use App\Deposit;
use Carbon\Carbon;

class Notifications extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'noticod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'notifiable_id','link_id', 'data', 'read','created_by','modified_by'];


    public static function readNotification($type = null , $id = null)
    {
        $userId = Auth::user()->id;
        
        Notifications::where('notifiable_id', $userId)
        ->where('link_id', $id)
        ->where('type', $type)
        ->update(['read' => 1 ]);
    }

    public static function Notify($data = null)
    {
        if($data['type'] == 'level_commision_approve')
        {
            //WHEN ADMIN APPROVE COMMISSION
            Notifications::userLevelCommisionApprove($data);
        }
        else if($data['type'] == 'level-commission')
        {
            Notifications::userLevelCommission($data);
        }
        else if($data['type'] == 'new_ticket_user')
        {
            Notifications::userGenerateTicket($data);
        }
        else if($data['type'] == 'ticket_reply')
        {
            Notifications::adminTicketReply($data);
        }
        else if($data['type'] == 'deposit')
        {
            Notifications::userDepositAmount($data);
        }
        else if($data['type'] == 'interest-payment')
        {
            Notifications::userInterestPayment($data);
        }
        else if($data['type'] == 'withdraw')
        {
            Notifications::userRequestWithdraw($data);
        }
        else if($data['type'] == 'withdraw-approve')
        {
            Notifications::adminApproveRequestWithdraw($data);
        }
        else if($data['type'] == 'user-register')
        {
            //Notifications::newUserRegister($data);
        }
    }

    public static function userLevelCommisionApprove($data = null)
    {
        $message = 'Your level commision $ '.number_format($data['commision'],2).' has been approved.';
        $notifications = Notifications::create([
                'type'                  => $data['type'],
                'notifiable_id'         => $data['notifiable_id'],
                'link_id'               => $data['link_id'],
                'data'                  => $message,
                'read'                  => 0,
                'created_by'            => $data['notifiable_id'],
                'modified_by'           => $data['notifiable_id'],
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ]);
    }

    public static function userGenerateTicket($data = null)
    {
        $logName    = Auth::user()->username;
        $message    = ''.$logName.' generated a new ticket '.$data['ticket_no'].'';

        $users = User::getAdminId();
        $inserts = array();
        if(count($users) > 0)
        {
            foreach ($users as $user)
            {
                $insert = array(  
                    'type'                  => $data['type'],
                    'notifiable_id'         => $user->id,
                    'link_id'               => $data['link_id'],
                    'data'                  => $message,
                    'read'                  => 0,
                    'created_by'            => Auth::user()->id,
                    'modified_by'           => Auth::user()->id,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                    );

                array_push($inserts, $insert);
            }
            Notifications::insert($inserts);
        }
    }

    public static function adminTicketReply($data = null)
    {
        $logName = Auth::user()->username;
        $message = ''.$logName.' reply on your ticket no '.$data['ticket_no'].'';
        $notifications = Notifications::create([
                'type'                  => $data['type'],
                'notifiable_id'         => $data['notifiable_id'],
                'link_id'               => $data['link_id'],
                'data'                  => $message,
                'read'                  => 0,
                'created_by'            => Auth::user()->id,
                'modified_by'           => Auth::user()->id,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
            ]);
    }

    public static function userDepositAmount($data = null)
    {
        $deposit = Deposit::where('depositid',$data['link_id'])->select('created_by')->first();
        if(count($deposit) > 0)
        {
            $created_by = $deposit->created_by;
            $users1 = User::where('id',$created_by)->select('username')->first();
            if(count($users1) > 0)
            {
                $name = $users1->username;
                //USER GET NOTIFICATION AFTER APPROVED AMOUNT
                $message1 = $name.' your deposit amount $ '.number_format($data['amount'],2).' has been approved';
                $notifications = Notifications::create([
                    'type'                  => 'deposit_user',
                    'notifiable_id'         => $created_by,
                    'link_id'               => $data['link_id'],
                    'data'                  => $message1,
                    'read'                  => 0,
                    'created_by'            => $created_by,
                    'modified_by'           => $created_by,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);

                //ADMIN AND SUB ADMIN NOTIFICATION
                $message = ''.$name.' deposited amount $ '.number_format($data['amount'],2).'';
                $users = User::getAdminId();
                $inserts = array();
                if(count($users) > 0)
                {
                    foreach ($users as $user)
                    {
                        $insert = array(  
                            'type'                  => $data['type'],
                            'notifiable_id'         => $user->id,
                            'link_id'               => $data['link_id'],
                            'data'                  => $message,
                            'read'                  => 0,
                            'created_by'            => $data['user_id'],
                            'modified_by'           => $data['user_id'],
                            'created_at'            => Carbon::now(),
                            'updated_at'            => Carbon::now(),
                            );

                        array_push($inserts, $insert);
                    }
                    Notifications::insert($inserts);
                }
            }
        }
    }

    public static function userInterestPayment($data = null)
    {
        $message = 'You have earn interest payment $ '.number_format($data['amount'],2).' ';
        $notifications = Notifications::create([
                'type'                  => $data['type'],
                'notifiable_id'         => $data['user_id'],
                'link_id'               => $data['link_id'],
                'data'                  => $message,
                'read'                  => 0,
                'created_by'            => $data['user_id'],
                'modified_by'           => $data['user_id'],
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
        ]);
    }

    public static function userLevelCommission($data = null)
    {
        $message = 'You have earn level commission $ '.number_format($data['amount'],2).' ';
        $notifications = Notifications::create([
                'type'                  => $data['type'],
                'notifiable_id'         => $data['user_id'],
                'link_id'               => $data['link_id'],
                'data'                  => $message,
                'read'                  => 0,
                'created_by'            => $data['user_id'],
                'modified_by'           => $data['user_id'],
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
        ]);
    }

    public static function userRequestWithdraw($data = null)
    {
        $created_by = $data['user_id'];
        $users1 = User::where('id',$created_by)->select('username')->first();

        if(count($users1) > 0)
        {
            //ADMIN AND SUB ADMIN NOTIFICATION
            $name = $users1->username;
            $message = ''.$name.' request for withdraw.';
            $users = User::getAdminId();
            $inserts = array();
            if(count($users) > 0)
            {
                foreach ($users as $user)
                {
                    $insert = array(  
                        'type'                  => $data['type'],
                        'notifiable_id'         => $user->id,
                        'link_id'               => $data['link_id'],
                        'data'                  => $message,
                        'read'                  => 0,
                        'created_by'            => $data['user_id'],
                        'modified_by'           => $data['user_id'],
                        'created_at'            => Carbon::now(),
                        'updated_at'            => Carbon::now(),
                        );

                    array_push($inserts, $insert);
                }
                Notifications::insert($inserts);
            }
        }
    }

    public static function adminApproveRequestWithdraw($data = null)
    {
        $message = 'Admin approve your withdraw request.';
        $notifications = Notifications::create([
                'type'                  => $data['type'],
                'notifiable_id'         => $data['user_id'],
                'link_id'               => $data['link_id'],
                'data'                  => $message,
                'read'                  => 0,
                'created_by'            => $data['user_id'],
                'modified_by'           => $data['user_id'],
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now(),
        ]);
    }

    public static function newUserRegister($data = null)
    {
            $message = ''.$data['name'].' register on '.config('services.SITE_DETAILS.SITE_NAME').'';
            $users = User::getAdminId();
            $inserts = array();
            if(count($users) > 0)
            {
                foreach ($users as $user)
                {
                    $insert = array(  
                        'type'                  => $data['type'],
                        'notifiable_id'         => $user->id,
                        'link_id'               => $data['link_id'],
                        'data'                  => $message,
                        'read'                  => 0,
                        'created_by'            => $data['user_id'],
                        'modified_by'           => $data['user_id'],
                        'created_at'            => Carbon::now(),
                        'updated_at'            => Carbon::now(),
                        );

                    array_push($inserts, $insert);
                }
                Notifications::insert($inserts);
            }
    }
}
