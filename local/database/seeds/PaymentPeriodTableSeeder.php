<?php

use Illuminate\Database\Seeder;
use App\PaymentPeriod;
use Carbon\Carbon;

class PaymentPeriodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        PaymentPeriod::create(
            array(
                'period'        => 'Hourly',
                'period_sing'   => 'Hour',
                'status'        => '1',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );
        
        PaymentPeriod::create(
            array(
                'period' 		=> 'Daily',
                'period_sing'   => 'Day',
                'status' 		=> '1',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        PaymentPeriod::create(
            array(
                'period' 		=> 'Weekly',
                'period_sing'   => 'Week',
                'status' 		=> '1',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        PaymentPeriod::create(
            array(
                'period' 		=> 'Monthly',
                'period_sing'   => 'Month',
                'status' 		=> '1',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );

        PaymentPeriod::create(
            array(
                'period' 		=> 'Yearly',
                'period_sing'   => 'Year',
                'status' 		=> '1',
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            )
        );
    }
}
