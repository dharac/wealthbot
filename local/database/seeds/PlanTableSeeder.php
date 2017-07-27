<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Plan;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $now = Carbon::now();

        Plan::create(array(
			'plan_name'         	=> "COMPOUNDED",
			'spend_min_amount'    	=> 300.00,
			'spend_max_amount'      => 50000.00,
			'profit'    			=> 30.00,
			'interest_period_type'  => 4,
			'plan_status'  			=> 0,
			'nature_of_plan'  		=> 3,
			'founder' 				=> 0,
			'duration'   			=> '0',
			'duration_time'			=> 1,
			'status'   				=> 'active',
			'created_by'   			=> 1,
			'modified_by'   		=> 1,
			'created_at'   			=> $now,
			'updated_at'   			=> $now
            ));

        Plan::create(array(
			'plan_name'         	=> "INTEREST OUT",
			'spend_min_amount'    	=> 300.00,
			'spend_max_amount'      => 50000.00,
			'profit'    			=> 30.00,
			'interest_period_type'  => 4,
			'plan_status'  			=> 0,
			'nature_of_plan'  		=> 2,
			'duration'   			=> '0',
			'founder' 				=> 0,
			'duration_time'			=> 1,
			'status'   				=> 'active',
			'created_by'   			=> 1,
			'modified_by'   		=> 1,
			'created_at'   			=> $now,
			'updated_at'   			=> $now
            ));

        Plan::create(array(
			'plan_name'         	=> "ALL OUT",
			'spend_min_amount'    	=> 300.00,
			'spend_max_amount'      => 50000.00,
			'profit'    			=> 30.00,
			'interest_period_type'  => 4,
			'plan_status'  			=> 1,
			'nature_of_plan'  		=> 1,
			'duration'   			=> '001',
			'founder' 				=> 0,
			'duration_time'			=> 4,
			'status'   				=> 'active',
			'created_by'   			=> 1,
			'modified_by'   		=> 1,
			'created_at'   			=> $now,
			'updated_at'   			=> $now
            ));


        Plan::create(array(
			'plan_name'         	=> "TEST $1 PLAN",
			'spend_min_amount'    	=> 1.00,
			'spend_max_amount'      => 3.00,
			'profit'    			=> 1,
			'interest_period_type'  => 2,
			'plan_status'  			=> 1,
			'nature_of_plan'  		=> 1,
			'founder' 				=> 0,
			'duration'   			=> '001',
			'duration_time'			=> 4,
			'status'   				=> 'active',
			'created_by'   			=> 1,
			'modified_by'   		=> 1,
			'created_at'   			=> $now,
			'updated_at'   			=> $now
            ));
    }
}
