<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model
{
	protected $table = 'security_question';
	protected $primaryKey = 'secid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['question','created_by','modified_by','created_dt','modified_dt'];
}
