<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Hash;
use Config;
use Redirect;
use Auth;
use App\Referral;
use App\LevelCommision;
use App\WalletAmountInOut;
use App\myCustome\myCustome;
use App\User;
use App\ReDeposit;
use Carbon\Carbon;
use App\Withdraw;

class ReferralReportController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	public function index()
	{
        $mode                   = '';
        $referrals              = array();
        $levelcommision         = 0;
        $withdraw_commission    = 0;
        $cmb_user               = "";
        $stdt                   =  "";
        $endt                   =  "";
        $mode                   = '';
        $dateRange              = "";
        $username                = "";

        $user = User::getFirstUser();
        if(count($user) > 0)
        {
            $loginUsr           = $user->id;
            $mode               = 'view';
            $cmb_user           = $loginUsr;
            $username           = $user->username;
            Referral::$cmbUser  = $loginUsr;
            $referrals          = Referral::getReferralDownline($loginUsr,1,10,'report','','');
            $commision          = WalletAmountInOut::getCommission($loginUsr);
        }
		return view('admin.pages.referral-report',compact('referrals','commision','mode','cmb_user','stdt','endt','mode','dateRange','username'));
	}

    public function detail(Request $request)
    {
        $loginUsr       = $request->cmb_user;
        $usernameData       = User::where('id',$loginUsr)->select('username')->first();
        $mode               = 'view';
        $cmb_user           = $loginUsr;
        $username           = $usernameData->username;
        Referral::$cmbUser  = $loginUsr;
        $referrals          = Referral::getReferralDownline($loginUsr,1,10,'report');
        $commision          = WalletAmountInOut::getCommission($loginUsr);

        return view('admin.pages.referral-report',compact('referrals','commision','mode','cmb_user','username','mode','dateRange'));
    }

    public function detailUser(Request $request)
    {
        $validator = $this->validate($request, [
            'startdt'   => 'nullable|date',
            'enddt'     => 'nullable|date',
            ],[
                'startdt.date' => 'The Start Date is invalid.',
                'enddt.date' => 'The End Date is invalid.',
        ]);

        $startdt    =  "";
        $enddt      =  "";
        $stdt       =  "";
        $endt       =  "";
        $mode       = 'search';
        $dateRange  = "";
        if($request->startdt != null && $request->enddt != null)
        {
            $startdt    = Carbon::parse($request->startdt)->format('m-d-Y');
            $startdt    = Carbon::createFromFormat('m-d-Y', $startdt);
            $enddt      = Carbon::parse($request->enddt)->format('m-d-Y');
            $enddt      = Carbon::createFromFormat('m-d-Y', $enddt);

            $stdt = $request->startdt;
            $endt = $request->enddt;

            $dateRange = $startdt->toFormattedDateString().' to '.$enddt->toFormattedDateString();
        }

        $loginUsr       = Auth::user()->id;

        $usernameData   = User::where('id',$loginUsr)->select('username')->first();

        $mode               = '';
        $mode               = 'view';
        $cmb_user           = $loginUsr;
        $username           = $usernameData->username;
        Referral::$cmbUser  = $loginUsr;
        $referrals          = Referral::getReferralDownline($loginUsr,1,10,'report',$startdt,$enddt);
        $levelcommision     = myCustome::availableCommission($loginUsr);
        return view('admin.pages.referral-report',compact('referrals','levelcommision','mode','cmb_user','username','stdt','endt','mode','dateRange'));
    }


    public function indexUser()
    {
        $mode               = '';
        $loginUsr           = Auth::user()->id;
        $mode               = 'view';
        $cmb_user           = $loginUsr;
        $username           = Auth::user()->username;
        $dateRange          = "";
        $stdt               = "";
        $endt               = "";
        Referral::$cmbUser  = $loginUsr;
        $referrals          = Referral::getReferralDownline($loginUsr,1,10,'report');
        $commision          = WalletAmountInOut::getCommission($loginUsr);
        return view('admin.pages.referral-report',compact('referrals','mode','cmb_user','username','dateRange','stdt','endt','commision'));
    }

    public function referralDetail(Request $request)
    {
        $id         = $request->id;
        $selId      = $request->selId;
        $user       = User::where('id',$id)->select('username','first_name','last_name')->first();
        $msg        = 'error';
        $name       = "";
        $html       =  "";
        if(count($user) > 0)
        {

            if(Auth::user()->hasRole('user'))
            {
                $name               = $user->username;
                Referral::$cmbUser  = Auth::user()->id;
                $selId              = Auth::user()->id;
            }
            else
            {
                $name               = ucfirst($user->first_name).' '.ucfirst($user->last_name).' | '.$user->username;
                Referral::$cmbUser  = $selId;
            }

            $result             = ReDeposit::getDetails('','',$id,'user');

            $deposits   = $result['result1'];
            $data       = $result['result2'];
            $msg        = 'success';
            $html       .= '<table class="table table-striped b-t">
                <thead>
                    <tr>
                        <th class="text-center">Deposit Id</th>
                        <th class="text-center">Plan Name</th>
                        <th class="text-center" title="Date of Deposit">Date of Dep</th>
                        <th class="text-center">Period</th>
                        <th class="text-center">Deposit Amt ($)</th>
                        <th class="text-center" title="Interest on Maturity">Interest on Maturity ($)</th>
                        <th class="text-center">Total ($)</th>';
                        if($selId > 0)
                        {
                            $html  .= '<th class="text-center">Commission (%)</th><th class="text-center">Commission Earn ($)</th>';
                        }

                $html .= '</tr>
                </thead>
                <tbody>';
                    if(count($deposits) > 0)
                    {
                        $a = 1;
                        $redTitle = [];
                        foreach($deposits as $singleRecord)
                        {
                            $html .= '<tr>
                                <td class="text-info"><a href="javascript:void('.$singleRecord->depositid.')">'.$singleRecord->depositno.'</a></td>
                                <td class="text-info"><span class="cls_tooltip" data-html="true" data-toggle="tooltip" title="'.$singleRecord->plan_name.'">'.str_limit($singleRecord->plan_name,40).'</span>
                                <br><span class="text-success">'.ucwords(str_replace('_', ' ', ucwords($singleRecord->status=="approved" ? "active" :$singleRecord->status )));
                                if(@$singleRecord->description != '')
                                {

                                   $html .= '<a href="javascript:void(0);" data-toggle="tooltip" title="'.$singleRecord->description.'" class="text-danger cls_tooltip"> <i class="material-icons text-success">&#xE88F;</i></a>';
                                }
                                $html .= '</span>

                                </td>
                                <td class="text-info" nowrap="nowrap">'.dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString().'</td>
                                <td class="text-info">-</td>
                                <td class="text-info" nowrap="nowrap">$ '.number_format($singleRecord->amount,2).'</td>
                                <td class="text-info">-</td>
                                <td class="text-info" nowrap="nowrap">$ '.number_format($singleRecord->amount,2).'</td>';
                                if($selId > 0)
                                {
                                    $html .= '<td class="text-info" nowrap="nowrap">-</td><td class="text-info" nowrap="nowrap">-</td>';
                                }

                            $html .= '</tr>';

                        $redTitle[$singleRecord->depositid] = 'Deposit';

                        if(!Auth::user()->hasRole('user'))
                        {
                            $withdraws = Withdraw::where('depositid',$singleRecord->depositid)
                            ->where('withdraw_type','deposit')
                            ->where('status','approved')
                            ->where('created_by',$id)
                            ->first();
                        }
                        
                        $flag = 0;
                        foreach($data as $singleRecord2)
                        {
                            if($singleRecord2['depositid'] == $singleRecord->depositid)
                            {
                                $textWithdraw = '';
                                if(!Auth::user()->hasRole('user'))
                                {
                                    if(count($withdraws) > 0)
                                    {
                                        if(dispayTimeStamp($withdraws->created_at)->toDateString() >= dispayTimeStamp($singleRecord2['startDate'])->toDateString()  && dispayTimeStamp($withdraws->created_at)->toDateString() <= dispayTimeStamp($singleRecord2['endDate'])->toDateString())
                                        {
                                            if($flag == 0)
                                            {
                                                $textWithdraw = '<b><span class="text-danger"> * Withdrawal Deposit of <u>$ '.number_format($withdraws->amount,2).'</u> on date <u>'.dispayTimeStamp($withdraws->created_at)->toFormattedDateString().'</u> </span></b>';
                                                $flag = 1;
                                            }
                                        }
                                        else
                                        {
                                            //$textWithdraw = '<b><span class="text-danger"> * Withdrawal Deposit of <u>$ '.number_format($withdraws->amount,2).'</u> on date <u>'.dispayTimeStamp($withdraws->created_at)->toFormattedDateString().'</u> </span></b>';
                                            //$flag = 1;
                                        }
                                    }
                                }

                                $delApplySt = '';
                                $delApplyEn = '';
                                if($singleRecord2['depositspecialstatus'] == 1)
                                {
                                    $delApplySt = '<del>';
                                    $delApplyEn = '</del>';
                                }


                                $total = $singleRecord2['amount'] + $singleRecord2['totalInterest'];

                                $html .= '<tr>
                                <td colspan="4" align="right" nowrap="nowrap">'.$delApplySt.' '.dispayTimeStamp($singleRecord2['startDate'])->toDayDateTimeString().'<b> to </b>'.dispayTimeStamp($singleRecord2['endDate'])->toDayDateTimeString().' '.$delApplyEn.' <br>'.$textWithdraw.'</td>';
                                $html .= '<td nowrap="nowrap">'.$delApplySt.' $ '.number_format($singleRecord2['amount'],2).' <b>'.$redTitle[$singleRecord->depositid].'</b>'.$delApplyEn.'</td>
                                <td nowrap="nowrap">'.$delApplySt.'$ '.number_format($singleRecord2['totalInterest'],2).' '.$delApplyEn.'</td>
                                <td nowrap="nowrap">'.$delApplySt.'$ '.number_format($total,2).' '.$delApplyEn.'</td>';

                                if($selId > 0)
                                {
                                    $html .= '<td nowrap="nowrap">'.number_format($singleRecord2['userLevelCommisionRate'],2).' %</td><td nowrap="nowrap">$ '.number_format($singleRecord2['userLevelCommision'],2).' <b>'.ucwords($singleRecord2['userLevelCommisionStatus']).'</b></td>';
                                }
                                
                                $html .= '</tr>';

                                if($redTitle[$singleRecord->depositid] == "Deposit")
                                {
                                    $redTitle[$singleRecord->depositid] = 'Redeposit';
                                }
                            }
                        }
                        $a++;
                    }
                }
                else
                {
                    $html .= '<tr><td class="text-center" colspan="7">No Records !</td></tr>';
                }
                $html .= '</tbody>
            </table>';

        }

        echo json_encode(array('msg' => $msg ,'name' => $name,'html' => $html ));
    }
}
