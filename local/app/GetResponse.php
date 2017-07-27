<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\GetResponse\GetResponse as GetResponseMainClass;
use App\myCustome\myCustome;

class GetResponse extends Model
{
	protected $table 				= 'getresponse';
	protected $primaryKey 			= 'getresid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'bcc', 'campaignId', 'fromFieldId', 'getresponse_api_key', 'created_by', 'modified_by', 'created_at', 'updated_at','status'];

    public static function emailGetResponse($data = null)
	{
		$message 		= $data['message'];
		$subject 		= $data['subject'];
		$emailId 		= $data['emailid'];

		$ccemailid = "";
		if(array_key_exists("ccemailid",$data))
        {
            $ccemailid = $data['ccemailid'];
        }

		$sendEmailsList = array('OEU4K0','klg3RR');
		if (\App::environment('local'))
		{
			$sendEmailsList = array($emailId);
		}
		else
		{
			array_push($sendEmailsList, $emailId);
			if($ccemailid != "")
			{
				array_push($sendEmailsList,$ccemailid);
			}
		}

		$CAMPAIGNID 	= config('services.GETRESPONSE.CAMPAIGNID');
		$FROMID 		= config('services.GETRESPONSE.FROMID');
		$EMAIL_NAME 	= config('services.GETRESPONSE.EMAIL_NAME');
		$API_KEY 		= config('services.GETRESPONSE.API_KEY');

		$getresponse 	= new GetResponseMainClass($API_KEY);
        $result = $getresponse->sendNewsletter(array(
            'campaign'  => array('campaignId' => $CAMPAIGNID),
            'name'      => $EMAIL_NAME,
            "subject"   => $subject,
            "fromField" => array('fromFieldId' => $FROMID),
            "content" => array(
                'html' => $message
            ),
            "sendSettings" => array(
                "selectedContacts" => $sendEmailsList
            ),
        ));

        if(array_key_exists("newsletterId",$result))
        {
            return true;
        }
        else
        {
            return false;
        }
	}

	public static function getContact($query = null)
	{
		$API_KEY 		= config('services.GETRESPONSE.API_KEY');
        $getresponse = new GetResponseMainClass($API_KEY);

		$result = $getresponse->getContacts(array(
			'query' => array(
				'email' => $query,
			),
			'fields' => 'name,email'
		));
		$result = json_decode(json_encode($result), True);
		return $result;
	}

	public static function addContact($data = null)
	{
		$name 		= 	$data['name'];
		$email 		= 	$data['email'];
		$ip 		= 	myCustome::getIp();

		$CAMPAIGNID 	= config('services.GETRESPONSE.CAMPAIGNID');
		$API_KEY 		= config('services.GETRESPONSE.API_KEY');

		$getresponse = new GetResponseMainClass($API_KEY);

		$result = $getresponse->addContact(array(
		    'name'              => $name,
		    'email'             => $email,
		    'dayOfCycle'        => 0,
		    'campaign'          => array('campaignId' => $CAMPAIGNID),
		    'ipAddress'         => $ip,
		));

		$result = json_decode(json_encode($result), True);
		return $result;
	}

	public static function getCampaigns()
	{
		$API_KEY 		= config('services.GETRESPONSE.API_KEY');
        $getresponse 	= new GetResponseMainClass($API_KEY);

		$result = $getresponse->getCampaigns();
		$result = json_decode(json_encode($result), True);
		return $result;
	}

	public static function getCustomField()
	{
		$API_KEY 		= config('services.GETRESPONSE.API_KEY');
        $getresponse 	= new GetResponseMainClass($API_KEY);
		$result 		= $getresponse->getCustomField();
		$result = json_decode(json_encode($result), True);
		return $result;
	}
}

