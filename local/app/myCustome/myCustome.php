<?php
namespace App\myCustome;
use Carbon\Carbon;
use Auth;
use Session;
use App\Ticket;
use App\GoogleCapcha;
use App\InterestPayment;
use App\LevelCommision;
use App\Referral;
use App\Setting;
use App\Withdraw;
use App\WalletAmountInOut;
use DB;
use File;
use mysqli;
use Storage;
use Zipper;
use Excel;
use App\Deposit;
use App\ReDeposit;
use ZIPARCHIVE;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use DateTimeZone;
use Config;

class myCustome
{
	public static function setSeesionPlan($plan = null , $amount = null)
	{
        session()->put('plan', $plan);
        session()->put('amount', $amount);
	}

	public static function deleteSeesionPlan()
	{
		session()->forget('amount');
        session()->forget('plan');
	}

	public static function natureOfPlan()
	{
		$data = array(
			'1' => 'Withdraw All',
			'2' => 'Withdraw Interest',
			'3' => 'Compounding Rollover',
			'4' => 'Withdrawal Initial Loan Amount',
			'5' => 'Compounding Rollover with Incubation Period',
			);

		return $data;
	}

	public static function bitcointype()
	{
		$data = array(
			'1' => 'BTC',
			'2' => 'ETC',
			'3' => 'ZEC',
			'4' => 'LTC',
			'5' => 'ETH',
			'6' => 'LTCT',
			'7' => 'DASH',
			'8' => 'XMR',
			);

		return $data;
	}

	public static function getAccDate($date = null ,$status = null ,$number = null)
	{
		$day = 0;
		if($status == '1')
		{
			$date->addHour($number);
		}
		else if($status == '2')
		{
			$date->addDay($number);
		}
		else if($status == '3')
		{
			$day = $number * 7;
			$date->addDay($number);
		}
		else if($status == '4')
		{
			$day = $number * 30;
			$date->addDay($day);
		}
		else if($status == '5')
		{
			$day = $number * 365;
			$date->addDay($number);
		}
		
		return $date;
	}

	public static function getFinalDate($date = null ,$status = null ,$number = null)
	{
		$day = 0;
		if($status == '1')
		{
			$date->addHour($number);
		}
		else if($status == '2')
		{
			$date->addDay($number);
		}
		else if($status == '3')
		{
			$day = $number * 7;
			$date->addDay($number);
		}
		else if($status == '4')
		{
			$day = $number * 30;
			$date->addDay($day);
		}
		else if($status == '5')
		{
			$day = $number * 365;
			$date->addDay($number);
		}
		
		return $date;
	}

	public static function getTicketSupportStatus()
	{
		return array(
			'' 				=> '--Status--',
			'new' 			=> 'New',
			'pending' 		=> 'Pending',
			'reopen'        => 'Reopen',
			'inprogress' 	=> 'In Progress',
			'closed'		=> 'Closed'
			);
	}

	public static function getTicketSupportUserStatus()
    {
        return array(
            ''                 				=> '--Status--',
            'new'             				=> 'New',
            'pending'         				=> 'Pending',
            'reopen'         				=> 'Reopen',
            'inprogress'        			=> 'In Progress',
            'awaiting_admin_reply'         	=> 'Awaiting Admin reply',
            'awaiting_your_reply'         	=> 'Awaiting Your reply',
            'closed'        				=> 'Closed'
            );
    }

