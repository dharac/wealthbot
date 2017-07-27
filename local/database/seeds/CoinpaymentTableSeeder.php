<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Coinpayment;

class CoinpaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        Coinpayment::create(array(
                'merchant_id'   => "4665085fa43b276a89dfc61beabb2cd6",
                'public_id'    	=> "5aaa19572d9916d70520e9af9e7559dfb4dc6bc467e36f888893e4bd7019cc3b",
                'private_id'    => "4182fbF7eA54BF443C7135a028ecf1Bfa8b1661967a24cfE8F71D84aA5e5b266",
                'ipn_secret'    => "a*b1c%",
                'ipn_email'     =>  "ashish@webmechanic.in",
                'status'        =>  "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        Coinpayment::create(array(
                'merchant_id'   => "ca9532c241bf663380ad22ff54fcf9c5",
                'public_id'     => "2ffe1a52e4c79ef678b392a60efdfc9c46bf50e7036b727cde0b312ed72dd82b",
                'private_id'    => "6BD9d463Eac6Fc5bAF620a43897cf7a3d048D3a6C37fb1Cfb3b31a749fe2A94b",
                'ipn_secret'    => "1MVhC9fjeCVvGdh7xemH72itHXF3U3gpuS",
                'ipn_email'     =>  "CoinPayments.IPN@WealthBot.ME",
                'status'        =>  "inactive",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));
    }
}
