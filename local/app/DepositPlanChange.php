<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class DepositPlanChange extends Model
{
	protected $table = 'deposit_plan_change';
	protected $primaryKey = 'depo_plan_chng';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['old_planid','new_planid','depositid','status','created_by','modified_by','created_at','updated_at'];

    public static function getLastNewRequest()
    {
    	$data = DepositPlanChange::join('plan_m as old_plan', 'old_plan.planid', '=', 'deposit_plan_change.old_planid')
    	->join('plan_m as new_plan', 'new_plan.planid', '=', 'deposit_plan_change.new_planid')
    	->join('users', 'users.id', '=', 'deposit_plan_change.created_by')
        ->select('old_plan.plan_name as old_plan_name','new_plan.plan_name as new_plan_name','users.first_name','users.username','users.id','deposit_plan_change.updated_at','deposit_plan_change.depositid','deposit_plan_change.status')
        ->orderby('deposit_plan_change.created_at','desc')
        ->limit(10)
        ->get();
        return $data;
    }

    public static function getRequest()
    {
        $perPage = config('services.DATATABLE.PERPAGE');

        $data = DepositPlanChange::join('plan_m as old_plan', 'old_plan.planid', '=', 'deposit_plan_change.old_planid')
        ->join('plan_m as new_plan', 'new_plan.planid', '=', 'deposit_plan_change.new_planid')
        ->join('deposit', 'deposit.depositid', '=', 'deposit_plan_change.depositid')
        ->join('users', 'users.id', '=', 'deposit_plan_change.created_by')
        ->select('old_plan.plan_name as old_plan_name','new_plan.plan_name as new_plan_name','users.first_name','users.last_name','users.id','deposit_plan_change.created_at','deposit_plan_change.updated_at','deposit_plan_change.depositid','deposit_plan_change.status','deposit.depositno')
        ->orderby('deposit_plan_change.created_at','desc')
        ->paginate($perPage);

        return $data;
    }

    public static function getRequestUserWise()
    {
        $perPage = config('services.DATATABLE.PERPAGE');

        $data = DepositPlanChange::join('plan_m as old_plan', 'old_plan.planid', '=', 'deposit_plan_change.old_planid')
        ->join('plan_m as new_plan', 'new_plan.planid', '=', 'deposit_plan_change.new_planid')
        ->join('deposit', 'deposit.depositid', '=', 'deposit_plan_change.depositid')
        ->select('old_plan.plan_name as old_plan_name','new_plan.plan_name as new_plan_name','deposit_plan_change.created_at','deposit_plan_change.updated_at','deposit_plan_change.depositid','deposit_plan_change.status','deposit.depositno')
        ->where('deposit_plan_change.created_by',\Auth::user()->id)
        ->orderby('deposit_plan_change.created_at','desc')
        ->paginate($perPage);

        return $data;
    }

    public static function getSingle($depositid)
    {
        $data = DepositPlanChange::join('plan_m as new_plan', 'new_plan.planid', '=', 'deposit_plan_change.new_planid')
        ->select('new_plan.plan_name as new_plan_name','deposit_plan_change.depositid','deposit_plan_change.status','deposit_plan_change.new_planid')
        ->where('deposit_plan_change.depositid',$depositid)
        ->where('deposit_plan_change.created_by',\Auth::user()->id)
        ->orderby('deposit_plan_change.depo_plan_chng','desc')
        ->first();
        return $data;
    }

    public static function insertPlanChange($data = null)
    {
            $old_planid     = $data['old_planid'];
            $new_planid     = $data['new_planid'];
            $depositid      = $data['depositid'];
            $status         = $data['status'];
            $userid         = $data['userid'];

        $insert = DepositPlanChange::create([
                'old_planid'        => $old_planid,
                'new_planid'        => $new_planid,
                'depositid'         => $depositid,
                'status'            => $status,
                'created_by'        => $userid,
                'modified_by'       => $userid,
            ]);

        return $insert;
    }
}
