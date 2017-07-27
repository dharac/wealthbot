<?php
Route::get('/', 'HomeController@home');
Route::get('/home', 'HomeController@home');

Route::get('vue', function() {
    return view('vue');
});

Route::get('/news/{id}', 'HomeController@newsSingle');

Auth::routes();

Route::get('/coinpayment','DepositController@autoCoinpayment');

Route::get('/login/register','HomeController@register');
Route::get('/register','HomeController@register');
Route::post('/login/register/store','HomeController@newUserRegister');

Route::get('register/verify/{confirmationCode}','HomeController@confirm');
Route::get('email/verify/{confirmationCode}','HomeController@emailConfirm');

/* SPECIAL  IPN ROUTES DONT CHANGE */
Route::any('/coinpayment/ipn','IpnController@ipnRequestCoinpayment');
Route::get('/payment/success','HomeController@paymentSuccess');
Route::get('/payment/error','HomeController@paymentError');
/* TRACK CONTROLLER */
Route::get('/track/{ref?}', 'TrackController@track');
Route::post('country/code','HomeController@countryCode');

/* Dashboard Routes */
Route::get('/dashboard', 'DashboardController@index');
Route::get('/dashboard/messages', 'DashboardController@getMessages');
Route::get('/countNotify','DashboardController@getNotificationCount');
Route::get('/getNotify','DashboardController@getNotificationMessage');
Route::get('/wallet', 'WalletController@index');

Route::get('/admin/user/password','UserController@password');
Route::get('/admin/user/profile','UserController@profile');
Route::get('/admin/user/profile/update','UserController@profileUpdate');
Route::post('/admin/user/update-profile','UserController@updateProfile');
Route::post('/admin/user/passwordupdt','UserController@update_password');
Route::post('/admin/user/images','UserController@profilePicture');

