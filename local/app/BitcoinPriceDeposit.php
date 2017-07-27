<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\myCustome\myCustome;

class BitcoinPriceDeposit extends Model
{
    protected $table = 'bitcoin_price_deposit';
    protected $primaryKey = 'btcid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['btcid', 'depositid', 'status','amount', 'bitcoin_in_dollar', 'created_by', 'modified_by', 'created_at', 'updated_at','bitcoin_currency','currency'];

    public static function InsertSingleRecord($data = null)
    {
        if($data)
        {
            $bitcoin_in_dollar = 0;
            if (array_key_exists("bitcoin_in_dollar",$data))
            {
                $bitcoin_in_dollar = $data['bitcoin_in_dollar'];
            }
            else
            {
                $bitcoin_in_dollar = myCustome::bitcoinInDollar($data['bitcoin_currency'],$data['currency']);
            }
            
            $insert = BitcoinPriceDeposit::create([
                'depositid'             => $data['depositid'],
                'status'                => $data['status'],
                'bitcoin_in_dollar'     => $bitcoin_in_dollar,
                'bitcoin_currency'      => $data['bitcoin_currency'],
                'currency'              => $data['currency'],
                'amount'                => $data['amount'],
                'created_by'            => $data['created_by'],
                'modified_by'           => $data['created_by'],
            ]);
            
            return $insert;
        }
    }
}