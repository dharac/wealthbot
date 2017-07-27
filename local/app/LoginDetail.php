<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\myCustome\myCustome;

class LoginDetail extends Model
{
	protected $table = 'logindetails';
	protected $primaryKey = 'logid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ip','os','browser','status','device','created_by','modified_by','created_dt','modified_dt'];

    public static function userLogin($id = null)
    {
        $status = 'register';
        if($id == null || $id == null )
        {
            $id     = Auth::user()->id;
            $status = 'login';
        }
    	$insert = LoginDetail::create([
					'ip' 				=> myCustome::getIp(),
					'browser' 			=> myCustome::getBrowser(),
					'device' 			=> myCustome::getDevice(),
					'os' 				=> myCustome::getOS(),
                    'status'            => $status,
					'created_by'		=> $id,
					'modified_by'		=> $id,
				]);
    }

    public static function lastLogin()
    {
        $logdetails = LoginDetail::where('created_by',Auth::user()->id)->where('status','login')->count();
        return $logdetails;
    }
}
