<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\EmailNotify;
use App\myCustome\myCustome;
use Carbon\Carbon;

class DatabaseBackup extends Model
{
	public static function sendEmail()
	{
    	$email     = config('services.SITE_DETAILS.SITE_ADMIN_EMAIL');
    	$subject   = config('services.SITE_DETAILS.SITE_NAME').' Database Backup '.dispayTimeStamp(Carbon::now())->toDayDateTimeString();
    	$filenm    = myCustome::getDbBackup();

    	$content = [
            'EMAIL'         =>  $email,
            'SUBJECT'      	=>  $subject,
            'MESSAGE'     	=>  'Hello Admin,<br>Your daily Database backup. <br>Please check your attachment.',
            'FILEPATH'      =>  $filenm,
            'LOGINURL'      =>  url('login'),
            'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
            'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
            'TYPE'          =>  'DATABASE-BACKUP',
            ];
    	EmailNotify::sendEmailNotification($content);
	}
}
