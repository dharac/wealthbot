<?php

use Illuminate\Database\Seeder;
use App\GoogleCapcha;
use Carbon\Carbon;

class GoogleCapchaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        GoogleCapcha::create(array(
            'cap_key'    	=> "6LdY6xcUAAAAAJ4Lc85npwZcx-3hmhAG3cFCRtLp",
            'cap_secret'    => "6LdY6xcUAAAAADSp1dBr02OGYXe0aCfDSr_koqXz",
            'email'     	=>  "ashish@webmechanic.in",
            'status'        =>  "inactive",
            'updated_at'    => $now,
            'created_at'    => $now,
            'created_by'    => 1,
            'modified_by'   => 1
        ));
    }
}