	public static function getIp()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
    	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    	$remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }
	    return $ip;
	}

	public static function getBrowser() 
	{

	    $user_agent = $_SERVER['HTTP_USER_AGENT'];

	    $browser        =   "Unknown Browser";

	    $browser_array  =   array(
	                            '/msie/i'       =>  'Internet Explorer',
	                            '/firefox/i'    =>  'Firefox',
	                            '/safari/i'     =>  'Safari',
	                            '/chrome/i'     =>  'Chrome',
	                            '/edge/i'       =>  'Edge',
	                            '/opera/i'      =>  'Opera',
	                            '/netscape/i'   =>  'Netscape',
	                            '/maxthon/i'    =>  'Maxthon',
	                            '/konqueror/i'  =>  'Konqueror',
	                            '/mobile/i'     =>  'Handheld Browser'
	                        );

	    foreach ($browser_array as $regex => $value) { 

	        if (preg_match($regex, $user_agent)) {
	            $browser    =   $value;
	        }
	    }

	    return $browser;
	}

	public static function getOS()
	{ 

	    $user_agent = $_SERVER['HTTP_USER_AGENT'];

	    $os_platform    =   "Unknown OS Platform";

	    $os_array       =   array(
	                            '/windows nt 10/i'     =>  'Windows 10',
	                            '/windows nt 6.3/i'     =>  'Windows 8.1',
	                            '/windows nt 6.2/i'     =>  'Windows 8',
	                            '/windows nt 6.1/i'     =>  'Windows 7',
	                            '/windows nt 6.0/i'     =>  'Windows Vista',
	                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
	                            '/windows nt 5.1/i'     =>  'Windows XP',
	                            '/windows xp/i'         =>  'Windows XP',
	                            '/windows nt 5.0/i'     =>  'Windows 2000',
	                            '/windows me/i'         =>  'Windows ME',
	                            '/win98/i'              =>  'Windows 98',
	                            '/win95/i'              =>  'Windows 95',
	                            '/win16/i'              =>  'Windows 3.11',
	                            '/macintosh|mac os x/i' =>  'Mac OS X',
	                            '/mac_powerpc/i'        =>  'Mac OS 9',
	                            '/linux/i'              =>  'Linux',
	                            '/ubuntu/i'             =>  'Ubuntu',
	                            '/iphone/i'             =>  'iPhone',
	                            '/ipod/i'               =>  'iPod',
	                            '/ipad/i'               =>  'iPad',
	                            '/android/i'            =>  'Android',
	                            '/blackberry/i'         =>  'BlackBerry',
	                            '/webos/i'              =>  'Mobile'
	                        );

	    foreach ($os_array as $regex => $value)
	    { 
	        if (preg_match($regex, $user_agent))
	        {
	            $os_platform    =   $value;
	        }
	    }
	    return $os_platform;
	}

	public static function getDevice()
	{
		$tablet_browser = 0;
		$mobile_browser = 0;
			 
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) 
		{
		    $tablet_browser++;
		}
		 
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
		{
		    $mobile_browser++;
		}
		 
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE']))))
		{
		    $mobile_browser++;
		}
		 
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
		    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		    'newt','noki','palm','pana','pant','phil','play','port','prox',
		    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		    'wapr','webc','winw','winw','xda ','xda-');
		 
		if (in_array($mobile_ua,$mobile_agents))
		{
		    $mobile_browser++;
		}
		 
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0)
		{
		    $mobile_browser++;
		    //Check for tablets on opera mini alternative headers
		    $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) 
		    {
		      $tablet_browser++;
		    }
		}

		$isDevice = "";
		if ($tablet_browser > 0) 
		{
		   $isDevice =  'tablet';
		}
		else if ($mobile_browser > 0) 
		{
			$isDevice =  'mobile';
		}
		else 
		{
			$isDevice =  'desktop';
		}
		return $isDevice;
			 
	}

	public static function getBalance($id = null,$type = null)
	{
		$commission                 = WalletAmountInOut::getCommission($id,$type);
		
		if($type == 'all')
		{
			$deposits = Deposit::getActiveDeposit();
			$result = ReDeposit::getPendingCommission($deposits);
		}
		else
		{
			Referral::$cmbUser  = $id;
			Referral::$downlineData = [];
			$referrals  = Referral::getReferralDownlineDataOnlyId($id,1,4);
			$cstArray 	= array();
			if(count($referrals) > 0)
			{
				foreach ($referrals as $key => $value) 
				{
					array_push($cstArray, $key);
				}
			}

			$deposits = Deposit::getActiveDepositUserWise($cstArray);
			$result = ReDeposit::getPendingCommission($deposits,'user',$id);
		}
		
		$data =  array(
				'pending_commission' 		=> $result['pendingCommission'],
				'pending_commission_list' 	=> $result['list'],
				'available_commission' 		=> $commission['commission_in'],
				'withdraw_commission' 	    => $commission['commission_out'],
				'wallet_commission' 	    => $commission['commission_wallet'],
			   );

		return $data;
	}

	public static function availableCommission($id = null,$type = null)
	{
		$available_commission 		= 0;

		if($type == 'all')
		{
			$available_commission 		= LevelCommision::sum('commission');
		}
		else
		{
			$available_commission 		= LevelCommision::where('created_by',$id)->sum('commission');
		}
		return $available_commission;
	}

	public static function withdrawCommission($id = null,$type = null)
	{
		$withdraw_commission 		= 0;
		if($type == 'all')
		{
			$withdraw_commission 		= Withdraw::where('withdraw_type','commission')->sum('amount');
		}
		else
		{
			$withdraw_commission 		= Withdraw::where('created_by',$id)->where('withdraw_type','commission')->sum('amount');
		}
		return $withdraw_commission;
	}

	public static function walletCommission($id = null,$type = null)
	{
		$commission_wallet 		= 0;
		if($type == 'all')
		{
			$commission_wallet  = WalletAmountInOut::where('status','3')->sum('amount');
		}
		else
		{
			$commission_wallet  = WalletAmountInOut::where('created_by',$id)->where('status','3')->sum('amount');
		}
		return $commission_wallet;
	}

	public static function pendingCommission($userLevels = null,$type = null)
	{
		$result 					= array();
		$pending_commission_list 	= array();
		$pending_commission 		= 0;
		$deposits   				= \App\ReDeposit::getDetails('','',$userLevels,'dashboard');

		if(count($deposits['result1']) > 0)
		{
			$sustainability_mode = Setting::getData('sustainability_mode');

			foreach($deposits['result1'] as $deposit)
			{
				if(array_key_exists('data'.$deposit->depositid, $deposits['result2']))
				{
					$singleRecord2 = $deposits['result2']['data'.$deposit->depositid];

					if(strtotime(Carbon::now()) <= strtotime($singleRecord2['endDate']->toDayDateTimeString()))
					{
						$commisionEarn = 0;
						$commisionEarn = LevelCommision::countCommission($sustainability_mode,$singleRecord2['totalInterest'],$singleRecord2['amount'],$singleRecord2['levelCommission']);

						$pending_commission = $pending_commission + $commisionEarn;

						if($type == 'list')
						{
							$temp = array(
							'startdate' 	=> $singleRecord2['startDate'],
							'enddate' 		=> $singleRecord2['endDate'],
							'name' 			=> $deposit->first_name.' '.$deposit->last_name,
							'username'  	=> $deposit->username,
							'amount' 		=> $singleRecord2['amount'],
							'interest' 		=> $singleRecord2['totalInterest'],
							'level' 		=> $singleRecord2['level'],
							'percent' 		=> $singleRecord2['levelCommission'],
							'com_amount' 	=> $commisionEarn,
							'refernceid'    => $singleRecord2['refernceid'],
							);

							array_push($pending_commission_list, $temp);
						}
					}
				}
			}
		}

		$result[0] = $pending_commission;
		$result[1] = $pending_commission_list;

		return $result;
	}

	public static function addOrdinalNumberSuffix($num = null)
	{
    	if (!in_array(($num % 100),array(11,12,13)))
    	{
      		switch ($num % 10) 
      		{
        		// Handle 1st, 2nd, 3rd
        		case 1:  return $num.'st';
        		case 2:  return $num.'nd';
        		case 3:  return $num.'rd';
      		}
    	}
    	return $num.'th';
  	}

  	public static function sanitize($string, $force_lowercase = true, $anal = false) 
  	{
    	$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
                   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
                   "â€”", "â€“", ",", "<", ".", ">", "/", "?");
	    $clean = trim(str_replace($strip, "", strip_tags($string)));
	    $clean = preg_replace('/\s+/', "-", $clean);
	    $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;
	    return ($force_lowercase) ?
	        (function_exists('mb_strtolower')) ?
	            mb_strtolower($clean, 'UTF-8') :
	            strtolower($clean) :
	        $clean;
	}

	public static function emailTemplateMergeVariable($data = null,$name = null)
	{
		$templateVariable = array('[USERNAME]','[EMAIL]','[VERIFYURL]','[ADMINMAIL]','[REFERRAL_ID]','[REFERRAL_LINK]','[LOGINURL]','[AMOUNT]','[PLAN_NM]','[PAYMENT_THROUGH]','[TRANS_ID]','[TXTAMT]','[WITHDRAWDATE]','[SITENAME]','[FIRSTNAME]','[LASTNAME]','[VERIFICATION_CODE]','[IP_ADDR]','[DATE_NOW]','[TICKET_NO]','[SUBJECT]','[STATUS]','[CONTENT]','[RESETURL]','[PROFILELINK]','[MYREFERRALLINK]' ,'[PLAN_NM_OLD]','[PLAN_NM_NEW]','[CUTTOFFDATE]','[OLD_EMAIL]','[NEW_EMAIL]','[REFERRER_FIRSTNAME]','[REFERRER_LASTNAME]','[CC]','[CYCLE_END_DATE]','[COINPAYMENT_MESSAGE]','[OLD_REFERRER_NAME]','[NEW_REFERRER_NAME]','[DEPOSITID]');

		$body = "";
		if(count($data['data']) > 0)
		{
			if($name == 'subject')
			{
				$body = $data['data']->subject;
			}
			 else if($name == 'signature')
            {
                $body = $data['signature'];
                $oldstring = 'vendor/kcfinder/upload';

				if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') 
				{
					$protocol = 'https://'; 
				}
				else
				{
					$protocol = 'http://';
				}

				$projectUrl = $protocol.$_SERVER['SERVER_NAME'];

				if (strpos($body, $oldstring) !== false) 
				{
					$body = str_replace('src="','src="'.$projectUrl.'', $body);
				}
            }
			else
			{
				$body = $data['data']->body;
			}
		}
		for ($i=0; $i < count($templateVariable); $i++)
		{
			if(strpos($body, $templateVariable[$i]) !== false) 
			{
				$without_bracket = str_replace(array( '[', ']' ), '', $templateVariable[$i]);
				if(array_key_exists($without_bracket, $data))
				{
					$body = str_replace($templateVariable[$i],$data[$without_bracket],$body);
				}
			}
		}
		return $body;
	}

	public static function getDbBackup()
	{
		ini_set('max_execution_time', 600);
        ini_set('memory_limit','1024M');
		$tables=false;
		$backup_name=false;
		$name = env('DB_DATABASE');
		$mysqli = new mysqli(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),$name);
		$mysqli->select_db($name);
		$mysqli->query("SET NAMES 'utf8'");
		$queryTables = $mysqli->query('SHOW TABLES');
		
		while($row = $queryTables->fetch_row())
		{ 
			$target_tables[] = $row[0];
		}
		if($tables !== false) 
		{
			$target_tables = array_intersect( $target_tables, $tables);
		}
		$content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
		foreach($target_tables as $table)
		{
			if (empty($table))
			{ 
				continue;
			}
			$result	= $mysqli->query('SELECT * FROM `'.$table.'`');
			$fields_amount=$result->field_count;
			$rows_num= count($result);
			$res = $mysqli->query('SHOW CREATE TABLE '.$table);
			$TableMLine=$res->fetch_row();
			$content .= "\n\n".$TableMLine[1].";\n\n";   
			$TableMLine[1]=str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `',$TableMLine[1]);
			for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0)
			{
				while($row = $result->fetch_row())
				{ 
					//when started (and every after 100 command cycle):
					if ($st_counter%100 == 0 || $st_counter == 0 )	{$content .= "\nINSERT INTO ".$table." VALUES";}
						$content .= "\n(";    for($j=0; $j<$fields_amount; $j++){ $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
						if (isset($row[$j])){$content .= '"'.$row[$j].'"' ;}  
						else{$content .= '""';}	   
						if ($j<($fields_amount-1)){$content.= ',';}   }        $content .=")";
					//every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
					if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) {$content .= ";";} else {$content .= ",";}	$st_counter=$st_counter+1;
				}
			} 
			$content .="\n\n\n";
		}
		$content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

		$timeNow 			= str_slug(Carbon::now()->toDayDateTimeString(), '-');
		$backup_name 		= config('services.SITE_DETAILS.SITE_NAME').'_Database_Backup_'.$timeNow.'.txt';
		$backup_name_zip 	= config('services.SITE_DETAILS.SITE_NAME').'_Database Backup_'.$timeNow.'.zip';


		// $foldernm = 'login3_code_db_backup/sql';
 		//$path_storage = $_SERVER["DOCUMENT_ROOT"]."/".$foldernm."/";
		$path_storage =  storage_path('/upload/sql/');
		$filenm = "";
		if(File::isDirectory($path_storage))
		{	
			$infile = 'databasebacup.sql';
			file_put_contents($infile, $content);

			$password = Setting::getData('sql_zip_password');
			
			$outfile = 'databasebacup.zip';

			@system("zip -P $password $outfile $infile");

			if(!copy($outfile, $path_storage.$backup_name_zip))
			{
				echo "failed to copy $file...\n";
			}

			File::delete($outfile);
			File::delete($infile);
			$filenm = $path_storage.$backup_name_zip;
		}
		return $filenm;
	}

	public static function getBackupMyCode()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','1024M');
        $password = Setting::getData('sql_zip_password');
        $path = realpath('');
        $files = glob($path.'*');
        $files = $files[0];
        $timeNow                = str_slug(Carbon::now()->toDayDateTimeString(), '-');
        $backup_name_zip        = config('services.SITE_DETAILS.SITE_NAME').'_Code_Backup_'.$timeNow.'.zip';
        $filenm = "";

		//$foldernm = $_SERVER["DOCUMENT_ROOT"].'/login3_code_db_backup/code/';
		$foldernm = '/home/wealth55/public_html'.'/login3_code_db_backup/code/';
		$pass_protected_file = $foldernm.'password_protected_code_backup.zip';
           
        if(File::isDirectory($foldernm))
        {
            Zipper::make($pass_protected_file)->add($files)->close();
            $filenm = $pass_protected_file;
        }
        $backup_name_zip = $foldernm.$backup_name_zip;
        @system("zip -P $password $backup_name_zip $filenm");
        unlink($filenm);
        return $filenm;
    }

	// public static function getBackupMyCode()
	// {
	// 	ini_set('max_execution_time', 600);
	// 	ini_set('memory_limit','1024M');
	// 	$password = Setting::getData('sql_zip_password');
	// 	$path = realpath('');
 //        $files = glob($path.'*');
 //        $files = $files[0];
 //        $timeNow            	= str_slug(Carbon::now()->toDayDateTimeString(), '-');
 //        $backup_name_zip        = config('services.SITE_DETAILS.SITE_NAME').'_Code_Backup_'.$timeNow.'.zip';
        
 //        //$path_storage =  storage_path('/upload/code/');
 //        $path_storage = $_SERVER["DOCUMENT_ROOT"].'/login3_code_db_backup/code/';
	// 	$filenm = "";
	// 	if(File::isDirectory($path_storage))
	// 	{
	// 		Zipper::make($path_storage.$backup_name_zip)->add($files)->close();
	// 		$filenm = $path_storage.$backup_name_zip;

	// 	}
	// 	return $filenm;
	// }

	public static function bitCoinAddressValidate($address = null)
	{
        $decoded = myCustome::decodeBase58($address);
 
        $d1 = hash("sha256", substr($decoded,0,21), true);
        $d2 = hash("sha256", $d1, true);
 
        if(substr_compare($decoded, $d2, 21, 4))
        {
        	return false;
            //throw new \Exception("bad digest");
        }
        return true;
	}

	public static function decodeBase58($input = null) 
	{
        $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";
 
        $out = array_fill(0, 25, 0);
        for($i=0;$i<strlen($input);$i++)
        {
                if(($p=strpos($alphabet, $input[$i]))===false)
                {
                	break;
                    return false;
                }
                $c = $p;
                for ($j = 25; $j--; ) 
                {
                        $c += (int)(58 * $out[$j]);
                        $out[$j] = (int)($c % 256);
                        $c /= 256;
                        $c = (int)$c;
                }
                if($c != 0)
                {
                    break;
                    return false;
                }
        }
 
        $result = "";
        foreach($out as $val)
        {
                $result .= chr($val);
        }
        return $result;
	}

	public static function withdrawTypeStatus()
	{
		return array( 
			'deposit' 		=> 'From Deposit Ledger',
			'interest' 		=> 'From Interest Ledger',
			'commission' 	=> 'From Commission Ledger',
			'initial' 		=> 'Initial Deposit Out Ledger',
			'wallet' 		=> 'From Wallet',
			);
	}

	public static function walletTypeStatus()
	{
		return array(
			'1' 						=> 'From Deposit',
			'2' 						=> 'From Interest',
			'3' 						=> 'From Commission',
			'4' 						=> 'From Initial Deposit Out',
			'redeposit' 				=> 'ReDeposit',
			'withdraw' 					=> 'Withdrawal',
			'redeposit_another_user' 	=> 'Redeposit For Another User',
			);
	}
	
	public static function Excel($data = null,$filenm = null,$csvType = null)
	{
		$comapny  = config('services.SITE_DETAILS.SITE_NAME');
        $excelfileNm = $filenm.' | '.$comapny.'';
        
    	Excel::create($excelfileNm, function($excel) use ($data)
        {
            $comapny  = config('services.SITE_DETAILS.SITE_NAME');

            $excel->setTitle($comapny);
            $excel->setCreator('Ashis Parmar')->setCompany($comapny);
            $excel->setDescription($comapny);

            $excel->sheet('sheet1', function($sheet) use ($data) 
            {
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->row(1, function($row) {
                    $row->setBackground('#6cc788');
                    $row->setFontSize('12');
                });
            });

        })->download($csvType);
	}

	public static function randomString()
	{
		$length = 10;
		$str = "";
		$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) 
		{
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}
		$str = strtoupper($str);
		return $str;
	}

	public static function setHeaderFooter($content = null,$subject = null)
	{
			$htmlData = '';
			$htmlData .= '<!DOCTYPE html>';
			$htmlData .= '<html lang="en">';
			$htmlData .= '<head>';
			$htmlData .= '<meta charset="utf-8">';
			$htmlData .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
			$htmlData .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
			$htmlData .= '<title>'.$subject.'</title>';
			$htmlData .= '<style type="text/css">';
			$htmlData .='#mainTitle { width: 100%; padding: 35px; background: #f1f1f1; }';
			$htmlData .='#mainTitle h1 { margin: 0; padding: 0; font-size: 28px; font-weight: 700; text-transform: capitalize; text-align: center; color: #444; }';
			$htmlData .='#mainTitle h1>a { text-decoration: none; color: inherit; }';
			$htmlData .='.contentArea { padding: 20px; width: 80%; margin: auto; }';
			$htmlData .= '.contentArea h5{ font-size: 18px; font-weight: 700; margin: 20px 0; color: #74787e; }';
			$htmlData .= '.contentArea p { font-size: 14px; line-height: 15px; margin:  12px auto; }';
			$htmlData .= '#mainFooter { padding: 20px;text-align: center;font-size: 12px;line-height: 24px;background: #f1f1f1; }';
			$htmlData .= '#mainFooter a {color: #6887ff; margin: 0 5px; }';
			$htmlData .= '#mainFooter p { margin: 0px 0; }';
			$htmlData .= '#mainFooter p b{ font-weight: 700;color: #74787e; }';
			$htmlData .= '@media screen and (max-width: 991px ) { .contentArea { width: 100%; } }';
		    $htmlData .= '</style>';
		  	$htmlData .= '</head>';
		  	$htmlData .='<body>';
		  	$htmlData .= '<header id="mainTitle"><h1>'.config('app.name').'</h1></header>';
		 	$htmlData .='<div class="contentArea">'.$content.'</div>';
		 	$htmlData .='<footer id="mainFooter"><p>All communications are only per the existing agreement at <a href="'.config('services.SITE_DETAILS.SITE_AGGREMENT').'" target="_blank">'.config('services.SITE_DETAILS.SITE_AGGREMENT_TEXT').'</a></p><p>Video Links <a href="'.config('services.SITE_DETAILS.SITE_VIDEO1').'" target="_blank">'.config('services.SITE_DETAILS.SITE_VIDEO1_TEXT').'</a> <a href="'.config('services.SITE_DETAILS.SITE_VIDEO2').'" target="_blank">'.config('services.SITE_DETAILS.SITE_VIDEO2_TEXT').'</a></p><p><a href="'.config('services.SITE_DETAILS.SITE_DEPOSIT_PDF').'" target="_blank">'.config('services.SITE_DETAILS.SITE_DEPOSIT_TEXT').'</a><a href="'.config('services.SITE_DETAILS.SITE_FOOTER_LINK_PRESENTATION').'" target="_blank">'.config('services.SITE_DETAILS.SITE_FOOTER_TEXT_PRESENTATION').'</a><a href="'.config('services.SITE_DETAILS.SITE_FOOTER_LINK_COMPLIANCE').'" target="_blank">'.config('services.SITE_DETAILS.SITE_FOOTER_TEXT_COMPLIANCE').'</a><a href="'.config('services.SITE_DETAILS.SITE_FOOTER_LINK_FAQ').'" target="_blank">'.config('services.SITE_DETAILS.SITE_FOOTER_TEXT_FAQ').'</a></p><p>&copy; '.date('Y').' '.config('app.name').'. All rights reserved.</p></footer>';
		  	$htmlData .='</body>';
			$htmlData .='</html>';
			return $htmlData;
	}

	public static function dispayTimeStamp($timeStamp = null)
	{
		$date = $timeStamp;
		$date->setTimezone(new DateTimeZone(Config::get('app.timezone_display')));
		return $date;
	}

	public static function getServerTime()
	{
		$timeNow = DB::select("select @now := now() as time_now");
        $timezone = DB::select("SELECT IF(@@global.time_zone = 'SYSTEM', @@global.system_time_zone, @@global.time_zone) AS time_zone");

        return array(
        	'timezone' => $timezone[0]->time_zone,
        	'timestamp' => $timeNow[0]->time_now
        	);
	}

	public static function SinchSms($phoneno = null,$bodyMessage = null)
	{
		$bodyMessage = str_replace('\r\n',PHP_EOL,$bodyMessage);
		
		$key 		= env('SINCH_SMS_KEY');
		$secret 	= env('SINCH_SMS_SECRET');
		$from 		= env('SINCH_SMS_FROM');

		$user = "application\\" . $key . ":" . $secret;
		$message = array("message"=> $bodyMessage,"from" => $from);
		$data = json_encode($message);
		$ch = curl_init('https://messagingapi.sinch.com/v1/sms/' . $phoneno);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_USERPWD,$user);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$result = curl_exec($ch);
		if(curl_errno($ch))
		{    
			return 'Curl error: ' . curl_error($ch);
		} 
		else
		{
			return  $result;
		}
		curl_close($ch);
	}

	public static function bitcoinInDollar($crypto,$currency)
	{
		$bitcoin_in_dollar = 0;
		$pair = $crypto."-".$currency;

		$url = "https://api.cryptonator.com/api/ticker/".$pair;
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$contents = curl_exec($ch);
		$contents =  json_decode($contents, true);
		$bitcoin_in_dollar = $contents['ticker']['price'];
		
		return $bitcoin_in_dollar;
	}
}