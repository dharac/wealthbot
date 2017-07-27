<?php 
$page_name = 'Confirm Admin';


$request_page_name = '';
$id = '';
$mode = '';

$btn_status = ucfirst($page_name);
$submit_link = 'admin/user/confirm_admin';

$eid            ='';
$referral       ='';
$referral_id        ='';
$first_name='';
$last_name='';
$username='';
$email='';
$gender='';
$dob='';
$address='';
$city='';
$state='';
$zip='';
$country='';
$countrycode='';
$phone='';
$bitcoin_id='';
$sec_question='';
$sec_answer='';
$role='';
$founder='';
$status='';
$old_referral_id='';

if(!empty($confirm_details))
{   
    if($confirm_details['mode'] == 'delete')
    {
        $btn_status = ucfirst($confirm_details['mode'].' User');
        $request_page_name = $confirm_details['page_name'];

        $id = $confirm_details['id'];
        $mode = $confirm_details['mode'];

    }
    if($confirm_details['mode'] == 'referal_change')
    {
        $btn_status = ucfirst('change Referrer');

        $request_page_name = $confirm_details['page_name'];

        $id = $confirm_details['id'];
        $mode = $confirm_details['mode'];

        $eid            = $confirm_details['eid'];
        $referral       = $confirm_details['referral'];
        $referral_id    = $confirm_details['referral_id'];
        $first_name     = $confirm_details['first_name'];
        $last_name      = $confirm_details['last_name'];
        $username       = $confirm_details['username'];
        $email          = $confirm_details['email'];
        $gender         = $confirm_details['gender'];
        $dob            = $confirm_details['dob'];
        $address        = $confirm_details['address'];
        $city           = $confirm_details['city'];
        $state          = $confirm_details['state'];
        $zip            = $confirm_details['zip'];
        $country        = $confirm_details['country'];
        $countrycode    = $confirm_details['countrycode'];
        $phone          = $confirm_details['phone'];
        $bitcoin_id     = $confirm_details['bitcoin_id'];
        $sec_question   = $confirm_details['sec_question'];
        $sec_answer     = $confirm_details['sec_answer'];
        $role           = $confirm_details['role'];
        $founder        = $confirm_details['founder'];
        $status         = $confirm_details['status'];
        $old_referral_id= $confirm_details['old_referral_id'];
        $admin_confirmation_status = $confirm_details['admin_confirmation_status'];
    }
}
?>
@extends('admin.parts.layout')  
@section('adminTitle',ucfirst($page_name))
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header"> <h2><small></small></h2> </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('id', $id, array('id' => 'id')) }}
                    {{ Form::hidden('mode', $mode, array('mode' => 'mode')) }}
                    {{ Form::hidden('request_page_name', $request_page_name, array('request_page_name' => 'request_page_name')) }}
                    @if($confirm_details['mode'] == 'referal_change')
                    {{ Form::hidden('eid', $eid, array('eid' => 'eid')) }}
                    {{ Form::hidden('referral', $referral, array('referral' => 'referral')) }}
                    {{ Form::hidden('referral_id', $referral_id, array('referral_id' => 'referral_id')) }}
                    {{ Form::hidden('first_name', $first_name, array('first_name' => 'first_name')) }}
                    {{ Form::hidden('last_name', $last_name, array('last_name' => 'last_name')) }}
                    {{ Form::hidden('username', $username, array('username' => 'username')) }}
                    {{ Form::hidden('email', $email, array('email' => 'email')) }}
                    {{ Form::hidden('gender', $gender, array('gender' => 'gender')) }}
                    {{ Form::hidden('dob', $dob, array('dob' => 'dob')) }}
                    {{ Form::hidden('address', $address, array('address' => 'address')) }}
                    {{ Form::hidden('city', $city, array('city' => 'city')) }}
                    {{ Form::hidden('state', $state, array('state' => 'state')) }}
                    {{ Form::hidden('zip', $zip, array('zip' => 'zip')) }}
                    {{ Form::hidden('country', $country, array('country' => 'country')) }}
                    {{ Form::hidden('countrycode', $countrycode, array('countrycode' => 'countrycode')) }}
                    {{ Form::hidden('phone', $phone, array('phone' => 'phone')) }}
                    {{ Form::hidden('bitcoin_id', $bitcoin_id, array('bitcoin_id' => 'bitcoin_id')) }}
                    {{ Form::hidden('sec_question', $sec_question, array('sec_question' => 'sec_question')) }}
                    {{ Form::hidden('sec_answer', $sec_answer, array('sec_answer' => 'sec_answer')) }}
                    {{ Form::hidden('role', $role, array('role' => 'role')) }}
                    {{ Form::hidden('founder', $founder, array('founder' => 'founder')) }}
                    {{ Form::hidden('status', $status, array('status' => 'status')) }}
                    {{ Form::hidden('old_referral_id', $old_referral_id, array('old_referral_id' => 'old_referral_id')) }}
                    {{ Form::hidden('admin_confirmation_status', $admin_confirmation_status, array('admin_confirmation_status' => 'admin_confirmation_status')) }}

                    @endif
                    <div class="form-group row {{ $errors->has('password') ? 'has-danger' : '' }}">
                        <label class="col-sm-2 form-control-label">Password *</label>
                        <div class="col-sm-10">
                            {{ Form::password('password', ['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'password' ,'placeholder' => 'Password']) }}
                            @if ($errors->has('password'))
                            <span class="parsley-required">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div> 
                    <div class="dker p-a text-right">
                        <a href="{{ URL('admin/'.$request_page_name) }}" class="btn btn-fw info">Cancel</a>
                        <button type="submit" class="btn btn-fw primary" onclick="return confirm('Are you sure?')"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
