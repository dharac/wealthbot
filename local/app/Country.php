<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	protected $table = 'country_m';
	protected $primaryKey = 'coucod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['counm','cou_prefix','cou_code','created_by','modified_by','created_dt','modified_dt'];
}
