<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class DepositCycleCount extends Model
{
	protected $table = 'deposit_cycle_count';
	protected $primaryKey = 'cycleid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cycleid', 'depositid', 'cycle', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public static function insertDepsitCycleCount($cycleData = null)
    {
        $depositid  = $cycleData['depositid'];
        $userid     = $cycleData['userid'];
        $cycle      = $cycleData['cycle'];

        $insert = DepositCycleCount::create([
                'depositid'         => $depositid,
                'cycle'             => $cycle,
                'created_by'        => $userid,
                'modified_by'       => $userid,
            ]);
    }
}
