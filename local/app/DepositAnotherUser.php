<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class DepositAnotherUser extends Model
{
	protected $table       = 'deposit_another_user';
	protected $primaryKey  = 'antid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['antid', 'depositfor', 'depositid', 'amount', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public static function InsertinAnotherUser($data = null)
    {
        if($data)
        {
            $insert = DepositAnotherUser::create([
                    'depositfor'        => $data['depositfor'],
                    'depositid'         => $data['depositid'],
                    'amount'            => $data['amount'],
                    'created_by'        => $data['created_by'],
                    'modified_by'       => $data['created_by'],
                ]);

            return $insert;
        }
    }
}
