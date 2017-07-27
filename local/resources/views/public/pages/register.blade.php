@extends('layouts.app')
@section('title', 'Register')
@section('content')
<?php
    $page_name          = 'user';
    $first_name         = "";
    $last_name          = "";
    $username           = "";
    $dob                = "";
    $city               = "";
    $bitcoin_id         = "";
    $sec_answer         = "";
    $sec_question       = "";
    $state              = "";
    $email              = "";
    $email_confirmation = "";
    $gmale              = true;
    $gfemale            = false;
    $coucod             = "";
    $countrycode        = "";
    $stacod             = "";
    $citycod            = "";
    $zip                = "";
    $address            = "";
    $phone              = "";
    $status             = 'active';
    $btn_status         = 'Register User';
    $submit_link        = '/login/register/store';
    $eid                = "";
    $email_on_add_edit  = 'readonly';
?>
<div class="col-md-8 col-md-offset-2 mh-450">
<div class="panel panel-default">
<div class="panel-heading">Register User</div>
            <div class="box">
            <div class="box-divider m-a-0"></div>
                <div class="panel-body">
                    {!! Form::open(array('url' => $submit_link, 'class' => '' , 'id' => 'new_user_register' )) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}
                    {{ Form::hidden('mode', $mode, array('id' => 'eid')) }}

                        <div class="form-group row {{ $errors->has('referral') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Referrer</label>
                            <div class="col-sm-9">
                                {{ Form::text('referral', $referral, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'referral' , 'placeholder' => 'Referrer' , $email_on_add_edit => $email_on_add_edit]) }}
                                @if ($errors->has('referral'))
                                    <span class="help-block">{{ $errors->first('referral') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('first_name') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">First Name *</label>
                            <div class="col-sm-9">
                                {{ Form::text('first_name', $first_name, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'first_name' , 'placeholder' => 'First Name']) }}
                                @if ($errors->has('first_name'))
                                    <span class="help-block">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('last_name') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Last Name *</label>
                            <div class="col-sm-9">
                                {{ Form::text('last_name', $last_name, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'last_name' , 'placeholder' => 'Last Name']) }}
                                @if ($errors->has('last_name'))
                                    <span class="help-block">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('username') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Username *</label>
                            <div class="col-sm-9">
                                {{ Form::text('username', $username, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'username' , 'placeholder' => 'Username']) }}
                                @if ($errors->has('username'))
                                    <span class="help-block">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">User Email Address *</label>
                            <div class="col-sm-9">
                                    {{ Form::text('email', $email, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'email' , 'placeholder' => 'User Email Address']) }}
                                @if ($errors->has('email'))
                                    <span class="help-block">{{ $errors->first('email') }}</span>
                                @endif
                                <span>Note: Kindly do not use aol.com email address Or you will not receive ANY emails or ANY updates AT ALL - please consider gmail.com or secure protonmail.com</span>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('email_confirmation') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Confirm Email Address *</label>
                            <div class="col-sm-9">
                                    {{ Form::text('email_confirmation', $email_confirmation, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'email_confirmation' , 'placeholder' => 'Confirm User Email Address']) }}
                                @if ($errors->has('email_confirmation'))
                                    <span class="help-block">{{ $errors->first('email_confirmation') }}</span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Password *</label>
                            <div class="col-sm-9">
                                {{ Form::password('password', ['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'password' ,'placeholder' => 'Password']) }}
                                @if ($errors->has('password'))
                                    <span class="help-block">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Confirm Password *</label>
                            <div class="col-sm-9">
                                {{ Form::password('password_confirmation',['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'password_confirmation', 'placeholder' => 'Confirm Password']) }}
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('country') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Country *</label>
                                <div class="col-sm-9">
                                    {{ Form::select('country', $countries, $coucod, array('id' => 'country','class' => 'form-control col-md-7 col-xs-12 select2' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                @if ($errors->has('country'))
                                    <span class="help-block">{{ $errors->first('country') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Cell Phone *</label>
                            <div class="col-sm-9">
                                    {{ Form::text('countrycode', $countrycode, ['class' => 'form-control col-md-2 col-xs-2', 'readonly' => 'readonly' , 'style' => 'width:25%;' ,'id' => 'countrycode' , 'placeholder' => 'Country Code']) }}
                                    {{ Form::text('phone', $phone, ['class' => 'form-control col-md-10 col-xs-10 phone', 'style' => 'width:75%;', 'id' => 'phone' , 'placeholder' => 'Enter phone number without country code']) }}
                                    @if ($errors->has('phone'))
                                        <span class="help-block">{{ $errors->first('phone') }}</span>
                                    @endif
                                    <span>Note: Enter phone number without country code. Enter only numbers in textbox.</span>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('bitcoin_id') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Bitcoin Wallet Address <span title="If you don't have a Bitcoin Wallet Address Please leave blank" data-toggle="tooltip" data-placement="top"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
                                <div class="col-sm-9">
                                    {{ Form::text('bitcoin_id', $bitcoin_id, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'bitcoin_id' , 'placeholder' => 'Bitcoin Wallet Address']) }}
                                @if ($errors->has('bitcoin_id'))
                                    <span class="help-block">{{ $errors->first('bitcoin_id') }}</span>
                                @endif
                            </div>
                        </div>
                        

                        <div class="form-group row {{ $errors->has('sec_question') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Security Question *</label>
                                <div class="col-sm-9">
                                {{ Form::select('sec_question', $questions, $sec_question, array('id' => 'sec_question','class' => 'form-control col-md-7 col-xs-12 select2' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                @if ($errors->has('sec_question'))
                                    <span class="help-block">{{ $errors->first('sec_question') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('sec_answer') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label">Security Answer *</label>
                                <div class="col-sm-9">
                                    {{ Form::text('sec_answer', $sec_answer, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'sec_answer' , 'placeholder' => 'Security Answer']) }}
                                @if ($errors->has('sec_answer'))
                                    <span class="help-block">{{ $errors->first('sec_answer') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('terms') ? 'has-error' : '' }}">
                            <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    {{ Form::checkbox('terms','1','', ['class' => '' ,'id' => 'terms' , 'placeholder' => 'Terms and Conditions']) }} I accept the <a href="{{ config('services.SITE_DETAILS.SITE_AGGREMENT') }}" target="_blank">Terms and Conditions</a> 
                                @if ($errors->has('terms'))
                                    <span class="help-block">{{ $errors->first('terms') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="dker p-a text-right">
                            <button type="submit" class="btn btn-primary btn-go-fast-user-register"><span><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</span></button>
                        </div>
                    </form>
                </div>
            </div>
</div>
</div>
<div style="clear: both"></div>
@endsection