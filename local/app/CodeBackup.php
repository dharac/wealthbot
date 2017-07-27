<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\myCustome\myCustome;
use Carbon\Carbon;
use Config;
use DB;

class CodeBackup extends Model
{
	public static function getBackupCode()
    {
        myCustome::getBackupMyCode();
        $tmzn = myCustome::getServerTime();
        $timezone 	=  $tmzn['timezone'];
        $timestamp 	=  $tmzn['timestamp'];
        $today 		= dispayTimeStamp(Carbon::now())->toDayDateTimeString();

        $timestamp  = Carbon::parse($timestamp)->format('Y-m-d H:i:s');
		$timestamp 	= Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->toDayDateTimeString();

        $email     = config('services.SITE_DETAILS.SITE_ADMIN_EMAIL');
        $subject   = "Scheduled Code Backup Successful";
        $message   = 'The scheduled source code backup was completed successfully on.['.Carbon::now()->toDayDateTimeString().' '.Config::get('app.timezone_display2').'], ['.$timestamp.' '.$timezone.']';

        $content = [
            'EMAIL'         =>  $email,
            'SUBJECT'       =>  $subject,
            'MESSAGE'    	=>  $message,
            'LOGINURL'      =>  url('login'),
            'ADMINMAIL'     =>  config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'),
            'SITENAME'      =>  config('services.SITE_DETAILS.SITE_NAME'),
            'TYPE'          =>  'CODE-BACKUP',
            ];
        EmailNotify::sendEmailNotification($content);
    }
}
