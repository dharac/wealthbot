<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Coinpayment extends Model
{
	protected $table = 'coinpayment';
	protected $primaryKey = 'coinid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['merchant_id','public_id','ipn_secret','status','ipn_email','private_id','created_by','modified_by','created_dt','modified_dt'];
}
