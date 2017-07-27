<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class PaymentPeriod extends Model
{
    protected $table = 'payment_period';
    protected $primaryKey = 'pay_period_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['period', 'status','created_by', 'modified_by', 'created_at', 'updated_at'];
}
