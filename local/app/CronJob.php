<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\myCustome\myCustome;

class CronJob extends Model
{
	protected $table = 'cron_job';
	protected $primaryKey = 'cronid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['description', 'created_by', 'modified_by','server_timezone','server_time', 'created_at', 'updated_at'];

    public static function InsertCronJob($description = null)
    {
        $tmzn = myCustome::getServerTime();
        
		$insert = CronJob::create([
				'description' 		=> $description,
				'created_by'		=> 1,
				'modified_by'		=> 1,
                'server_timezone'   => $tmzn['timezone'],
                'server_time'       => $tmzn['timestamp'],
		]);

		return $insert;
    }
}
