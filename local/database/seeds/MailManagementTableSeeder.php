<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\MailManagement;

class MailManagementTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Your account has been successfully created.</p><p>Please click the below link to activate your account;</p><p>Email : [EMAIL]</p><p>Copy and paste this link to your browser for active your account:</p><p>[VERIFYURL]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Verify your e-mail address",
                'body'    		=> $body,
                'sanitiz_str'   => "verify-your-e-mail-address",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        $body = '<p>Dear [FIRSTNAME], Welcome to the [SITENAME]!</p><p><strong>Thank-you for your trust and consideration:</strong></p><p><a href="'.config('services.SITE_DETAILS.SITE_VIDEO1').'" target="_blank">'.config('services.SITE_DETAILS.SITE_VIDEO1').'</a></p><p><strong>[SITENAME]&nbsp;looks forward to helping you LEVERAGE... here&#39;s HOW:</strong></p><p><a href="'.config('services.SITE_DETAILS.SITE_VIDEO2').'" target="_blank">'.config('services.SITE_DETAILS.SITE_VIDEO2').'</a></p><p><strong>A few helpful hints others have found useful...</strong></p><p><a href="'.config('services.SITE_DETAILS.SITE_DEPOSIT_PDF').'" target="_blank">'.config('services.SITE_DETAILS.SITE_DEPOSIT_PDF').'</a></p><p><strong>Your custom referral link can be found&nbsp;inside &quot;<a href="'.URL('user/referral').'">My Referrals</a>&quot;...</strong></p><p>[SITENAME] likes to these questions, daily:&nbsp;<em>How did we get to be so lucky?&nbsp;&nbsp;And what more is possible, NOW?</em></p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Welcome to the WealthBot!  Account Information",
                'body'    		=> $body,
                'sanitiz_str'   => "welcome-to-the-wealthbot-account-information",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>You are receiving this email because we received a password reset request for your account.</p><p>Copy and paste this link to your browser for Login:</p><p>[RESETURL]</p><p>If you did not request a password reset, no further action is required.</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Reset Password",
                'body'    		=> $body,
                'sanitiz_str'   => "reset-password",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Your Profile has been successfully updated.</p><p>Please check you profile and account detail.</p><p>Copy and paste this link to your browser for Login:</p><p>[LOGINURL]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Profile Updated",
                'body'    		=> $body,
                'sanitiz_str'   => "profile-updated",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Thank You for your private loan deposit with us.</p><p>For your convenience, we have included a copy of your private loan deposit below.</p><p>Private Loan Deposit Amount : [AMOUNT] USD</p><p>Plan Name : [PLAN_NM]</p><p>Payment Through : [PAYMENT_THROUGH]</p><p>Transaction Id : [TRANS_ID]</p><p>Copy and paste this link to your browser for Login:</p><p>[LOGINURL]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Private Loan Deposit Information",
                'body'    		=> $body,
                'sanitiz_str'   => "private-loan-deposit-information",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Your payout request made successfully.</p><p>For your convenience, we have included a details of payout request below.</p><p>Payout Amount : [TXTAMT] USD</p><p>Payout request date : [WITHDRAWDATE]</p><p>Transaction Id : [TRANS_ID]</p><p>Copy and paste this link to your browser for Login:</p><p>[LOGINURL]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Withdrawal Information.",
                'body'    		=> $body,
                'sanitiz_str'   => "withdrawal-information",
                'status'        => "inactive",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
            ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Congratulations and welcome to the [SITENAME] Program! You have been approved for [SITENAME]. To access your [SITENAME] account, please click on the following link and enter your email and password as provided below</p><p>The following are your login details</p><p>Email : [EMAIL]</p><p>Your Referral ID : [REFERRAL_ID]</p><p>Your Referral Link : [REFERRAL_LINK]</p><p>Copy and paste this link to your browser for Login:</p><p>[LOGINURL]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Welcome Mail",
                'body'    		=> $body,
                'sanitiz_str'   => "welcome-mail",
                'status'        => "inactive",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Congratulations and welcome to the [SITENAME] ! Your Private Loan Deposit has made successfully in [SITENAME].</p><p>The following are your deposit details.</p><p>Amount : [AMOUNT]</p><p>Payment Through : [PAYMENT_THROUGH]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Private Lending Deposit",
                'body'    		=> $body,
                'sanitiz_str'   => "private-lending-deposit",
                'status'        => "inactive",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Welcome to the [SITENAME] !<br /><br />This is to confirm you that your private loan deposit has made successfully in [SITENAME].</p><p>The following are your private lending deposit details</p><p>Amount : [AMOUNT]</p><p>Payment Through : [PAYMENT_THROUGH]</p><p>Copy and paste this link to your browser for Login:</p><p>[LOGINURL]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Private Lending Deposit Made",
                'body'    		=> $body,
                'sanitiz_str'   => "private-lending-deposit-made",
                'status'        => "inactive",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Your payout request made successfully.</p><p>For your convenience, we have included details of the payout below.</p><p>Payout Amount : $ [TXTAMT] USD</p><p>Payout date : [WITHDRAWDATE]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Withdrawal",
                'body'    		=> $body,
                'sanitiz_str'   => "withdrawal",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>We have received a request from your User ID [FIRSTNAME]. This is a double protection email to cross check the authenticity of this operation.</p><p>You are required to enter the code below in the back office to verify this operation.</p><p>The following are your verification code</p><p>Verification code : [VERIFICATION_CODE]</p><p>IP Addres : [IP_ADDR]</p><p>Date &amp; Time : [DATE_NOW]</p>';
        MailManagement::create(array(
                'subject'       => "Withdraw code",
                'body'    		=> $body,
                'sanitiz_str'   => "withdraw-code",
                'status'        => "inactive",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));

        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Ticket From [SITENAME]</p><p>Ticket Number : [TICKET_NO]</p><p>Subject: [SUBJECT]</p><p>Status: [STATUS]</p><p>Message: [CONTENT]</p>';
        MailManagement::create(array(
                'subject'       => "Ticket",
                'body'    		=> $body,
                'sanitiz_str'   => "ticket",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));


        $body = '<p>Dear&nbsp;[FIRSTNAME],</p><p>Indicates an empty or non-real Bitcoin Wallet Address</p><p>Copy and paste this link to your browser for update your profile:</p><p>[PROFILELINK]</p><p>If you have any questions about your account or any other matter, please feel free to contact us at [ADMINMAIL]</p>';
        MailManagement::create(array(
                'subject'       => "Indicates an empty or non-real Bitcoin Wallet Address",
                'body'          => $body,
                'sanitiz_str'   => "indicates_an_empty_or_non_real_bitcoin_wallet_address",
                'status'        => "active",
                'updated_at'    => $now,
                'created_at'    => $now,
                'created_by'    => 1,
                'modified_by'   => 1
        ));
    }
}
