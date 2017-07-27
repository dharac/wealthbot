<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $table = 'ticket_replys';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['ticketid','from_mail','to_mail','message','status','created_by','created_dt','modified_dt','user_status'];

}
