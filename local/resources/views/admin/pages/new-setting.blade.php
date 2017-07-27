<?php 
$page_name = 'setting';
$primary_key = 'setid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name).'s')
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $eid                                            = 0;
    $singleRecords                                  = $setting;
    $site_email_verification                        = "";
    $sustainability_mode                            = "";
    $backup_code                                    = "";
    $site_email_verification                        = $singleRecords['site_email_verification'];
    $deposit_approve_on_bitcoin_rate                = $singleRecords['deposit_approve_on_bitcoin_rate'];
    $new_sustainability_mode_on_existing_old_plans  = $singleRecords['new_sustainability_mode_on_existing_old_plans'];
    $sustainability_mode                            = $singleRecords['sustainability_mode'];
    $backup_code                                    = $singleRecords['backup_code'];
    $backup_sql                                     = $singleRecords['backup_sql'];
    $sql_zip_password                               = $singleRecords['sql_zip_password'];
    $user_delete_or_referrer_change                 = $singleRecords['user_delete_or_referrer_change'];
    $founder_sustainablity                          = $singleRecords['founder_sustainablity'];
    $founder_sustainablity_lender                   = $singleRecords['lender'];
    $founder_sustainablity_marketeer                = $singleRecords['marketeer'];
    $founder_sustainablity_wealthbot                = $singleRecords['wealthbot'];
    $non_founder_sustainablity                      = $singleRecords['non_founder_sustainablity'];
    $non_founder_sustainablity_lender               = $singleRecords['non_lender'];
    $non_founder_sustainablity_marketeer            = $singleRecords['non_marketeer'];
    $non_founder_sustainablity_wealthbot            = $singleRecords['non_wealthbot'];
    $btn_status                                     =   'Save '.ucfirst($page_name).'s';
    $submit_link                                    =   'admin/'.$page_name.'/update';
}
else
{
    $singleRecords                                  = $setting;
}
?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="row p-a">
                    <div class="col-md-6 text-left row col-xs-6">
                        <div class="box-header"><h2>{{ $mode.' '.ucfirst($page_name) }}s</h2></div>
                    </div>
                    @if($mode == 'View')
                    <div class="col-md-6 text-right col-xs-6">
                        <a href="{{ URL('admin/'.$page_name.'/edit/') }}" class="md-btn md-fab m-b-sm cyan" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>
                    </div>
                    @endif
                </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    @if($mode == 'Add' || $mode == 'Edit')
                    {!! Form::open(array('url' => $submit_link)) !!}
                    <div class="form-group row {{ $errors->has('site_email_verification') ? 'has-danger' : '' }}">
                        <label class="col-sm-3 form-control-label">Site Email Verification</label>
                        <div class="col-sm-3">
                            <label class="ui-switch ui-switch-lg green m-t-xs m-r">
                                {{ Form::checkbox('site_email_verification', '1', $site_email_verification , ['class' => 'flat' ,'id' => 'site_email_verification' ]) }}
                                <i></i>
                            </label>
                            @if ($errors->has('site_email_verification'))
                            <span class="parsley-required">{{ $errors->first('site_email_verification') }}</span>
                            @endif
                        </div>

                        <div class="col-sm-4">
                            <br>
                            {{ config('services.SITE_DETAILS.SITE_NAME') }} Site Email Verification
                        </div>
                    </div>

                    <div class="form-group row {{ $errors->has('sustainability_mode') ? 'has-danger' : '' }}">
                        <label class="col-sm-3 form-control-label">Sustainability Mode</label>
                        <div class="col-sm-3">
                            <label class="ui-switch ui-switch-lg green m-t-xs m-r"> {{ Form::checkbox('sustainability_mode', '1', $sustainability_mode , ['class' => 'flat' ,'id' => 'sustainability_mode' ]) }}<i></i> </label>
                            @if ($errors->has('sustainability_mode'))<span class="parsley-required">{{ $errors->first('sustainability_mode') }}</span>@endif </div>
                            <div class="col-sm-6">
                                <span class="text-success">When On</span> - Commission will be calculated on Interest earned on Deposit/Redeposit<br>
                                <span class="text-danger">When Off</span> - Commission will be calculated on Deposit/Redeposit Amount
                            </div>        
                        </div>

                        <div class="form-group row {{ $errors->has('backup_code') ? 'has-danger' : '' }}">
                            <label class="col-sm-3 form-control-label">Weekly Code Backup</label>
                            <div class="col-sm-3">
                                <label class="ui-switch ui-switch-lg green m-t-xs m-r">{{ Form::checkbox('backup_code', '1', $backup_code , ['class' => 'flat' ,'id' => 'backup_code' ]) }}<i></i> </label>
                                @if ($errors->has('backup_code')) <span class="parsley-required">{{ $errors->first('backup_code') }}</span> @endif </div>
                                <div class="col-sm-6">Weekly Backup the Code</div>
                            </div>

                            <div class="form-group row {{ $errors->has('backup_sql') ? 'has-danger' : '' }}">
                                <label class="col-sm-3 form-control-label">Daily Sql Backup</label>
                                <div class="col-sm-3">
                                    <label class="ui-switch ui-switch-lg green m-t-xs m-r">{{ Form::checkbox('backup_sql', '1', $backup_sql , ['class' => 'flat' ,'id' => 'backup_sql' ]) }}<i></i></label>
                                    @if ($errors->has('backup_sql'))
                                    <span class="parsley-required">{{ $errors->first('backup_sql') }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    Daily Backup the Sql
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('sql_zip_password') ? 'has-danger' : '' }}">
                                <label class="col-sm-3 col-md-3 form-control-label">SQL ZIP / CODE ZIP Password *</label>
                                <div class="col-sm-3 col-md-3">
                                    {{ Form::text('sql_zip_password', $sql_zip_password, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'sql_zip_password' , 'placeholder' => 'Password']) }}
                                    @if ($errors->has('sql_zip_password'))
                                    <span class="parsley-required">{{ $errors->first('sql_zip_password') }}</span>
                                    @endif
                                </div>
                            </div> 

                            <div class="form-group row {{ $errors->has('user_delete_or_referrer_change') ? 'has-danger' : '' }}">
                                <label class="col-sm-3 col-md-3 form-control-label">User Delete or Referrer change Password *</label>
                                <div class="col-sm-3 col-md-3">
                                    {{ Form::text('user_delete_or_referrer_change', $user_delete_or_referrer_change, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'user_delete_or_referrer_change' , 'placeholder' => 'Password']) }}
                                    @if ($errors->has('user_delete_or_referrer_change'))
                                    <span class="parsley-required">{{ $errors->first('user_delete_or_referrer_change') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('deposit_approve_on_bitcoin_rate') ? 'has-danger' : '' }}">
                                <label class="col-sm-3 form-control-label">Deposit approve on Bitcoin Rate</label>
                                <div class="col-sm-3">
                                    <label class="ui-switch ui-switch-lg green m-t-xs m-r"> {{ Form::checkbox('deposit_approve_on_bitcoin_rate', '1', $deposit_approve_on_bitcoin_rate , ['class' => 'flat' ,'id' => 'deposit_approve_on_bitcoin_rate' ]) }}<i></i> </label>
                                    @if ($errors->has('deposit_approve_on_bitcoin_rate'))<span class="parsley-required">{{ $errors->first('deposit_approve_on_bitcoin_rate') }}</span>@endif </div>
                                </div>

                                <div class="form-group row {{ $errors->has('new_sustainability_mode_on_existing_old_plans') ? 'has-danger' : '' }}">
                                    <label class="col-sm-3 form-control-label">New Sustainability mode on existing old Plans</label>
                                    <div class="col-sm-3">
                                        <label class="ui-switch ui-switch-lg green m-t-xs m-r"> {{ Form::checkbox('new_sustainability_mode_on_existing_old_plans', '1', $new_sustainability_mode_on_existing_old_plans , ['class' => 'flat' ,'id' => 'new_sustainability_mode_on_existing_old_plans' ]) }}<i></i> </label>
                                        @if ($errors->has('new_sustainability_mode_on_existing_old_plans'))<span class="parsley-required">{{ $errors->first('new_sustainability_mode_on_existing_old_plans') }}</span>@endif </div>
                                    </div>

                                    <div class="form-group row {{ $errors->has('founder_sustainablity') ? 'has-danger' : '' }}">
                                        <label class="col-sm-3 form-control-label">Founder New Sustainability Mode</label>
                                        <div class="col-sm-3">
                                            <label class="ui-switch ui-switch-lg green m-t-xs m-r">{{ Form::checkbox('founder_sustainablity', $founder_sustainablity, $founder_sustainablity , ['class' => 'flat founder_sustainablity' ,'id' => 'founder_sustainablity' ]) }}<i></i></label>
                                            @if ($errors->has('founder_sustainablity'))
                                            <span class="parsley-required">{{ $errors->first('founder_sustainablity') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="founder_sustainablity_textbox @if($founder_sustainablity != 1) hide @endif">
                                        <div class="form-group  row {{ $errors->has('lender') ? 'has-danger' : '' }}">
                                            <label class="col-sm-3 col-md-3 form-control-label">Lender % *</label>
                                            <div class="col-sm-3 col-md-3">
                                                {{ Form::text('lender', $founder_sustainablity_lender, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'lender' , 'placeholder' => 'Lender']) }}
                                                @if ($errors->has('lender'))
                                                <span class="parsley-required">{{ $errors->first('lender') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('marketeer') ? 'has-danger' : '' }}">
                                            <label class="col-sm-3 col-md-3 form-control-label">Marketeer % *</label>
                                            <div class="col-sm-3 col-md-3">
                                                {{ Form::text('marketeer', $founder_sustainablity_marketeer, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'marketeer' , 'placeholder' => 'Marketeer']) }}
                                                @if ($errors->has('marketeer'))
                                                <span class="parsley-required">{{ $errors->first('marketeer') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('wealthbot') ? 'has-danger' : '' }}">
                                            <label class="col-sm-3 col-md-3 form-control-label">Wealthbot % *</label>
                                            <div class="col-sm-3 col-md-3">
                                                {{ Form::text('wealthbot', $founder_sustainablity_wealthbot, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'wealthbot' , 'placeholder' => 'Wealthbot']) }}
                                                @if ($errors->has('wealthbot'))
                                                <span class="parsley-required">{{ $errors->first('wealthbot') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group row {{ $errors->has('non_founder_sustainablity') ? 'has-danger' : '' }}">
                                        <label class="col-sm-3 form-control-label">Non-Founder New Sustainability Mode</label>
                                        <div class="col-sm-3">
                                            <label class="ui-switch ui-switch-lg green m-t-xs m-r">{{ Form::checkbox('non_founder_sustainablity', $non_founder_sustainablity, $non_founder_sustainablity , ['class' => 'flat non_founder_sustainablity' ,'id' => 'non_founder_sustainablity' ]) }}<i></i></label>
                                            @if ($errors->has('non_founder_sustainablity'))
                                            <span class="parsley-required">{{ $errors->first('non_founder_sustainablity') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="non_founder_sustainablity_textbox @if($non_founder_sustainablity != 1) hide @endif">
                                        <div class="form-group  row {{ $errors->has('non_lender') ? 'has-danger' : '' }}">
                                            <label class="col-sm-3 col-md-3 form-control-label">Lender % *</label>
                                            <div class="col-sm-3 col-md-3">
                                                {{ Form::text('non_lender', $non_founder_sustainablity_lender, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'non_lender' , 'placeholder' => 'Lender']) }}
                                                @if ($errors->has('lender'))
                                                <span class="parsley-required">{{ $errors->first('non_lender') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group  row {{ $errors->has('non_marketeer') ? 'has-danger' : '' }}">
                                            <label class="col-sm-3 col-md-3 form-control-label">Marketeer % *</label>
                                            <div class="col-sm-3 col-md-3">
                                                {{ Form::text('non_marketeer', $non_founder_sustainablity_marketeer, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'non_marketeer' , 'placeholder' => 'Marketeer']) }}
                                                @if ($errors->has('non_marketeer'))
                                                <span class="parsley-required">{{ $errors->first('non_marketeer') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group  row {{ $errors->has('non_wealthbot') ? 'has-danger' : '' }}">
                                            <label class="col-sm-3 col-md-3 form-control-label">Wealthbot % *</label>
                                            <div class="col-sm-3 col-md-3">
                                                {{ Form::text('non_wealthbot', $non_founder_sustainablity_wealthbot, ['class' => 'form-control col-md-6 col-xs-6' ,'id' => 'non_wealthbot' , 'placeholder' => 'Wealthbot']) }}
                                                @if ($errors->has('non_wealthbot'))
                                                <span class="parsley-required">{{ $errors->first('non_wealthbot') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="dker p-a text-right">
                                    <a href="{{ URL('admin/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                                    <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                                </div>
                                {!! Form::close() !!}
                                @else
                                <table class="table table-striped b-t">
                                    <thead>
                                        <tr>
                                            <th>Setting Name</th>
                                            <th>Value</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($singleRecords as $key => $value)
                                        <?php $nm = ucwords(str_replace('_', ' ', $key)); ?>
                                        @if($key == 'site_email_verification')
                                        <tr>
                                            <td>{{ $nm }}</td>
                                            <td>@if($value == '1') <span class="text-success">ON</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td>WealthBot Site Email Verification</td>
                                        </tr>
                                        @elseif($key == 'sustainability_mode')
                                        <tr>
                                            <td>{{ $nm }}</td>
                                            <td>@if($value == '1') <span class="text-success">ON</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td>
                                                <span class="text-success">When On</span> - Commission will be calculated on Interest earned on Deposit/Redeposit<br>
                                                <span class="text-danger">When Off</span> - Commission will be calculated on Deposit/Redeposit Amount
                                            </td>
                                        </tr>
                                        @elseif($key == 'deposit_approve_on_bitcoin_rate')
                                        <tr>
                                            <td>{{ $nm }}</td>
                                            <td>@if($value == '1') <span class="text-success">ON</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td></td>
                                        </tr>                                    
                                        @elseif($key == 'new_sustainability_mode_on_existing_old_plans')
                                        <tr>
                                            <td>{{ $nm }}</td>
                                            <td>@if($value == '1') <span class="text-success">ON</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td></td>
                                        </tr>
                                        @elseif($key == 'founder_sustainablity')
                                        <tr>
                                            <td>Founder New Sustainability Mode</td>
                                            <td>
                                                @if($singleRecords['founder_sustainablity'] == 1)
                                                <span>{{ $singleRecords['lender'] }} %</span> Lender <br>
                                                <span>{{ $singleRecords['marketeer'] }} %</span> Marketeer<br>
                                                <span>{{ $singleRecords['wealthbot'] }} %</span> Wealthbot<br>
                                                @else
                                                <span class="text-danger">OFF</span>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                        @elseif($key == 'non_founder_sustainablity')
                                        <tr>
                                            <td>Non-Founder New Sustainability Mode</td>
                                            <td>
                                                @if($singleRecords['non_founder_sustainablity'] == 1)
                                                @if($singleRecords['non_founder_sustainablity'] == 1)
                                                <span>{{ $singleRecords['non_lender'] }} %</span> Lender <br>
                                                <span>{{ $singleRecords['non_marketeer'] }} %</span> Marketeer<br>
                                                <span>{{ $singleRecords['non_wealthbot'] }} %</span> Wealthbot<br>
                                                @endif
                                                @else
                                                <span class="text-danger">OFF</span>
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                        @elseif($key == 'backup_code')
                                        <tr>
                                            <td>{{ $nm }}</td>
                                            <td>@if($value == '1') <span class="text-success">ON</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td></td>
                                        </tr>
                                        @elseif($key == 'backup_sql')
                                        <tr>
                                            <td>{{ $nm }}</td>
                                            <td>@if($value == '1') <span class="text-success">ON</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td></td>
                                        </tr>
                                        @elseif($key == 'sql_zip_password')
                                        <tr>
                                            <td>SQL ZIP / CODE ZIP Password</td>
                                            <td>@if($value != '') <span class="text-success">Set Password</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td></td>
                                        </tr>
                                        @elseif($key == 'user_delete_or_referrer_change')
                                        <tr>
                                            <td>User Delete Or Referrer Change</td>
                                            <td>@if($value != '') <span class="text-success">Set Password</span> @else <span class="text-danger">OFF</span> @endif</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/setting.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop