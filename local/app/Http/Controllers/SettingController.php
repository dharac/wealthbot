<?php
namespace App\Http\Controllers;
use App\PlanLevel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Setting;
use Session;
use Redirect;
use Auth;
use Validator;


class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $mode                                                     = 'View';
        $setting                                                  = [];
        $setting['site_email_verification']                       = Setting::getData('site_email_verification');
        $setting['sustainability_mode']                           = Setting::getData('sustainability_mode');
        $setting['deposit_approve_on_bitcoin_rate']               = Setting::getData('deposit_approve_on_bitcoin_rate');
        $setting['new_sustainability_mode_on_existing_old_plans'] = Setting::getData('new_sustainability_mode_on_existing_old_plans');
        $setting['founder_sustainablity']                         = Setting::getData('founder_sustainablity');
        $setting['lender']                                        = Setting::getData('lender');
        $setting['marketeer']                                     = Setting::getData('marketeer');
        $setting['wealthbot']                                     = Setting::getData('wealthbot');
        $setting['non_founder_sustainablity']                     = Setting::getData('non_founder_sustainablity');
        $setting['non_lender']                                    = Setting::getData('non_lender');
        $setting['non_marketeer']                                 = Setting::getData('non_marketeer');
        $setting['non_wealthbot']                                 = Setting::getData('non_wealthbot');
        $setting['backup_code']                                   = Setting::getData('backup_code');
        $setting['backup_sql']                                    = Setting::getData('backup_sql');
        $setting['sql_zip_password']                              = Setting::getData('sql_zip_password');
        $setting['user_delete_or_referrer_change']                = Setting::getData('user_delete_or_referrer_change');
        return view('admin.pages.new-setting',compact('mode','setting'));
    }

    public function editRecord()
    {
        $mode                                                       = 'Edit';
        $setting                                                    = [];
        $setting['site_email_verification']                         = Setting::getData('site_email_verification');
        $setting['sustainability_mode']                             = Setting::getData('sustainability_mode');
        $setting['deposit_approve_on_bitcoin_rate']                 = Setting::getData('deposit_approve_on_bitcoin_rate');
        $setting['new_sustainability_mode_on_existing_old_plans']   = Setting::getData('new_sustainability_mode_on_existing_old_plans');
        $setting['backup_code']                                     = Setting::getData('backup_code');
        $setting['backup_sql']                                      = Setting::getData('backup_sql');
        $setting['sql_zip_password']                                = Setting::getData('sql_zip_password');
        $setting['user_delete_or_referrer_change']                  = Setting::getData('user_delete_or_referrer_change');
        $setting['founder_sustainablity']                           = Setting::getData('founder_sustainablity');
        $setting['lender']                                          = Setting::getData('lender');
        $setting['marketeer']                                       = Setting::getData('marketeer');
        $setting['wealthbot']                                       = Setting::getData('wealthbot');
        $setting['non_founder_sustainablity']                       = Setting::getData('non_founder_sustainablity');
        $setting['non_lender']                                      = Setting::getData('non_lender');
        $setting['non_marketeer']                                   = Setting::getData('non_marketeer');
        $setting['non_wealthbot']                                   = Setting::getData('non_wealthbot');
        return view('admin.pages.new-setting',compact('mode','setting'));
    }

    public function update(Request $request)
    {
        $keys = array('deposit_approve_on_bitcoin_rate','new_sustainability_mode_on_existing_old_plans','site_email_verification','sustainability_mode','backup_code','backup_sql','sql_zip_password','user_delete_or_referrer_change','founder_sustainablity','lender','marketeer','wealthbot','non_founder_sustainablity','non_lender','non_marketeer','non_wealthbot');

        $validator = $this->validate($request, [
            'sql_zip_password'                      => 'required',
            'user_delete_or_referrer_change'        => 'required',
            ],[
                'sql_zip_password.required'=> 'The SQL ZIP Password field is required.',
                'user_delete_or_referrer_change.required'=> 'The User Delete or Referrer change Password field is required.'
        ]);

        if($request->founder_sustainablity == 1)
        {
            $validator = $this->validate($request, [
            'lender'     => 'required',
            'marketeer'  => 'required',
            'wealthbot'  => 'required',
            ],[
                'lender.required'       => 'The Founder Sustainablity Lender field is required.',
                'marketeer.required'    => 'The Founder Sustainablity Marketeer field is required.',
                'wealthbot.required'    => 'The Founder Sustainablity wealthbot field is required.'
            ]);
        }
        if($request->non_founder_sustainablity == 1)
        {
            $validator = $this->validate($request, [
            'non_lender'    => 'required',
            'non_marketeer' => 'required',
            'non_wealthbot' => 'required',
            ],[
                'non_lender.required'       => 'The Non Founder Sustainablity Lender field is required.',
                'non_marketeer.required'    => 'The Non Founder Sustainablity Marketeer field is required.',
                'non_wealthbot.required'    => 'The Non Founder Sustainablity wealthbot field is required.'
            ]);
        }

        foreach ($keys as $key) 
        {    
            $value = $request->$key;
            if($value == "" || $value == null) { $value = 0; }

            $status = Setting::create([
                'data_key'          => $key,
                'data_value'        => $value,
                'created_by'        => Auth::user()->id,
                'modified_by'       => Auth::user()->id,
            ]);
        }

        if($status)
        {
            Session::flash('message', 'Success! Settings Updated.');
            Session::flash('alert-class', 'alert-success');
        }
        else
        {
            Session::flash('message', 'Error! Something went wrong.');
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::to('admin/setting');
    }
}
