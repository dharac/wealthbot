<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class GoogleCapcha extends Model
{
	protected $table = 'google_recaptcha';
	protected $primaryKey = 'capcod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cap_key', 'cap_secret', 'created_by', 'modified_by', 'created_at', 'updated_at', 'status', 'email'];


    public static function googleCapchaStatus()
	{
		$data = GoogleCapcha::where('status','active')->first();
		return $data;
	}
}
