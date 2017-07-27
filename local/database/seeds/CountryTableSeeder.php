<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$now = Carbon::now();

        Country::create(array(
                'counm'         => "India",
                'cou_prefix'    => "IND",
                'cou_code'      => "+91",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        Country::create(array(
                'counm'         => "Australia",
                'cou_prefix'    => "AUS",
                'cou_code'      => "+07",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));
    }
}