Route::post('/admin/level-commision/pending/json','LevelCommisionController@userPendingCommission');
Route::post('admin/referral-report/referral-detail','ReferralReportController@referralDetail');

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function() {

Route::post('/chart', 'DashboardController@chart');
Route::post('/amount/move','WalletController@store');
Route::get('/available-payout','WalletController@listData');

Route::get('/deposit','DepositController@index');
Route::get('/deposit/new','DepositController@fromCoinpayment');
Route::get('/deposit/new/wallet','DepositController@fromWallet');
Route::get('/deposit/new/wallet/another','DepositController@fromWalletAnotherUser');
Route::post('/deposit/store','DepositController@storeDeposite');
Route::get('/deposit/view/{id}','DepositController@viewRecord');

Route::get('/deposit/history/plan','DepositPlanChangeController@index');
Route::get('/deposit/change/plan','DepositPlanChangeController@newRecord');
Route::post('/deposit/plan/data','DepositPlanChangeController@getPlanData');
Route::post('/deposit/plan/update','DepositPlanChangeController@updateDeposit');
Route::post('/deposit/plan/cancel','DepositPlanChangeController@cancelRequest');

/*PUBLIC TICKET CONTROLLER */
Route::get('/ticket','TicketController@index');
Route::get('/ticket/new','TicketController@newRecord');
Route::get('/ticket/view/{id}','TicketController@viewRecord');
Route::get('/ticket/edit/{id}','TicketController@editRecord');
Route::post('/ticket/update','TicketController@update');
Route::post('/ticket/store','TicketController@store');

Route::post('/ticket/reply/store','TicketReplyController@store');

Route::get('/referral','ReferralController@index');

Route::get('/level-commision','LevelCommisionController@userLevelCommision');
Route::get('/level-commision/view/{id}','LevelCommisionController@viewRecord');

Route::get('/withdraw','WithdrawController@index');
Route::get('/withdraw/new','WithdrawController@newRecord');
Route::post('/withdraw/store','WithdrawController@storewithraw');
Route::get('/withdraw/view/{id}','WithdrawController@viewRecordUser');

Route::get('/interest-payment','InterestPaymentController@indexUser');
Route::get('/interest-payment/view/{id}','InterestPaymentController@viewRecord');

Route::get('/invite-friend','InviteFriendController@index');
Route::post('/invite-friend/store','InviteFriendController@store');

Route::get('/ledger','RedepositeController@indexUser');

Route::get('/referral-report','ReferralReportController@indexUser');
Route::get('/referral-report/detail','ReferralReportController@detailUser');
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth','role:admin|sub_admin']], function() {

Route::post('/dashboard/data','DashboardController@dashboardData');
Route::post('/dashboard/duplicators','DashboardController@dashboardDuplicators');
Route::post('/dashboard/plan-count','DashboardController@planCount');

Route::get('/wallet-transaction','WalletController@walletTransaction');

/* Page Routes */
Route::get('/page','PageController@index');
Route::get('/page/new','PageController@newRecord');
Route::post('/page/store','PageController@store');
Route::get('/page/edit/{id}','PageController@editRecord');
Route::post('/page/update','PageController@update');
Route::post('/page/delete','PageController@delete');

/* User Routes */
Route::get('/user','UserController@index');
Route::get('/user/new','UserController@newRecord');
Route::post('/user/store','UserController@store');
Route::get('/user/edit/{id}','UserController@editRecord');
Route::match(['get', 'post'],'/user/update','UserController@update');
Route::match(['get', 'post'],'/user/delete','UserController@delete');
Route::get('/user/view/{id}','UserController@viewRecord');
Route::post('/user/export','UserController@export');
Route::get('/user/search','UserController@search');
Route::get('/user/email/{id}','UserController@email');
Route::post('/user/send-email','UserController@sendEmail');
Route::post('/user/column','UserController@getColumn');
Route::get('/user/referrals/{id}','ReferralController@index');
Route::post('/autosuggest/referral','UserController@autosuggestReferral');
Route::post('/search/live','UserController@liveSearch');
Route::get('/user/panel/{id}','UserController@userPanel');
Route::post('/user/password/email','UserController@sendEmailPassword');
Route::post('/user/level-referrer','UserController@levelReferrer');
Route::post('/user/confirm_admin','UserController@ConfirmAdmin');

/* Country Routes */
Route::get('/country','CountryController@index');
Route::get('/country/new','CountryController@newRecord');
Route::post('/country/store','CountryController@store');
Route::get('/country/edit/{id}','CountryController@editRecord');
Route::post('/country/update','CountryController@update');
Route::post('/country/delete','CountryController@delete');

/* Questions Routes */
Route::get('/question','QuestionController@index');
Route::get('/question/new','QuestionController@newRecord');
Route::post('/question/store','QuestionController@store');
Route::get('/question/edit/{id}','QuestionController@editRecord');
Route::post('/question/update','QuestionController@update');
Route::post('/question/delete','QuestionController@delete');

/* Plan Routes */
Route::get('/plan','PlanController@index');
Route::get('/plan/new','PlanController@newRecord');
Route::post('/plan/store','PlanController@store');
Route::get('/plan/edit/{id}','PlanController@editRecord');
Route::post('/plan/update','PlanController@update');
Route::get('/plan/view/{id}','PlanController@viewRecord');
Route::post('/plan/delete','PlanController@delete');

/* Investment Routes */
Route::get('/loan','InvestmentController@index');
Route::get('/loan/new','InvestmentController@newRecord');
Route::post('/loan/store','InvestmentController@store');
Route::get('/loan/edit/{id}','InvestmentController@editRecord');
Route::post('/loan/update','InvestmentController@update');
Route::get('/loan/view/{id}','InvestmentController@viewRecord');
Route::post('/loan/delete','InvestmentController@delete');
Route::post('/loan/approve','InvestmentController@approveLoan');

/* NEWS */
Route::get('/news','NewsController@index');
Route::get('/news/new','NewsController@newRecord');
Route::post('/news/store','NewsController@store');
Route::get('/news/edit/{id}','NewsController@editRecord');
Route::post('/news/update','NewsController@update');
Route::post('/news/delete','NewsController@delete');

/* NEWSLETTER */
Route::get('/newsletter','NewsletterController@index');
Route::get('/newsletter/new','NewsletterController@newRecord');
Route::post('/newsletter/store','NewsletterController@store');
Route::get('/newsletter/edit/{id}','NewsletterController@editRecord');
Route::post('/newsletter/update','NewsletterController@update');
Route::post('/newsletter/delete','NewsletterController@delete');

/* MAIL */
Route::get('/mail','MailController@index');
Route::get('/mail/new','MailController@newRecord');
Route::post('/mail/store','MailController@store');
Route::get('/mail/edit/{id}','MailController@editRecord');
Route::post('/mail/update','MailController@update');
Route::post('/mail/delete','MailController@delete');

/* TESTIMONIALS */
// Route::get('/testimonial','TestimonialController@index');
// Route::get('/testimonial/new','TestimonialController@newRecord');
// Route::post('/testimonial/store','TestimonialController@store');
// Route::get('/testimonial/edit/{id}','TestimonialController@editRecord');
// Route::post('/testimonial/update','TestimonialController@update');
// Route::post('/testimonial/delete','TestimonialController@delete');

/* SUB ADMIN */
// Route::get('/sub-admin','SubAdminController@index');
// Route::get('/sub-admin/new','SubAdminController@newRecord');
// Route::post('/sub-admin/store','SubAdminController@store');
// Route::get('/sub-admin/edit/{id}','SubAdminController@editRecord');
// Route::post('/sub-admin/update','SubAdminController@update');
// Route::post('/sub-admin/delete','SubAdminController@delete');

/* TIKET Routes */
Route::get('/ticket','TicketController@adminIndex');
Route::get('/ticket/view/{id}','TicketController@adminViewRecord');
Route::post('/ticket/reply/store','TicketReplyController@store');
Route::post('/ticket/delete','TicketController@delete');
Route::post('/ticket/close','TicketController@close');

/* IPN Routes */
Route::get('/ipn','IpnController@index');
Route::post('/ipn/delete','IpnController@delete');

Route::get('/sms','SendsmsController@index');

/* Setting Coinpayment Routes */
Route::get('/coinpayment','CoinpaymentController@index');
Route::get('/coinpayment/new','CoinpaymentController@newRecord');
Route::post('/coinpayment/store','CoinpaymentController@store');
Route::get('/coinpayment/edit/{id}','CoinpaymentController@editRecord');
Route::post('/coinpayment/update','CoinpaymentController@update');
Route::post('/coinpayment/delete','CoinpaymentController@delete');
Route::get('/coinpayment/status/{id}','CoinpaymentController@chStatus');


/* Setting Getresponse Routes */
// Route::get('/getresponse','GetresponseController@index');
// Route::get('/getresponse/new','GetresponseController@newRecord');
// Route::post('/getresponse/store','GetresponseController@store');
// Route::get('/getresponse/edit/{id}','GetresponseController@editRecord');
// Route::post('/getresponse/update','GetresponseController@update');
// Route::post('/getresponse/delete','GetresponseController@delete');
// Route::get('/getresponse/status/{id}','GetresponseController@chStatus');

/* Setting Google capcha Routes */
Route::get('/google-capcha','GoogleCapchaController@index');
Route::get('/google-capcha/new','GoogleCapchaController@newRecord');
Route::post('/google-capcha/store','GoogleCapchaController@store');
Route::get('/google-capcha/edit/{id}','GoogleCapchaController@editRecord');
Route::post('/google-capcha/update','GoogleCapchaController@update');
Route::post('/google-capcha/delete','GoogleCapchaController@delete');
Route::get('/google-capcha/status/{id}','GoogleCapchaController@chStatus');


/* Setting Mail Management Routes */
Route::get('/mail-management','MailManagementController@index');
Route::get('/mail-management/new','MailManagementController@newRecord');
Route::post('/mail-management/store','MailManagementController@store');
Route::get('/mail-management/edit/{id}','MailManagementController@editRecord');
Route::post('/mail-management/update','MailManagementController@update');
Route::get('/mail-management/status/{id}','MailManagementController@chStatus');
Route::post('/mail-management/update_signature','MailManagementController@update_signature');

Route::get('/sms-management','SmsManagementController@index');
Route::get('/sms-management/new','SmsManagementController@newRecord');
Route::post('/sms-management/store','SmsManagementController@store');
Route::get('/sms-management/edit/{id}','SmsManagementController@editRecord');
Route::post('/sms-management/update','SmsManagementController@update');
Route::get('/sms-management/status/{id}','SmsManagementController@chStatus');
Route::post('/sms-management/signature','SmsManagementController@update_signature');

/* Setting Google capcha Routes */
Route::get('/login-detail','loginDetailController@index');

Route::get('/cron-job','CronJobController@index');

Route::get('/ledger','RedepositeController@index');
Route::get('/ledger/detail','RedepositeController@detail');

Route::get('/referral-report','ReferralReportController@index');
Route::get('/referral-report/detail','ReferralReportController@detail');

Route::get('/mass-email','MassMailController@index');
Route::post('/mass-email/send','MassMailController@send');

Route::get('/database-backup','DatabaseBackupController@index');
Route::post('/database-backup/store','DatabaseBackupController@store');

Route::post('/user/autofill','UserController@autoFill');


/*Plan Change Request*/
Route::get('/deposit/change/plan','DepositPlanChangeController@indexAdmin');

/* MENU */
Route::get('/menu','MenuController@index');
Route::get('/menu/new','MenuController@newRecord');
Route::post('/menu/store','MenuController@store');
Route::get('/menu/edit/{id}','MenuController@editRecord');
Route::post('/menu/update','MenuController@update');

/* LEVEL COMMISION */
Route::get('/level-commision','LevelCommisionController@index');
Route::post('/level-commision/export','LevelCommisionController@export');
Route::get('/level-commision/pending','LevelCommisionController@pending');
Route::post('/level-commision/approve','LevelCommisionController@approveLevelCommision');
Route::post('/level-commision/report','LevelCommisionController@report');
Route::post('/level-commision/pending/export','LevelCommisionController@exportPending');

Route::get('/withdraw','WithdrawController@indexAdmin');
Route::get('/withdraw/view/{id}','WithdrawController@viewRecord');
Route::post('/withdraw/export','WithdrawController@export');
Route::post('/withdraw-pay','WithdrawController@withdraw_pay');
Route::post('/withdraw/approve/checked','WithdrawController@withdraw_pay_checked');

Route::get('/interest-payment','InterestPaymentController@index');
Route::get('/interest-payment/pending/{id?}','InterestPaymentController@pending');

Route::get('/payout-report','PayOutController@index');

Route::get('/setting','SettingController@index');
Route::get('/setting/edit','SettingController@editRecord');
Route::post('/setting/update','SettingController@update');

Route::get('/interest-variable/','InterestVariableController@index');
Route::get('/interest-variable/new','InterestVariableController@newRecord');
Route::post('/interest-variable/store','InterestVariableController@store');
Route::get('/interest-variable/edit/{id}','InterestVariableController@editRecord');
Route::post('/interest-variable/update','InterestVariableController@update');
Route::post('/interest-variable/delete','InterestVariableController@delete');

});