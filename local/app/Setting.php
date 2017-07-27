<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
	protected $table = 'settings';
	protected $primaryKey = 'setid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['data_key', 'data_value', 'created_by', 'modified_by', 'created_at', 'updated_at'];

    public static function getData($value = null)
    {
    	$setting = Setting::where('data_key',$value)
    	->select('data_value')
    	->orderby('setid','desc')
    	->first();

    	$data_value = "";
    	if(count($setting) > 0)
    	{
    		$data_value = $setting->data_value;
    	}
    	return $data_value;
    }
}
