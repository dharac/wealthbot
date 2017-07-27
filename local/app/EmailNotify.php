<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Mail;
use App\Mail\InviteFriend;
use App\Mail\EmailUser;
use App\Mail\CustomeEmail;
use App\Mail\MassEmailUser;
use App\Mail\DatabaseBackupEmail;
use App\MailManagement;
use App\GetResponse;
use App\myCustome\myCustome;
use App\User;

class EmailNotify extends Model
{
	public static function sendEmailNotification($content = null)
    {
        ini_set('max_execution_time', 120);
        
        $signature              = MailManagement::findOrFail(14);
        $content['signature']   = $signature->body;
        $content['BCC']         = array('teamglobalna@gmail.com',config('services.SITE_DETAILS.SITE_ADMIN_EMAIL'));

        if(array_key_exists("EMAIL",$content) && array_key_exists("EMAIL-ID",$content))
        {
            $userCheckEmailId = User::where('email',$content['EMAIL'])->select('id','getresponseid','first_name')->first();
            if(count($userCheckEmailId) > 0)
            {
                if($userCheckEmailId->getresponseid == "")
                {
                    $name = ucwords(strtolower($userCheckEmailId->first_name));
                    $acon = GetResponse::addContact(array('name' =>  $name, 'email' => $content['EMAIL']));
                    $contact = GetResponse::getContact($content['EMAIL']);
                    if(count($contact) > 0)
                    {
                        if(trim(strtolower($contact[0]['email'])) == trim(strtolower($content['EMAIL'])))
                        {   
                            User::where('id', $userCheckEmailId->id)
                            ->update(['getresponseid' => $contact[0]['contactId']]);
                            $content['EMAIL-ID']        = $contact[0]['contactId'];
                        }
                    }
                }
            }
        }

        if(\App::environment('local'))
        {
            $content['BCC']     =   array('ashish@webmechanic.in');
            $content['CC']      =   'ashish@webmechanic.in';
            $content['EMAIL']   =   'ashish@webmechanic.in';
        }
        
        if($content['TYPE'] == 'VERIFY')
        {
            $mailManagement  = MailManagement::findOrFail(1);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
    	else if($content['TYPE'] == 'WELCOME')
    	{
            $mailManagement  = MailManagement::findOrFail(2);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                // if($content['CC'] != "")
                // {
                //     Mail::to($content['EMAIL'])->cc($content['CC'])->bcc($content['BCC'])->send(new CustomeEmail($content));
                // }
                // else
                // {
                //     Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));   
                // }
            }
    	}
        else if($content['TYPE'] == 'RESET-PASSWORD')
        {
            $mailManagement  = MailManagement::findOrFail(3);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'PROFILE')
        {
            $mailManagement  = MailManagement::findOrFail(4);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                //$sent = Mail::to($content['EMAIL'],$content['FIRSTNAME'])->bcc($content['BCC'])->send(new CustomeEmail($content));
                EmailNotify::sendEmailViaGetResponse($content);
            }
        }
        else if($content['TYPE'] == 'DEPOSIT-AMOUNT')
        {
            $mailManagement  = MailManagement::findOrFail(5);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'WITHDRAW-COMMISSION')
        {
            $mailManagement  = MailManagement::findOrFail(6);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'WITHDRAW-WALLET')
        {
            $mailManagement  = MailManagement::findOrFail(20);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'PLAN-CHANGE')
        {
            $mailManagement  = MailManagement::findOrFail(7);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'ADMIN-EMAILCHANGE')
        {
            $mailManagement  = MailManagement::findOrFail(8);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'BITCOIN-HELP')
        {
            $mailManagement  = MailManagement::findOrFail(9);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'WITHDRAW')
        {
            $mailManagement  = MailManagement::findOrFail(10);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'BITCOIN-CHANGE')
        {
            $mailManagement  = MailManagement::findOrFail(11);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'TICKET')
        {
            $mailManagement  = MailManagement::findOrFail(12);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'TICKET-REPLY')
        {
            $mailManagement  = MailManagement::findOrFail(18);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'EMPTY-BITCOIN')
        {
            $mailManagement  = MailManagement::findOrFail(13);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'REFERRER-CHANGE')
        {
            $mailManagement  = MailManagement::findOrFail(15);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                // if($content['CC'] != "")
                // {
                //     Mail::to($content['EMAIL'])->cc($content['CC'])->bcc($content['BCC'])->send(new CustomeEmail($content));
                // }
                // else
                // {
                //     Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
                // }
            }
        }
        else if($content['TYPE'] == 'PAYMENT-CANCELLED')
        {
            $mailManagement  = MailManagement::findOrFail(16);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'WAITING-BYUER-FUND')
        {
            $mailManagement  = MailManagement::findOrFail(17);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }
        else if($content['TYPE'] == 'USER-DELETED')
        {
            $mailManagement  = MailManagement::findOrFail(21);
            if($mailManagement->status == 'active')
            {
                $content['data'] = $mailManagement;
                EmailNotify::sendEmailViaGetResponse($content);
                //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new CustomeEmail($content));
            }
        }

        else if($content['TYPE'] == 'EMAIL-USER')
        {
            $finalEmail     = myCustome::setHeaderFooter($content['BODY'],$content['SUBJECT']);
            $send = array(
                'message' => $finalEmail,
                'subject' => $content['SUBJECT'],
                'emailid' => $content['EMAIL-ID'],
            );

            $status = GetResponse::emailGetResponse($send);
            return $status;

            //Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new EmailUser($content));
        }
        else if($content['TYPE'] == 'MASS-EMAIL')
        {
            $subject    = $content['SUBJECT'];
            $body       = $content['BODY'];

            $subject = str_replace("[USERNAME]",$content['USERNAME'],$subject);
            $subject = str_replace("[FIRSTNAME]",$content['FIRSTNAME'],$subject);
            $subject = str_replace("[LASTNAME]",$content['LASTNAME'],$subject);

            $body = str_replace("[USERNAME]",$content['USERNAME'],$body);
            $body = str_replace("[FIRSTNAME]",$content['FIRSTNAME'],$body);
            $body = str_replace("[LASTNAME]",$content['LASTNAME'],$body);

            $finalEmail     = myCustome::setHeaderFooter($body,$subject);

            $send = array(
                'message' => $finalEmail,
                'subject' => $subject,
                'emailid' => $content['EMAIL-ID'],
            );

            $status = GetResponse::emailGetResponse($send);
            return $status;
        }
        else if($content['TYPE'] == 'INVITE-FRIEND')
        {
            array_push($content['BCC'], $content['yourEmail']);
            Mail::to($content['EMAIL'])->bcc($content['BCC'])->send(new InviteFriend($content));
        }
        else if($content['TYPE'] == 'DATABASE-BACKUP')
        {
            Mail::to($content['EMAIL'])->send(new DatabaseBackupEmail($content));
        }
        else if($content['TYPE'] == 'CODE-BACKUP')
        {
            Mail::to($content['EMAIL'])->send(new DatabaseBackupEmail($content));
        }
    }

    public static function sendEmailViaGetResponse($content = null)
    {
        if($content)
        {
            $getresponseid  = $content['EMAIL-ID'];
            $subject        = myCustome::emailTemplateMergeVariable($content,'subject');
            $body           = myCustome::emailTemplateMergeVariable($content,'body');
            $signature      = myCustome::emailTemplateMergeVariable($content,'signature');
            $body           = $body.$signature;
            $finalEmail     = myCustome::setHeaderFooter($body,$subject);

            $ccemailid = "";
            if(array_key_exists("CC-EMAIL-ID",$content))
            {
                $ccemailid = $content['CC-EMAIL-ID'];
            }

            $send = array(
            'message'   => $finalEmail,
            'subject'   => $subject,
            'emailid'   => $getresponseid,
            'ccemailid' => $ccemailid,
            );

            $status = GetResponse::emailGetResponse($send);
            return $status;
        }
    }
}
