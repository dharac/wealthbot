<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class PlanLevel extends Model
{
	protected $table = 'plan_levels';
	protected $primaryKey = 'levelid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['planid', 'level', 'commision', 'created_by', 'modified_by', 'created_at', 'updated_at'];
}
