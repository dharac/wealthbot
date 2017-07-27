<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class MailManagement extends Model
{
	protected $table = 'mail_management';
	protected $primaryKey = 'mailid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['subject', 'body','sanitiz_str','status', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
