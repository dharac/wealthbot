<?php
namespace App\Http\Controllers;
use App\Ticket;
use App\TicketReply;
use App\User;
use App\Notifications;
use App\EmailNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Redirect;

class TicketReplyController extends Controller
{
    public function store(Request $request)
    {

        if(Auth::user()->hasRole('user'))
        {
            $this->validate($request, [
            'message'   => 'required',
            ],[
                'message.required'=> 'The Ticket Message field is required.',
            ]);

            $ticket     = Ticket::findorfail($request->ticketid);
            if($ticket->user_status == 'closed' && $request->status != 'reopen')
            {
                if($request->status == '')
                {
                    Session::flash('message', 'Error! Ticket is closed. Reopen ticket for send message.');
                }
                else
                {
                    Session::flash('message', 'Error! Ticket is allready closed.');
                }
                 Session::flash('alert-class', 'alert-danger');
                return Redirect::to('/user/ticket/view/'.$request->ticketid);
            }
            else
            {
               $status = 'awaiting_your_reply';
                $user_status = "awaiting_admin_reply";
                if($request->status == 'reopen')
                {
                    $status = 'reopen';
                    $user_status = "awaiting_admin_reply";
                }
                if($request->status == 'closed')
                {
                    $status = $request->status;
                     $user_status = $request->status;
                }
            }
        }
        else
        {
           $this->validate($request, [
            'message'   => 'required',
            'status'   => 'required',
            ],[
                'message.required'=> 'The Ticket Message field is required.',
                'status.required'=> 'The Status field is required.',
            ]);

            $status = $request->status;
            $user_status = "awaiting_your_reply";
            if($request->status == 'inprogress')
            {
                $user_status = $request->status;
            }
            if($request->status == 'closed')
            {
                $user_status = $request->status;
            }
        }

        $mail = '';
        $phone = '';
        $ticket     = Ticket::findorfail($request->ticketid);
        $user       = User::where('id',$ticket->userid)->select('email','phone')->first();
        $mail = "";
        $phone = "";
        if(count($user) > 0)
        {
            $mail       = $ticket->email ? $ticket->email : $user->email;
            $phone      = $ticket->phone ? $ticket->phone : $user->phone;
        }

        $ticket1 = TicketReply::create([
            'ticketid'          => $ticket->ticketid,
            'from_mail'         => Auth::user()->email,
            'to_mail'           => $mail,
            'phone'             => $phone,
            'message'           => $request->message,
            'status'            => $status,
            'user_status'       => $user_status,
            'created_by'        => Auth::user()->id,
        ]);

        $update = $ticket->update([
            'status'            => $status,
            'user_status'           => $user_status,
            'modified_by'       => Auth::user()->id,
        ]);

        $BW_MESSAGE = $request->ticket_no;


        $redirectUrl = 'admin/ticket/view/'.$ticket->ticketid;
        if(Auth::user()->hasRole('user'))
        {
            $redirectUrl = 'user/ticket/view/'.$ticket->ticketid;
        }

        if($ticket1)
        {
            $sendArray  = array(
                    'link_id'           => $ticket->ticketid,
                    'notifiable_id'     => $ticket->created_by,
                    'type'              => 'ticket_reply',
                    'ticket_no'         => $ticket->ticket_no,
                );

            Notifications::Notify($sendArray);

            $created_by = 0;
            if(Auth::user()->hasRole('user'))
            {
                $tr = TicketReply::where('ticketid',$ticket->ticketid)
                ->where('created_by','!=',Auth::user()->id)
                ->select('created_by')
                ->orderby('id','desc')
                ->first();
                
                if(count($tr) > 0)
                {
                    $created_by = $tr->created_by;
                }
            }
             else
             {
                $created_by = $ticket->created_by;  
             }

             $userFetch       = User::where('id',$created_by)->select('email','getresponseid','first_name','username')->first();

             if(count($userFetch) > 0)
             {
                $content = [
                'SUBJECT'       =>  $ticket->subject,
                'EMAIL'         =>  $userFetch->email,
                'EMAIL-ID'      =>  $userFetch->getresponseid,
                'USERNAME'      =>  $userFetch->username,
                'FIRSTNAME'     =>  $userFetch->first_name,
                'TICKET_NO'     =>  $ticket->ticket_no,
                'LOGINURL'      =>  url('login'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'TICKET-REPLY',
                ]; 

                EmailNotify::sendEmailNotification($content);
             }

            Session::flash('message', 'Success! Give ticket '.$BW_MESSAGE.' reply.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to($redirectUrl);
    }
}
