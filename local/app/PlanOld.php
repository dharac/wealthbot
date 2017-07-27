<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class PlanOld extends Model
{
    protected $table = 'plan_old';
    protected $primaryKey = 'planid_old';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['planid', 'plan_name', 'spend_min_amount', 'spend_max_amount', 'profit', 'interest_period_type', 'plan_status', 'nature_of_plan', 'founder', 'duration', 'duration_time', 'status', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public static function insertData($data = null)
    {
        if($data)
        {
            $insert = PlanOld::create([
                'planid'                    => $data->planid,
                'plan_name'                 => $data->plan_name,
                'spend_min_amount'          => $data->spend_min_amount,
                'spend_max_amount'          => $data->spend_max_amount,
                'profit'                    => $data->profit,
                'nature_of_plan'            => $data->nature_of_plan,
                'plan_status'               => $data->plan_status,
                'interest_period_type'      => $data->interest_period_type,
                'duration'                  => $data->duration,
                'duration_time'             => $data->duration_time,
                'founder'                   => $data->founder,
                'new_founder'               => $data->new_founder,
                'status'                    => $data->status,
                'created_by'                => $data->created_by,
                'modified_by'               => $data->modified_by,
            ]);
            return $insert;
        }
    }
}
