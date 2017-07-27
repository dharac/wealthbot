<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class InterestVariable extends Model
{
	protected $table = 'interest_variable';
	protected $primaryKey = 'varid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['varid','interest','month', 'year', 'planid', 'created_by', 'modified_by', 'created_at', 'updated_at'];
    

    public function plan()
    {
        return $this->belongsTo('App\Plan', 'planid');
    }
}
