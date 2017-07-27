<?php
namespace App\Http\Controllers;
use App\myCustome\myCustome;
use App\Ticket;
use App\TicketReply;
use App\User;
use App\Notifications;
use App\EmailNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;
use Redirect;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $perPage = config('services.DATATABLE.PERPAGE');
        $user = \Auth::user();
        $tickets = Ticket::join('users', 'tickets.userid', '=', 'users.id')
            ->where('tickets.userid',$user->id)
            ->select('tickets.*')
            ->latest()
            ->paginate($perPage);
        $statuss = myCustome::getTicketSupportUserStatus();
        return view('users.pages.ticket', compact('tickets','statuss'));
    }

    public function adminIndex(Request $request)
    {
        $perPage = config('services.DATATABLE.PERPAGE');
        $s = "";
        $q = "";
        if($request->q != "")
        {
            $q = $request->q;
            $tickets = Ticket::join('users', 'tickets.userid', '=', 'users.id')
            ->select('tickets.*','users.username','users.last_name','users.first_name','users.id')
            ->orwhere('tickets.status', 'like','%'.$q.'%')
            ->orwhere('tickets.ticket_no', 'like','%'.$q.'%')
            ->orwhere('tickets.ticketid', 'like','%'.$q.'%')
            ->orwhere('users.username', 'like','%'.$q.'%')
            ->orWhereRaw("concat(users.first_name, ' ', users.last_name) like '%".$q."%' ")
            ->latest()
            ->paginate($perPage);
        }
        else if($request->s != "")
        {
            $s = $request->s;
            $tickets = Ticket::join('users', 'tickets.userid', '=', 'users.id')
            ->select('tickets.*','users.username','users.last_name','users.first_name','users.id')
            ->orwhere('tickets.status', 'like','%'.$s.'%')
            ->latest()
            ->paginate($perPage);
        }
        else
        {
            $tickets = Ticket::latest()->paginate($perPage);
        }

        $statuss = myCustome::getTicketSupportUserStatus();
        return view('admin.pages.ticket', compact('tickets','statuss','q','s'));
    }

    public function newRecord()
    {
        $mode = 'Add';
        return view('users.pages.new-ticket', compact('mode'));
    }

    public function editRecord($id)
    {
        $ticket = Ticket::findOrFail($id);
        $mode = 'Edit';
        return view('users.pages.new-ticket', compact('mode','ticket'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'subject'   => 'required',
            'message'   => 'required',
        ],[
            'subject.required'  => 'The Ticket Subject field is required.',
            'message.required'  => 'The Ticket Message field is required.',
        ]);

        $email = Auth::user()->email;
        $phone = Auth::user()->phone;

        $excerpt = strip_tags(Str::words($request->message,12));
        $status = 'new';

        $id = $request->eid;
        $data = Ticket::findOrFail($id);

        $update = $data->update([
            'subject'           => $request->subject,
            'message'           => $request->message,
            'excerpt'           => $excerpt,
            'email'             => $email,
            'phone'             => $phone,
            'status'            => $status,
            'modified_by'       => Auth::user()->id,
            ]);

        $BW_MESSAGE = $data->ticket_no;

        if($update)
        {
            Session::flash('message', 'Success! Ticket '.$BW_MESSAGE.' Updated.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('/user/ticket');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' 	=> 'required',
            'message' 	=> 'required',
            'email'   => 'sometimes|nullable|email',
        ],[
            'subject.required'  => 'The Ticket Subject field is required.',
            'message.required'  => 'The Ticket Message field is required.',
            'email.email'       => 'Invalid Email.',
        ]);

        $email = $request->email;
        $phone = $request->phone;

        if($email == "")
        {
            $email = Auth::user()->email;
        }

        if($phone == "")
        {
            $phone = Auth::user()->phone;
        }

        $excerpt = strip_tags(Str::words($request->message,12));
        $ticket_no = Ticket::getTicketNo();

        $status = 'new';
        $user_status = 'awaiting_admin_reply';
      
        $ticket = Ticket::create([
            'ticket_no'     => $ticket_no,
            'userid'        => Auth::user()->id,
            'subject'       => $request->subject,
            'message'       => $request->message,
            'excerpt'       => $excerpt,
            'email'         => $email,
            'phone'         => $phone,
            'status'        => $status,
            'user_status'   => $user_status,
            'created_by'    => Auth::user()->id,
            'modified_by'   => Auth::user()->id,
        ]);

        $BW_MESSAGE = $ticket_no;

        if($ticket)
        {
            $sendArray  = array(
                        'link_id'           => $ticket->ticketid,
                        'type'              => 'new_ticket_user',
                        'ticket_no'         => $ticket_no,
                    );

            //## NOTIFICATION IN ADMIN PANEL
            Notifications::Notify($sendArray);

            $content = [
                'SUBJECT'       =>  $request->subject,
                'CONTENT'       =>  $request->message,
                'EMAIL'         =>  $email,
                'EMAIL-ID'      =>  Auth::user()->getresponseid,
                'USERNAME'      =>  Auth::user()->username,
                'FIRSTNAME'     =>  Auth::user()->first_name,
                'TICKET_NO'     =>  $ticket_no,
                'STATUS'        =>  $status,
                'LOGINURL'      =>  url('login'),
                'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
                'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
                'TYPE'          =>  'TICKET',
                ];

            EmailNotify::sendEmailNotification($content);

            Session::flash('message', 'Success! Ticket '.$BW_MESSAGE.' Created.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('/user/ticket');
    }

    public function viewRecord($id)
    {
        $ticket  = Ticket::findOrFail($id);
        $user_action_statuss = array(''=> '--Status--');
      
        if($ticket->user_status == 'closed')
        {
            $user_action_statuss['reopen'] = 'Reopen';
        }
        if($ticket->user_status != 'closed')
        {
            $user_action_statuss['closed'] = 'Closed';
        }

        $replys = TicketReply::leftJoin('users', 'ticket_replys.created_by', '=', 'users.id')
        ->where('ticket_replys.ticketid',$ticket->ticketid)
        ->select('ticket_replys.*','users.username','users.last_name','users.first_name')
        ->get();
        $statuss = myCustome::getTicketSupportUserStatus();
        Notifications::readNotification('ticket_reply',$ticket->ticketid);

       return view('users.pages.view-ticket', compact('ticket','replys','statuss','user_action_statuss'));
    }

    public function adminViewRecord($id)
    {
        $ticket = Ticket::join('users', 'tickets.userid', '=', 'users.id')
            ->where('tickets.ticketid',$id)
            ->select('tickets.*','users.username','users.email','users.phone','users.last_name','users.first_name')
            ->first();

       Notifications::readNotification('new_ticket_user',$id);

       if(count($ticket) > 0)
        {
            $replys = TicketReply::join('users', 'ticket_replys.created_by', '=', 'users.id')
            ->where('ticket_replys.ticketid',$id)
            ->select('ticket_replys.*','users.username','users.last_name','users.first_name')->get();
            $statuss = myCustome::getTicketSupportStatus();
            unset($statuss['new']);
            $display_statuss = myCustome::getTicketSupportUserStatus();
            return view('admin.pages.view-ticket', compact('ticket','replys','statuss','display_statuss'));
        }
        else
        {
            abort(404);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $data = Ticket::findOrFail($id);
        $data->delete();
        TicketReply::where('ticketid', $id)->delete();
        echo json_encode(array('msg' => 'sucess' ,'id' => $id ));
    }

    public function close(Request $request)
    {
        $msg  = 'error';
        $values = $request->values;
        for ($i=0; $i < count($values); $i++) 
        { 
            Ticket::where('ticketid', $values[$i])
          ->update(['status' => 'closed','modified_by' => Auth::user()->id ]);   
        }

        $msg = 'success';
        echo json_encode(array('msg' => $msg));
    }
}
