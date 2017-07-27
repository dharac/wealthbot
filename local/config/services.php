<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'SITE_DETAILS' => [
        'SITE_TITLE'                    => 'CMS | Wealthbot',
        'SITE_NAME'                     => 'WealthBot',
        'SITE_DEVELOPER'                => 'Ashish Parmar',
        'EMAIL_SIGN'                    => 'WealthBot Admin',
        'SITE_CREATE_COMPANY'           => 'Vardaam Web Solutions Pvt Ltd (formerly Web Mechanic)',
        'SITE_CREATE_COMPANY_WEBSITE'   => 'http://www.vardaam.com/',
        'SITE_ADMIN_MOBILE'             => '7573-050-753',
        'SITE_ADMIN_EMAIL'              => 'admin@wealthbot.me',
        'VERIFY_EMAIL'                  =>  'NO',

        'SITE_VIDEO1'                   => 'http://QuickIntro.WealthBot.ONLINE',
        'SITE_VIDEO1_TEXT'              => 'Quick Intro',
        'SITE_VIDEO2'                   => 'http://Production.WealthBot.ONLINE',
        'SITE_VIDEO2_TEXT'              => 'How it Works',
        'SITE_AGGREMENT'                => 'http://Agreement.WealthBot.ONLINE',
        'SITE_AGGREMENT_TEXT'           => 'Agreement',
        'SITE_DEPOSIT_PDF'              => 'http://HowtoDeposit.WealthBot.ONLINE',
        'SITE_DEPOSIT_TEXT'             => 'HOW TO START',
        'SITE_FOOTER_LINK'              => 'http://value.wealthbot.online/',
        'SITE_FOOTER_TEXT'              => 'Future Value',
        'SITE_FOOTER_LINK_FAQ'          => 'http://FAQ.WealthBot.ONLINE',
        'SITE_FOOTER_TEXT_FAQ'          => 'FAQ',
        'SITE_FOOTER_LINK_PRESENTATION' => 'http://presentation.wealthbot.online/',
        'SITE_FOOTER_TEXT_PRESENTATION' => 'PRESENTATION',
        'SITE_FOOTER_LINK_COMPLIANCE'   => 'http://compliance.wealthbot.online/',
        'SITE_FOOTER_TEXT_COMPLIANCE'   => 'COMPLIANCE',
        'SITE_LOGO_TEXT'                => 'The Safe Alternative',
        'EMAIL_LOGO_TEXT'               => 'WealthBot - the Safe Alternative',
        'EMAIL_FOOTER_TEXT'             => '"WealthBot - the Safe Alternative" should be in email signature. We need to add the text "-the safe Alternative" as a tag line wherever the word or Logo Wealthbot is found.',
        'SITE_USER_INACTIVE'            => 'Your status with/access to the club has changed by prior written agreement.',
     ],

    'SOCIAL' => [
        'FACEBOOK'          => '',
        'INSTAGRAM'         => '',
        'TWITTER'           => '',
        'YOUTUBE'           => '',
        'PINTEREST'         => '',
        'VK'                => '',
    ],

    'COINPAYMENTS' => [
        'PUBLICKEY'          => '2ffe1a52e4c79ef678b392a60efdfc9c46bf50e7036b727cde0b312ed72dd82b',
        'PRIVATEKEY'         => '6BD9d463Eac6Fc5bAF620a43897cf7a3d048D3a6C37fb1Cfb3b31a749fe2A94b',
    ],

    'DATATABLE' => [
        'PERPAGE'          => '100',
    ],

     'GOOGLE_CREDENTIAL' => [
        'API_KEY'                    => 'AIzaSyC0jbKZ8-09xmlfqlWP7Q7pPI5RSTGU4_g',
     ],

     'SCRIPT' => [
        'VERSION'                    => '9.2',
     ],

     'BACKUP' => [
        'MYSQL_ZIP_PASSWORD'        => 'B^o4hPcg97nV]p[2ysgmVHmeN',
        'ADMIN_CONFIRM_PASSWORD'    => 'mL5Pl@Q82#cho!eLULulWyD',
     ],

     'GETRESPONSE' => [
        'CAMPAIGNID'        => 'TT5RH',
        'FROMID'            => 'TVO2m',
        'EMAIL_NAME'        => 'Welathbot App Email',
        'API_KEY'           => 'aa7613ee638a8819335322b988538597',
     ],

];
