<?php $page_name = 'Change Password' ?>
@extends('admin.parts.layout')
@section('adminTitle', $page_name)
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"><h2>{{ $page_name }}</h2></div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => 'admin/user/passwordupdt')) !!}
                        <div class="form-group row {{ $errors->has('opassword') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Old Password *</label>
                            <div class="col-sm-10">
                                {{ Form::password('opassword', ['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'opassword' ,'placeholder' => 'Old Password']) }}
                                @if ($errors->has('opassword'))
                                    <span class="parsley-required">{{ $errors->first('opassword') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">New Password *</label>
                            <div class="col-sm-10">
                                {{ Form::password('password', ['class' => 'form-control has-value col-md-7 col-xs-12' ,'id' => 'password' ,'placeholder' => 'New Password']) }}
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
                        <div class="dker p-a text-right">
                            <a href="{{ URL('/dashboard') }}" class="btn btn-fw info">Cancel</a>
                            <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $page_name }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
