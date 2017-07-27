<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class EmailChangeVerification extends Model
{
	protected $table = 'email_change_verification';
	protected $primaryKey = 'vercod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['userid','new_email','status', 'old_email', 'confirmation_code', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
