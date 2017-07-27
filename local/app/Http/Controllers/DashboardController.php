<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Response;
use Auth;
use DB;
use Carbon\Carbon;
use App\myCustome\myCustome;
use App\User;
use App\Ticket;
use App\IpnRequest;
use App\Notifications;
use App\Deposit;
use App\LevelCommision;
use App\InterestPayment;
use App\NexmoSms;
use App\EmailNotify;
use App\Plan;
use App\Referral;
use App\DepositPlanChange;
use App\Withdraw;
use App\LoginDetail;
use App\ReDeposit;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected $userNotificationArray  = ['level_commision_approve','ticket_reply','deposit_user','interest-payment','level-commission','withdraw-approve'];
    protected $adminNotificationArray = ['new_ticket_user','deposit','withdraw','user-register'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id = null)
    {
        $logdetails = LoginDetail::lastLogin();
        if(Auth::user()->hasRole('user'))
        {
            $depositId          = 0;
            $levelCommisions    = LevelCommision::getLastNewLevelCommisionByUser();
            $interestPayments   = InterestPayment::getLastNewInterestPaymentByUser();
            $deposits           = ReDeposit::getDetails('','',Auth::user()->id,'dashboard');
            return view('users.pages.dashboard',compact('levelCommisions','interestPayments','deposits','logdetails'));
        }
        else
        {
            $users              = User::getLastNewUser();
            $tickets            = Ticket::getLastNewTicket();
            $levelCommisions    = LevelCommision::getLastNewLevelCommision();
            $investments        = Deposit::getLastNewInvestment();
            $interestPayments   = InterestPayment::getLastNewPaymentInterest();
            $depositPlanChanges = DepositPlanChange::getLastNewRequest();
            $withdrawals        = Withdraw::getLastNewWithdrawal();
            $countryWiseLenders = User::getCountryWiseUsersCount();
            return view('admin.pages.dashboard',compact('users','tickets','levelCommisions','investments','interestPayments','depositPlanChanges','withdrawals','logdetails','countryWiseLenders'));
        }
    }

    public function dashboardData()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit','1024M');

        $activelenderscounts                          = Deposit::activeTotalLendersMoreThanDeposit(array(1,2,3));
        $active_total_lenders_more_than_one_deposit   = $activelenderscounts[1];
        $active_total_lenders_more_than_two_deposit   = $activelenderscounts[2];
        $active_total_lenders_more_than_three_deposit = $activelenderscounts[3];
        $active_total_lenders                         = Deposit::activeTotalLenders('result');
        $total_withdrawals                            = Withdraw::totalWithdrawals();
        $total_tickets                                = Ticket::totalTickets();
        $allUsers                                     = User::getAllUsers();
        $totalUsers                                   = count($allUsers);
        $payouts                                      = ReDeposit::payoutReport();
        $loan                                         = Deposit::getDeposite();
        $today_user_count                             = User::getTotalUserToday();
        $no_of_country                                = User::getTotalUserCountry();
        $no_of_payment_not_completed                  = IpnRequest::getTotalNotCompletedPayments();
        $getCountOfUserPayments                       = IpnRequest::getCountOfUserPayments();
        $amount_withdrawals_pending                   = Withdraw::amountPendingWithdrawls();
        $latest_country                               = User::getLatestCountry();
       
        $active_lenders_total = count($active_total_lenders);

        $total_payment_not_completed_amount = 0;

        foreach ($no_of_payment_not_completed as $payment) 
        {
            $total_payment_not_completed_amount = $total_payment_not_completed_amount + $payment->amount;
        }

        $total_percentage_not_payment = number_format((count($no_of_payment_not_completed) * 100) / count($getCountOfUserPayments),2) ."%";
        
        //USER TOTAL
        $oUsers              = $allUsers;
        $user_total        = [];
        $user_total[24]     = 0;
        $user_total[48]     = 0;
        $user_total[72]     = 0;
        $user_total[7]      = 0;
        $user_total[14]     = 0;
        $user_total[21]     = 0;
        $user_total[30]     = 0;
        $user_total[60]     = 0;
        $user_total[90]     = 0;
        $today             = Carbon::now();

        $uhour24 = Carbon::now()->addHours(-24);        
        $uhour48 = Carbon::now()->addHours(-48);
        $uhour72 = Carbon::now()->addHours(-72);
        $udays7  = Carbon::now()->addDays(-7);        
        $udays14 = Carbon::now()->addDays(-14);
        $udays21 = Carbon::now()->addDays(-21);
        $udays30 = Carbon::now()->addDays(-30);
        $udays60 = Carbon::now()->addDays(-60);
        $udays90 = Carbon::now()->addDays(-90);

        if(count($oUsers) > 0)
        {
            foreach($oUsers as $singleUser)
            {

                if(strtotime($uhour24->toDateString()) <= strtotime($singleUser->created_at->toDateString()))
                {        
                    $user_total[24]++;
                }

                if(strtotime($uhour48->toDateString()) <= strtotime($singleUser->created_at->toDateString()))
                {           
                    $user_total[48]  ++;
                }

                if(strtotime($uhour72->toDateString()) <= strtotime($singleUser->created_at->toDateString()))
                {           
                    $user_total[72]  ++;
                }

                if(strtotime($udays7->toDateString()) < strtotime($singleUser->created_at->toDateString()))
                {           
                    $user_total[7]  ++;
                }

                if(strtotime($udays14->toDateString()) < strtotime($singleUser->created_at->toDateString()))
                {
                    $user_total[14]  ++;
                }

                if(strtotime($udays21->toDateString()) < strtotime($singleUser->created_at->toDateString()))
                {
                    $user_total[21]  ++;
                }

                if(strtotime($udays30->toDateString()) < strtotime($singleUser->created_at->toDateString()))
                {
                    $user_total[30]  ++;
                }

                if(strtotime($udays60->toDateString()) < strtotime($singleUser->created_at->toDateString()))
                {
                    $user_total[60]  ++;
                }

                if(strtotime($udays90->toDateString()) < strtotime($singleUser->created_at->toDateString()))
                {
                    $user_total[90]  ++;
                }
            }
        }
        //END USER

                
        //PAYOUT TOTAL
        $allRecord          = $payouts['list'];

        $payouttotal        = [];
        $payouttotal[7]     = 0;
        $payouttotal[14]    = 0;
        $payouttotal[21]    = 0;
        $payouttotal[30]    = 0;
        $today              = Carbon::now();

        $days7 = Carbon::now()->addDays(7);
        $days14 = Carbon::now()->addDays(14);
        $days21 = Carbon::now()->addDays(21);

        if(count($allRecord) > 0)
        {
            foreach($allRecord as $singleRecord)
            {
                $totalAmt = 0;
                if($singleRecord['nature_of_plan'] == 1)
                {
                    $totalAmt = $singleRecord['amount'] + $singleRecord['interest'];    
                }
                else if($singleRecord['nature_of_plan'] == 3)
                {
                    $totalAmt = 0;
                }
                else if($singleRecord['nature_of_plan'] == 4)
                {
                    $totalAmt = $singleRecord['initialamt'];
                }
                else if($singleRecord['nature_of_plan'] == 2)
                {
                    $totalAmt = $singleRecord['interest'];  
                }

                if(strtotime($days7->toDateString()) >= strtotime($singleRecord['endDate']->toDateString()))
                {
                    $payouttotal[7]  = $payouttotal[7] + $totalAmt;
                }

                if(strtotime($days14->toDateString()) >= strtotime($singleRecord['endDate']->toDateString()))
                {
                    $payouttotal[14]  = $payouttotal[14] + $totalAmt;
                }

                if(strtotime($days21->toDateString()) >= strtotime($singleRecord['endDate']->toDateString()))
                {
                    $payouttotal[21]  = $payouttotal[21] + $totalAmt;
                }
                
                $payouttotal[30]  = $payouttotal[30] + $totalAmt;
            }
        }
        //END PAYOUT
       

        //LOAN TOTAL
        $oLoan             = $loan;

        $loan_total        = [];
        $loan_total[24]    = 0;
        $loan_total[48]    = 0;
        $loan_total[72]    = 0;
        $loan_total[7]     = 0;
        $loan_total[14]    = 0;
        $loan_total[21]    = 0;
        $loan_total[30]    = 0;
        $loan_total[60]    = 0;
        $loan_total[90]    = 0;
        $loan_total[180]   = 0;
        $loan_total[270]   = 0;
        $loan_total[360]   = 0;
        $loan_total[720]   = 0;
        $today             = Carbon::now();

        $hour24 = Carbon::now()->addDays(-1);
        $hour48 = Carbon::now()->addDays(-2);
        $hour72 = Carbon::now()->addDays(-3);
        $days7  = Carbon::now()->addDays(-7);        
        $days14 = Carbon::now()->addDays(-14);
        $days21 = Carbon::now()->addDays(-21);
        $days30 = Carbon::now()->addDays(-30);
        $days60 = Carbon::now()->addDays(-60);
        $days90 = Carbon::now()->addDays(-90);
        $days180 = Carbon::now()->addDays(-180);
        $days270 = Carbon::now()->addDays(-270);
        $days360 = Carbon::now()->addDays(-360);
        $days720 = Carbon::now()->addDays(-720);

        if(count($oLoan) > 0)
        {
            foreach($oLoan as $singleLoan)
            {

                if ((strtotime($singleLoan->created_at) > strtotime($hour24)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[24]  = $loan_total[24] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($hour48)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {           
                    $loan_total[48]  = $loan_total[48] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($hour72)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {           
                    $loan_total[72]  = $loan_total[72] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days7)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {           
                    $loan_total[7]  = $loan_total[7] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days14)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[14]  = $loan_total[14] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days21)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[21]  = $loan_total[21] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days30)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[30]  = $loan_total[30] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days60)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[60]  = $loan_total[60] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days90)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[90]  = $loan_total[90] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days180)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[180]  = $loan_total[180] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days270)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[270]  = $loan_total[270] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days360)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[360]  = $loan_total[360] + $singleLoan->amount;
                }

                if ((strtotime($singleLoan->created_at) > strtotime($days720)) && (strtotime($singleLoan->created_at) < strtotime(Carbon::now())))
                {
                    $loan_total[720]  = $loan_total[720] + $singleLoan->amount;
                }
            }
        }
        //END LOAN
        
        //Active Lender TOTAL
        $oLender                = $active_total_lenders;

        $aLender_total        = [];
        $aLender_total[24]    = 0;
        $aLender_total[48]    = 0;
        $aLender_total[72]    = 0;
        $aLender_total[7]     = 0;
        $aLender_total[14]    = 0;
        $aLender_total[21]    = 0;
        $aLender_total[30]    = 0;
        $aLender_total[60]    = 0;
        $aLender_total[90]    = 0;
        $aLender_total[6]     = 0;
        $today              = Carbon::now();


        if(count($oLender) > 0)
        {
            foreach($oLender as $singleLender)
            {
                if(strtotime($hour24) < strtotime($singleLender->created_at))
                {           
                    $aLender_total[24] ++;
                } 

                if( strtotime($hour48) < strtotime($singleLender->created_at) )
                {           
                    $aLender_total[48]  ++ ;
                }

                if(strtotime($hour72) < strtotime($singleLender->created_at))
                {           
                    $aLender_total[72]  ++;
                }

                if(strtotime($days7) < strtotime($singleLender->created_at))
                {           
                    $aLender_total[7]  ++;
                }

                if(strtotime($days14) < strtotime($singleLender->created_at))
                {
                    $aLender_total[14]  ++;
                }

                if(strtotime($days21) < strtotime($singleLender->created_at))
                {
                    $aLender_total[21] ++;
                }

                if(strtotime($days30) < strtotime($singleLender->created_at))
                {
                    $aLender_total[30]  ++;
                }

                if(strtotime($days60) < strtotime($singleLender->created_at))
                {
                    $aLender_total[60]  ++;
                }

                if(strtotime($days90) < strtotime($singleLender->created_at))
                {
                    $aLender_total[90]  ++;
                }

                if(strtotime($days180) < strtotime($singleLender->created_at))
                {
                    $aLender_total[6]  ++;
                }                
            }


            $aLender_total[24]    =  ($aLender_total[24]==0)?1:$aLender_total[24];
            $aLender_total[48]    =  ($aLender_total[48]==0)?1:$aLender_total[48];
            $aLender_total[72]    =  ($aLender_total[72]==0)?1:$aLender_total[72];
            $aLender_total[7]     =  ($aLender_total[7]==0)?1:$aLender_total[7];
            $aLender_total[14]    =  ($aLender_total[14]==0)?1:$aLender_total[14];
            $aLender_total[21]    =  ($aLender_total[21]==0)?1:$aLender_total[21];
            $aLender_total[30]    =  ($aLender_total[30]==0)?1:$aLender_total[30];
            $aLender_total[60]    =  ($aLender_total[60]==0)?1:$aLender_total[60];
            $aLender_total[90]    =  ($aLender_total[90]==0)?1:$aLender_total[90];
            $aLender_total[6]     =  ($aLender_total[6]==0)?1:$aLender_total[6];

            $aLender_total[24]    = ($loan_total[24] / $aLender_total[24]);
            $aLender_total[48]    = ($loan_total[48] / $aLender_total[48]);
            $aLender_total[72]    = ($loan_total[72] / $aLender_total[72]);
            $aLender_total[7]     = ($loan_total[7] / $aLender_total[7]);
            $aLender_total[14]    = ($loan_total[14] / $aLender_total[14]);
            $aLender_total[21]    = ($loan_total[21] / $aLender_total[21]);
            $aLender_total[30]    = ($loan_total[30] / $aLender_total[30]);
            $aLender_total[60]    = ($loan_total[60] /$aLender_total[60]);
            $aLender_total[90]    = ($loan_total[90] / $aLender_total[90]);
            $aLender_total[6]     = ($loan_total[180] / $aLender_total[6]);
        }
        //END LOAN
       
        $active_total_lenders_percent = ( $active_lenders_total * 100 ) / $totalUsers;
        $active_total_lenders_percent = number_format($active_total_lenders_percent,2);

        $active_total_lenders_more_than_one_deposit_percent = ( $active_total_lenders_more_than_one_deposit * 100 ) / $totalUsers;
        $active_total_lenders_more_than_one_deposit_percent = number_format($active_total_lenders_more_than_one_deposit_percent,2);

        $active_total_lenders_more_than_two_deposit_percent = ( $active_total_lenders_more_than_two_deposit * 100 ) / $totalUsers;
        $active_total_lenders_more_than_two_deposit_percent = number_format($active_total_lenders_more_than_two_deposit_percent,2);

        $active_total_lenders_more_than_three_deposit_percent = ( $active_total_lenders_more_than_three_deposit * 100 ) / $totalUsers;
        $active_total_lenders_more_than_three_deposit_percent = number_format($active_total_lenders_more_than_three_deposit_percent,2);

        $deposite = [];
        $deposite['one_percentage'] = $active_total_lenders_more_than_one_deposit_percent.' % ';
        $deposite['one_deposite'] = $active_total_lenders_more_than_one_deposit;
        $deposite['two_percentage'] = $active_total_lenders_more_than_two_deposit_percent.' % ';
        $deposite['two_deposite'] = $active_total_lenders_more_than_two_deposit;
        $deposite['three_percentage'] = $active_total_lenders_more_than_three_deposit_percent.' % ';
        $deposite['three_deposite'] = $active_total_lenders_more_than_three_deposit;
        
        $response = array(
                'msg'                                                   => 'success',
                'active_total_lenders_percent'                          => $active_total_lenders_percent.' % ',
                'active_total_lenders'                                  => $active_lenders_total,
                'amount_of_payment_not_completed'                       => $total_payment_not_completed_amount,
                'percentage_of_payment_not_completed'                   => $total_percentage_not_payment,
                'deposite'                                              => $deposite,
                'no_of_country'                                         => $no_of_country,
                'total_tickets_new'                                     => $total_tickets[0],
                'total_tickets_reopen'                                  => $total_tickets[1],
                'total_tickets_pending'                                 => $total_tickets[4],
                'total_tickets_inprogress'                              => $total_tickets[2],
                'total_tickets_awaiting_your_reply'                     => $total_tickets[3],
                'total_withdrawals_pending'                             => $total_withdrawals[0],
                'total_withdrawals_approved'                            => $total_withdrawals[1],
                'payouttotal'                                           => $payouttotal,
                'loantotal'                                             => $loan_total,
                'total_user_count'                                      => $totalUsers,
                'usertotal'                                             => $user_total,
                'today_user_count'                                      => $today_user_count,
                'aLender_total'                                         => $aLender_total,
                'amount_withdrawals_pending'                            => $amount_withdrawals_pending,
                'latest_country'                                        => $latest_country,
                );

        return response()->json($response);
    }

    public function dashboardDuplicators()
    {
        ini_set('max_execution_time', 300);
        $duplicators                                  = User::getDuplicators();
        $getDuplicators                               = $duplicators['second_level'];
        $getThirdLevelDuplicators                     = $duplicators['third_level'];
        $getFifthLevelDuplicators                     = $duplicators['fifth_level'];
        $response = array(
            'msg'                                                   => 'success',
            'aDuplicators'                                          => $getDuplicators,
            'aThirdLevelDuplicators'                                => $getThirdLevelDuplicators,
            'aFifthLevelDuplicators'                                => $getFifthLevelDuplicators,
        );
        return response()->json($response);
    }

    public function chart(Request $request)
    {
        $depositId = $request->id;
        $divAmt = 0;
        if($depositId > 0)
        {
            $intDatas   = ReDeposit::getDetails('','',Auth::user()->id,'chart',$depositId);
            if($intDatas)
            {
                $ac_startDate   = dispayTimeStamp($intDatas['startDate'])->toDayDateTimeString();
                $ac_endDate     = dispayTimeStamp($intDatas['endDate'])->toDayDateTimeString();
                $duration       = $intDatas['interest_period_type'];

                if($duration == 2)
                {
                    $divAmt = 1;
                }
                elseif($duration == 3)
                {
                    $divAmt = 7;
                }
                else if($duration == 4)
                {
                    $divAmt = 30;
                }
                else if($duration == 5)
                {
                    $divAmt = 365;
                }
            }

            $dateString     = "";
            $dataString     = "";
            $maxInterest    = 0;
            if($intDatas)
            {
                $to             = $intDatas['startDate'];
                $from           = $intDatas['endDate'];
                $diff_in_days   = $to->diffInDays($from);
                $start_date     = $to->toDateString();
                $end_date       = $from->toDateString();
                $tempArray      = array();
                if($diff_in_days > 0)
                {

                    $tempArray = [];
                    $demo1 = 0;

                    $tot = $intDatas['totalInterest'] / $divAmt;
                    while (strtotime($start_date) < strtotime($end_date)) 
                    { 
                        if($diff_in_days == 30)
                        {
                            $start_date     = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
                        }

                        $startdt        = Carbon::parse($start_date)->format('Y-m-d');
                        $startdt        = Carbon::createFromFormat('Y-m-d', $startdt);
                        $demo1          = $tot + $demo1;

                        if(strtotime(Carbon::now()->toDateString()) >= strtotime($start_date))
                        {
                            $tempArray[]    = array($startdt->format('j-M-Y') , $demo1);
                        }
                        else
                        {
                            $tempArray[]    = array($startdt->format('j-M-Y') , "0");
                        }

                        if($diff_in_days == 1)
                        {
                            $start_date     = date ("Y-m-d", strtotime("+1 days", strtotime($start_date)));
                        }

                        $maxInterest = $tot + $maxInterest;
                    }
                }
                else if($diff_in_days == 0)
                {
                    $tempArray      = [];
                    $enddt          = Carbon::parse($end_date)->format('Y-m-d');
                    $enddt          = Carbon::createFromFormat('Y-m-d', $enddt);
                    $demo1          = $intDatas['totalInterest'];
                    $maxInterest    = $intDatas['totalInterest'];
                    $diff_in_days   = 1;
                    $tempArray[]    = array($enddt->format('j-M') , $demo1);
                    
                }
            }

            $response = array(
                'msg'               => 'success',
                'data'              => $tempArray,
                'startDate'         => $ac_startDate,
                'endDate'           => $ac_endDate,
                'maxInterest'       => $maxInterest,
                'diff_in_days'      => $diff_in_days,
                );

            return response()->json($response);
        }
    }

    public function getMessages()
    {
        $timeZone   =   config('app.timezone');
        if(Auth::user()->hasRole('user'))
        {
            $levelCommisions    =   LevelCommision::getLastNewLevelCommisionByUser();
            $interestPayments   =   InterestPayment::getLastNewInterestPaymentByUser();

            $response = array(
                'msg'               => 'success',
                'interestPayments'  => $interestPayments,
                'levelCommisions'   => $levelCommisions,
                'timeZone'          => $timeZone,
                'S'                 => 'B',
                );

            return response()->json($response);
        }
        else
        {
            $totalUsers         =   User::getTotalUser();
            $users              =   User::getLastNewUser();
            $tickets            =   Ticket::getLastNewTicket();
            $levelCommisions    =   LevelCommision::getLastNewLevelCommision();
            $investments        =   Deposit::getLastNewInvestment();
            $interestPayments   =   InterestPayment::getLastNewPaymentInterest();

            $response = array(
                'msg'               => 'success',
                'totalUsers'        => $totalUsers,
                'users'             => $users,
                'tickets'           => $tickets,
                'levelCommisions'   => $levelCommisions,
                'investments'       => $investments,
                'interestPayments'  => $interestPayments,
                'timeZone'          => $timeZone,
                'S'                 => 'A',
                );

            return response()->json($response);
        }
    }

    function getNotificationCount()
    {
        $data['msg'] = 'wait';
        $user = Auth::user();
        $ticket_reply = 0;
        if($user->hasRole('user'))
        {
            $notifications = Notifications::where('notifiable_id',$user->id)
            ->where('read','0')
            ->whereIn('type', $this->userNotificationArray)
            ->count();

            $ticket_reply = Notifications::where('notifiable_id',$user->id)
            ->where('read','0')
            ->where('type', 'ticket_reply')
            ->count();
        }
        else
        {
            $notifications = Notifications::where('notifiable_id',$user->id)
            ->where('read','0')
            ->whereIn('type', $this->adminNotificationArray)
            ->count();
        }

        if($notifications > 0)
        {
            if($notifications > 99)
            {
                $notifications = '99'.'+';
            }
            $data['messageCount']   = $notifications;
            $data['ticketCount']    = $ticket_reply;
            $data['msg'] = 'success';
        }
        return Response::json($data);
    }

    function getNotificationMessage(Request $request)
    {
        $data['msg']        = 'wait';
        $messageCount       = '<li class="list-group-item info lt box-shadow-z0 b"><span class="pull-left m-r"></span> <span class="clear block"><i class="material-icons">&#xE7F5;</i> Your notifications live here.<br></span><small class="text-muted">No Notifications Yet !</small></span></li>';
        $data['message']    = $messageCount;
        $user               = Auth::user();

        if($user->hasRole('user'))
        {
            $notificationsCount = Notifications::where('notifiable_id',$user->id)
            ->where('read','0')
            ->whereIn('type', $this->userNotificationArray)
            ->count();

             $notifications  = Notifications::where('notifiable_id',$user->id)
             ->latest('created_at')
             ->where('read','0')
             ->whereIn('type', $this->userNotificationArray)
             ->take(50)
             ->get();
        }
        else
        {
            $notificationsCount = Notifications::where('notifiable_id',$user->id)
            ->where('read','0')
            ->whereIn('type', $this->adminNotificationArray)
            ->count();

            $notifications      = Notifications::where('notifiable_id',$user->id)
             ->latest('created_at')
             ->where('read','0')
             ->whereIn('type', $this->adminNotificationArray)
             ->take(50)
             ->get();
        }

        if(count($notifications) > 0)
        {
            $message        = "";
            foreach ($notifications as $notification)
            {

                $humanDate   = $notification->created_at->diffForHumans();
                $systemDate   = $notification->created_at->toDayDateTimeString();

                if($notification->type == 'new_ticket_user')
                {
                    $message .= '<li class="list-group-item primary lt box-shadow-z0 b"><span class="pull-left m-r"></span> <span class="clear block"><a href="'.URL('admin/ticket/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'ticket_reply')
                {
                    $message .= '<li class="list-group-item accent lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('user/ticket/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'level_commision_approve')
                {
                    $message .= '<li class="list-group-item warning lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('user/level-commision/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'deposit')
                {
                    $message .= '<li class="list-group-item info lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('admin/loan/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'deposit_user')
                {
                    $message .= '<li class="list-group-item success lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('user/deposit/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'interest-payment')
                {
                    $message .= '<li class="list-group-item info lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('user/interest-payment/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'level-commission')
                {
                    $message .= '<li class="list-group-item primary lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('user/level-commision/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'withdraw')
                {
                    $message .= '<li class="list-group-item success lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('admin/withdraw/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'withdraw-approve')
                {
                    $message .= '<li class="list-group-item success lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('user/withdraw/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
                else if($notification->type == 'user-register')
                {
                    $message .= '<li class="list-group-item success lt box-shadow-z0 b"><span class="clear block"><a href="'.URL('admin/user/view/'.$notification->link_id.' ').'">'.$notification->data.'</a><br><small class="text-muted" title="'.$systemDate.'">'.$humanDate.'</small></span></li>';
                }
            }

            $data['message'] = $message;
            $data['msg']     = 'success';
        }
        return Response::json($data);
    }

    function planCount(Request $request)
    {
        $html   = "";

        $html .= '<div class="table-responsive"><table class="table"><thead><tr><th>#</th><th>Plan Name</th><th>Total Users</th></tr></thead><tbody></div>';
        $a = 1;

        $plans = Plan::where('status','active')->select('planid','plan_name')->get();
        foreach ($plans as $plan)
        {
            $datas  = Deposit::planSubscriptionByUser($plan->planid);
            if($datas[0]->cnt > 0)
            {
                $html .= '<tr>';
                $html .= '<td>'.$a.'</td>';
                $html .= '<td><a href="javascript:void('.$plan->planid.')">'.$plan->plan_name.'</a></td>';
                $html .= '<td>'.$datas[0]->cnt.'</td>';
                $html .= '</tr>';
                $a++;
            }
        }
        $html .= '</tbody></table>';

        $data['html'] = $html;
        $data['msg']     = 'success';
        return Response::json($data);
    }
}
?>