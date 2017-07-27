<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table        = 'plan_m';
    protected $primaryKey   = 'planid';
    public static $activePlan   = array('11','12','13','14');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['plan_name', 'spend_min_amount', 'spend_max_amount','nature_of_plan', 'profit','plan_status', 'interest_period_type','status','founder','new_founder','duration', 'duration_time', 'created_by', 'modified_by'];


    public static function getActivePlans()
    {
    	$plans = Plan::join('payment_period', 'payment_period.pay_period_id', '=', 'plan_m.interest_period_type')
        ->join('payment_period as payment_period1', 'payment_period1.pay_period_id', '=', 'plan_m.duration_time')
        ->where('plan_m.status','active')
        ->orderby('plan_m.planid','asc')
        ->select('payment_period.period as periodofProfit','payment_period1.period_sing as periodofDuration','plan_m.*')
        ->get();
        return $plans; 
    }
}
