<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleUserTableSeeder::class);
        $this->call(PlanTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(CoinpaymentTableSeeder::class);
        $this->call(IpnRequestTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PaymentPeriodTableSeeder::class);
        $this->call(GoogleCapchaTableSeeder::class);
        $this->call(MailManagementTableSeeder::class);
    }
}
