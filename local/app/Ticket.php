<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'ticketid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['userid','subject','message','excerpt','ticket_no','email','phone','status','created_by','modified_by','created_dt','modified_dt','user_status'];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public static function getLastNewTicket()
    {
        $tickets = Ticket::where('tickets.status','new')
            ->orwhere('tickets.status','reopen')
            ->orderby('tickets.updated_at','desc')
            ->limit(10)
            ->get();
        return $tickets;
    }
    
    public static function totalTickets()
    {
        $new                    = Ticket::where('status','new')->count();
        $reopen                 = Ticket::where('status','reopen')->count();
        $inprogress             = Ticket::where('status','inprogress')->count();
        $awaiting_your_reply    = Ticket::where('status','awaiting_your_reply')->count();
        $pending                = Ticket::where('status','pending')->count();
       return array($new,$reopen,$inprogress,$awaiting_your_reply,$pending);
    }

    public static function getTicketNo()
    {
        $orderNo  = Ticket::max('ticketid');
        if($orderNo == null || $orderNo == "")
        {
            $orderNo =  1;
        }
        else
        {
            $orderNo =  1 + $orderNo;
        }
        $letter = chr(rand(65,90));
        $number = rand(1,100);
        $orderNoString = '#WB'.$number.$letter.$orderNo;
        return $orderNoString;
    }
}
