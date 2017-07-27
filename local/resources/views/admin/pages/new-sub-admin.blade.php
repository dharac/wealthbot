@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.'Sub Admin')
@section('adminBody')
<?php
$page_name = 'sub admin';
$primary_key = 'id';
if($mode == 'Edit')
{
    $singleRecord           =   $user;
    $eid                    =   $singleRecord->$primary_key;
 
    $first_name             = $singleRecord->first_name;
    $last_name             = $singleRecord->last_name;
    $username               = $singleRecord->username;
    $email                  = $singleRecord->email;
    $coucod                 = $singleRecord->coucod;

    $btn_status             = 'Update '.ucwords($page_name);
    $submit_link            = 'admin/'.str_slug($page_name).'/update';
    $eid                    = $singleRecord->id;
    $email_on_add_edit      = 'readonly';
}
else
{
    $first_name         = "";
    $last_name          = "";
    $username           = "";
    $email              = "";
    $gmale = true;
    $gfemale = false;
    $coucod             = "";
    $btn_status         = 'Add '.ucwords($page_name);
    $submit_link        = 'admin/'.str_slug($page_name).'/store';
    $eid                = "";
    $email_on_add_edit = '';
}
?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ $mode.' '.ucwords($page_name) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link, 'class' => '' , 'id' => 'new-user'  )) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}


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
                                {{ Form::text('username', $username, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'username',$email_on_add_edit => $email_on_add_edit , 'placeholder' => 'Username']) }}
                                @if ($errors->has('username'))
                                    <span class="parsley-required">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Email Address *</label>
                            <div class="col-sm-10">
                                    {{ Form::text('email', $email, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'email' , $email_on_add_edit => $email_on_add_edit , 'placeholder' => 'User Email']) }}
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


                        <div class="form-group row {{ $errors->has('country') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Country *</label>
                                <div class="col-sm-10">
                                    {{ Form::select('country', $countries, $coucod, array('id' => 'country','class' => 'form-control col-md-7 col-xs-12 select2' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                @if ($errors->has('country'))
                                    <span class="parsley-required">{{ $errors->first('country') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="dker p-a text-right">
                            <a href="{{ URL('admin/'.str_slug($page_name)) }}" class="btn btn-fw info">Cancel</a>
                            <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection