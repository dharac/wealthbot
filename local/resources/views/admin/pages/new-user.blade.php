<?php
$titlePage = $mode.' '.'User';
$primary_key = 'id';
if($mode_type  == 'profile')
{
    $titlePage = 'Edit Profile';
}
$page_name          = 'user';
$profileUpdateRead  = "";
if($mode == 'Edit')
{
    $gender     = $user->gender;
    $gmale = "";
    $gfemale = "";
    if($gender == 'male')
    {
        $gmale = true;
        $gfemale = false;
    }
    else if($gender == 'female')
    {
        $gmale      = false;
        $gfemale    = true;
    }
    $dob = "";
    if($user->dob != "" || $user->dob != null)
    {
        $dob                    = Carbon\Carbon::parse($user->dob)->format('m-d-Y');
    }
    $first_name             = $user->first_name;
    $last_name              = $user->last_name;
    $username               = $user->username;
    $referral               = $user->referral;
    $city                   = $user->city;
    $state                  = $user->state;
    $bitcoin_id             = $user->bitcoin_id;
    $sec_question           = $user->sec_question;
    $sec_answer             = $user->sec_answer;
    $email                  = $user->email;
    $coucod                 = $user->coucod;
    $stacod                 = $user->stacod;
    $citycod                = $user->citycod;
    $zip                    = $user->zip;
    $address                = $user->address;
    $role                   = $user->role_id;
    $phone                  = $user->phone;
    $countrycode            = $user->cou_code;
    $status                 = $user->status;
    $founder                = $user->founder;
    $btn_status             = 'Update User';
    $submit_link            = 'admin/user/update';
    $eid                    = $user->id;
    $email_on_add_edit      = 'readonly';

    if($mode_type  == 'profile')
    {
        $profileUpdateRead  = 'readonly';
        $btn_status         = 'Save Changes';
        $submit_link        = 'admin/user/update-profile';
    }

    $refName = config('services.SITE_DETAILS.SITE_NAME');
    if(count($refData) > 0)
    {
        $refName = ucfirst($refData->first_name).' '.ucfirst($refData->last_name).' ('.$refData->username.')';
    }
}
else
{
    $first_name         = "";
    $last_name          = "";
    $username           = "";
    $referral           = "";
    $dob                = "";
    $city               = "";
    $bitcoin_id         ="";
    $sec_answer         = "";
    $sec_question       = "";
    $state              = "";
    $email              = "";
    $gmale              = "";
    $gfemale            = "";
    $coucod             = "";
    $refName            = "";
    $stacod             = "";
    $citycod            = "";
    $zip                = "";
    $address            = "";
    $phone              = "";
    $countrycode        = "";
    $role               = "";
    $status             = 'active';
    $founder            = "";
    $btn_status         = 'Add User';
    $submit_link        = 'admin/user/store';
    $eid                = "";
    $email_on_add_edit = '';
}
?>
@extends('admin.parts.layout')
@section('adminTitle', $titlePage)
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">

            <div class="box">

            <div class="row p-a">
            <div class="col-md-6 col-xs-12 text-left row">
                <div class="box-header">
                    @if($mode_type  == 'profile')
                    <h2>Edit Profile</h2>
                    @else
                    <h2>{{ $mode.' '.ucfirst($page_name) }}</h2>
                    @endif
                </div>
            </div>
            @if($mode == 'View')
            <div class="col-md-6 col-xs-12 text-right">
                <a class="md-btn md-fab m-b-sm blue" href="{{ url('admin/user') }}" title="Back to Users"><i class="material-icons">&#xE15E;</i></a>&nbsp;
                <a href="{{ URL('admin/'.$page_name.'/edit/'.$user->$primary_key.'') }}" class="md-btn md-fab m-b-sm cyan" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>&nbsp;
                <a href="javascript:void({{ $user->$primary_key }});" class="md-btn md-fab m-b-sm pink referral-report-popup" data-id="{{ $user->$primary_key }}" title="View Deposit"><i class="material-icons">&#xE3CA;</i></a>
                &nbsp;
                <a href="{{ URL('admin/user/referrals/'.$user->$primary_key.'') }}" title="Referrals Information" class="md-btn md-fab m-b-sm green-500"><i class="material-icons">&#xE335;</i></a>&nbsp;
                <a href="{{ url('admin/user/email/'.$user->$primary_key.'') }}" class="md-btn md-fab m-b-sm indigo" data-id="{{ $user->$primary_key }}" title="Send Email"><i class="material-icons">&#xE0BE;</i></a>
                &nbsp;
                <a href="{{ url('admin/user/panel/'.$user->$primary_key.'') }}" class="md-btn md-fab m-b-sm green-800" title="Go to User Panel"><i class="material-icons">&#xE853;</i></a>
                &nbsp;
                {!! Form::open(array('url' => 'admin/user/password/email' , 'style' =>     'float: right;display: inline-block;margin-left:5px;' )) !!}
                {{ Form::hidden('email', $user->email , array('id' => 'email')) }}
                <button class="md-btn md-fab m-b-sm warn" title="Reset Password" onclick="return confirm('Are you sure you want to reset this password ?');"><i class="material-icons">&#xE898;</i></button>
                {!! Form::close() !!}

            </div>
            @endif
        </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    @if($mode == 'Add' || $mode == 'Edit')
                    {!! Form::open(array('url' => $submit_link, 'class' => '' , 'id' => 'new_user')) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                        @if($mode_type  != 'profile')
                        <div class="form-group row {{ $errors->has('referral') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Referrer</label>
                            <div class="col-sm-9 col-xs-11">
                                {{ Form::text('referral', $refName, ['class' => 'form-control col-md-6 col-xs-10' ,'id' => 'autocomplete_referral' , 'placeholder' => 'Referrer' , 'autocomplete' => 'off' ]) }}

                                    {{ Form::hidden('referral_id', $referral, ['id' => 'autocomplete_referral_id' , 'placeholder' => 'Referrer Id']) }}

                                @if ($errors->has('referral'))
                                    <div id="selction-ajax"></div>
                                    <span class="parsley-required">{{ $errors->first('referral') }}</span>
                                @endif
                            </div>
                            
                            <div class="col-sm-1 col-xs-1" style="margin-top: 2px !important;"><a href="javascript:void(0)" title="Remove Referrer" class="btn btn-icon red btn-sm removeReferrer"><i class="fa fa-remove"></i></a></div>
                        </div>  
                        @endif

                        <div class="form-group row {{ $errors->has('first_name') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">First Name *</label>
                            <div class="col-sm-10">
                                {{ Form::text('first_name', $first_name, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'first_name' , 'placeholder' => 'First Name']) }}
                                @if ($errors->has('first_name'))
                                    <span class="parsley-required">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('last_name') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Last Name *</label>
                            <div class="col-sm-10">
                                {{ Form::text('last_name', $last_name, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'last_name' , 'placeholder' => 'Last Name']) }}
                                @if ($errors->has('last_name'))
                                    <span class="parsley-required">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('username') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Username *</label>
                            <div class="col-sm-10">
                                {{ Form::text('username', $username, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'username', $profileUpdateRead => $profileUpdateRead , 'placeholder' => 'Username']) }}
                                @if ($errors->has('username'))
                                    <span class="parsley-required">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">User Email Address *</label>
                            <div class="col-sm-10">
                                    {{ Form::text('email', $email, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'email','placeholder' => 'User Email']) }}
                                @if ($errors->has('email'))
                                    <span class="parsley-required">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($mode == 'Add')
                        <div class="form-group row {{ $errors->has('password') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Password *</label>
                            <div class="col-sm-10">
                                {{ Form::password('password', ['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'password' ,'placeholder' => 'Password']) }}
                                @if ($errors->has('password'))
                                    <span class="parsley-required">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password_confirmation') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Confirm Password *</label>
                            <div class="col-sm-10">
                                {{ Form::password('password_confirmation',['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'password_confirmation', 'placeholder' => 'Confirm Password']) }}
                                @if ($errors->has('password_confirmation'))
                                    <span class="parsley-required">{{ $errors->first('password_confirmation') }}</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="form-group row {{ $errors->has('gender') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Gender</label>
                                <div class="col-sm-10">
                                <label class="md-check">
                                    {{ Form::radio('gender', 'male', $gmale , ['class' => 'flat' ,'id' => 'genderM' ]) }}
                                    <i class="blue"></i>
                                    Male
                                </label>
                                <label class="md-check">
                                    {{ Form::radio('gender', 'female', $gfemale , ['class' => 'flat' ,'id' => 'genderF' ]) }}
                                    <i class="blue"></i>
                                    Female
                                </label>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('dob') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Date of Birth * <br>(MM/DD/YYYY)</label>
                                <div class="col-sm-10">
                                    {{ Form::text('dob', $dob, ['class' => 'form-control col-md-7 col-xs-12 dateValue' ,'id' => 'dob' , 'placeholder' => 'Date of Birth (MM/DD/YYYY) ']) }}
                                @if ($errors->has('dob'))
                                    <span class="parsley-required">{{ $errors->first('dob') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('address') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Address </label>
                            <div class="col-sm-10">
                                {{ Form::textarea('address', $address, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'address', 'rows' => '3', 'placeholder' => 'User Address' ]) }}
                            @if ($errors->has('address'))
                                <span class="parsley-required">{{ $errors->first('address') }}</span>
                            @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('city') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">City</label>
                                <div class="col-sm-10">
                                    {{ Form::text('city', $city, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'city' , 'placeholder' => 'City']) }}
                                @if ($errors->has('city'))
                                    <span class="parsley-required">{{ $errors->first('city') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('state') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">State/Province</label>
                                <div class="col-sm-10">
                                    {{ Form::text('state', $state, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'state' , 'placeholder' => 'State/Province']) }}
                                @if ($errors->has('state'))
                                    <span class="parsley-required">{{ $errors->first('state') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('zip') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Zip Code</label>
                                <div class="col-sm-10">
                                    {{ Form::text('zip', $zip, ['class' => 'form-control col-md-7 col-xs-12 zip' ,'id' => 'zip' , 'placeholder' => 'Zip Code']) }}
                                @if ($errors->has('zip'))
                                    <span class="parsley-required">{{ $errors->first('zip') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('country') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Country *</label>
                                <div class="col-sm-10">
                                    {{ Form::select('country', $countries, $coucod, array('id' => 'country','class' => 'form-control col-md-7 col-xs-12 select2' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                @if ($errors->has('country'))
                                    <span class="parsley-required">{{ $errors->first('country') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('phone') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Cell Phone</label>
                                <div class="col-sm-10">
                                    {{ Form::text('countrycode', $countrycode, ['class' => 'form-control col-md-2 col-xs-2', 'readonly' => 'readonly' , 'style' => 'width:20%;', 'id' => 'countrycode' , 'placeholder' => 'Country Code']) }}
                                    {{ Form::text('phone', $phone, ['class' => 'form-control col-md-10 col-xs-10 phone', 'style' => 'width:80%;', 'id' => 'phone' , 'placeholder' => 'User Phone']) }}
                                    @if ($errors->has('phone'))
                                        <span class="parsley-required">{{ $errors->first('phone') }}</span>
                                    @endif
                                    <span>Note: Enter phone number without country code. Enter only numbers in textbox.</span>
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('bitcoin_id') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Bitcoin Wallet Address</label>
                                <div class="col-sm-10">
                                    {{ Form::text('bitcoin_id', $bitcoin_id, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'bitcoin_id' , 'placeholder' => 'Bitcoin Wallet Address']) }}
                                @if ($errors->has('bitcoin_id'))
                                    <span class="parsley-required">{{ $errors->first('bitcoin_id') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('sec_question') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Security Question *</label>
                                <div class="col-sm-10">
                                {{ Form::select('sec_question', $questions, $sec_question, array('id' => 'sec_question','class' => 'form-control col-md-7 col-xs-12 select2' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                @if ($errors->has('sec_question'))
                                    <span class="parsley-required">{{ $errors->first('sec_question') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row {{ $errors->has('sec_answer') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Security Answer *</label>
                                <div class="col-sm-10">
                                    {{ Form::text('sec_answer', $sec_answer, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'sec_answer' , 'placeholder' => 'Security Answer']) }}
                                @if ($errors->has('sec_answer'))
                                    <span class="parsley-required">{{ $errors->first('sec_answer') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($mode_type  != 'profile')
                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Role</label>
                            <div class="col-sm-10">
                                {{ Form::select('role', $roles, $role, array('id' => 'country','class' => 'form-control col-md-7 col-xs-12')) }}
                            </div>
                        </div>

                        <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Founder</label>
                                <div class="col-sm-10">
                                <label class="md-check">
                                {{ Form::checkbox('founder', 1, $founder, ['class' => 'form-control']) }}
                                <i class="blue"></i>
                                Founder
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Status</label>
                            <div class="col-sm-10">
                                {{ Form::select('status', array('active' => 'Active','inactive' => 'Inactive' ), $status , ['class' => 'form-control' ]) }}
                            </div>
                        </div>
                        @endif

                        @if($mode  == 'Add')
                         <div class="form-group row {{ $errors->has('terms') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label"></label>
                                <div class="col-sm-9">
                                    <label class="md-check">
                                    {{ Form::checkbox('terms','1','', ['class' => '' ,'id' => 'terms' , 'placeholder' => 'Terms and Conditions']) }} <i class="indigo"></i>
                                    I accept the <a href="{{ config('services.SITE_DETAILS.SITE_AGGREMENT') }}" class="text-info" target="_blank">Terms and Conditions</a>  </label>
                                    <br>@if ($errors->has('terms'))
                                        <span class="parsley-required">{{ $errors->first('terms') }}</span>
                                    @endif
                            </div>
                        </div>
                        @endif

                        <div class="dker p-a text-right">
                            @if($mode_type  != 'profile')
                            <a href="{{ URL('admin/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                            @endif
                            <button type="submit" class="btn btn-fw primary btn-go-fast-user"><span><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</span></button>
                        </div>
                    </form>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>First Name</td>
                                    <th>{{ ucfirst($user->first_name) }}</th>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <th>{{ $user->last_name ? ucfirst($user->last_name) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <th>{{ $user->username }}</th>
                                </tr>
                                <tr>
                                    <td>Referrer</td>
                                    <th>
                                        @if($user->ufirst_name != "" || $user->ulast_name != "")
                                        <a href="javascript:void(0);" data-id="{{ $user->id }}" class="view-upper-level text-info">{{ ucfirst($user->ufirst_name) }} {{ ucfirst($user->ulast_name) }}</a>
                                        @else
                                        {{ config('services.SITE_DETAILS.SITE_NAME') }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Role</td>
                                    <th>{{ $user->display_name }}</th>
                                </tr>
                                <tr>
                                    <td>Email Address</td>
                                    @if($user->confirmed == '1')
                                    <th class="text-success" title="Email Verified" nowrap="nowrap"><i class="material-icons">&#xE8E8;</i> {{ $user->email }}</th>
                                    @else
                                    <th class="text-danger" title="Email Not Verified" nowrap="nowrap"><i class="material-icons">&#xE8AE;</i> {{ $user->email }}</th>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Cell Phone</td>
                                    <th>@if($user->phone != "") {{ $user->cou_code }} {{  $user->phone }} @else -  @endif</th>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <th>{{ $user->gender ? ucfirst($user->gender) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Date of Birth (MM/DD/YYYY)</td>
                                    <?php 
                                    $dob = "-";
                                    if($user->dob != null || $user->dob != "")
                                    {
                                        $dob    = Carbon\Carbon::parse($user->dob)->format('Y-m-d');
                                        $dob    = Carbon\Carbon::createFromFormat('Y-m-d', $dob);
                                        $dob    = $dob->format('m/d/Y');
                                    }
                                    ?>
                                    <th>{{ $dob }}</th>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <th>{{ $user->address ? $user->address : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <th>{{ $user->city ? ucwords($user->city) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <th>{{ $user->state ? ucwords($user->state) : '-' }}</th>
                                </tr>

                                <tr>
                                    <td>Country</td>
                                    <th>{{ $user->counm ? ucwords($user->counm) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>ZIP</td>
                                    <th>{{ $user->zip ? $user->zip : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Bitcoin Wallet Address</td>
                                    <th>{{ $user->bitcoin_id ? $user->bitcoin_id : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Security Question</td>
                                    <th>{{ $user->question ? $user->question : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Security Question Answer</td>
                                    <th>{{ $user->sec_answer ? $user->sec_answer : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Founder</td>
                                    <th>@if($user->founder == 1) Yes @else - @endif</th>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <th>
                                    @if($user->status == 'active')
                                        <span class="text-success" title="{{ ucfirst($user->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($user->status) }}</span>
                                    @elseif($user->status == 'inactive')
                                        <span class="text-danger" title="{{ ucfirst($user->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($user->status) }}</span>
                                    @else
                                        <span class="text-info" title="{{ ucfirst($user->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($user->status) }}</span>
                                    @endif
                                </th>
                                </tr>
                                <tr>
                                    <td>Registration Date</td>
                                    <th title="{{ dispayTimeStamp($user->created_at)->diffForHumans() }}">{{ $user->created_at->toDayDateTimeString() }}</th>
                                </tr>
                                <tr>
                                    <td>Modified Date</td>
                                    <th title="{{ dispayTimeStamp($user->updated_at)->diffForHumans() }}">{{ $user->updated_at->toDayDateTimeString() }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/user.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/referral-report.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop