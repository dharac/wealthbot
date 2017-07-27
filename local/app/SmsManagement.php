<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SmsManagement extends Model
{
	protected $table = 'sms_management';
	protected $primaryKey = 'smsid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subject', 'body','sanitiz_str','status', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
