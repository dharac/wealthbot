<?php 
$page_name = 'google-capcha';
$page_name2 = 'Google Capcha';
$primary_key = 'capcod';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name2))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $googlecapcha;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $cap_key            =   $singleRecord->cap_key;
    $cap_secret         =   $singleRecord->cap_secret;
    $email              =   $singleRecord->email;

    $btn_status         =   'Update '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $cap_key            =   "";
    $cap_secret         =   "";
    $email              =   "";

    $btn_status         =   'Add '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ $mode.' '.ucfirst($page_name2) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                         <div class="form-group row {{ $errors->has('cap_key') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Google Capcha Key<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('cap_key', $cap_key, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'cap_key' , 'placeholder' => 'Google Capcha Key']) }}
                                @if ($errors->has('cap_key'))
                                    <span class="parsley-required">{{ $errors->first('cap_key') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('cap_secret') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Google Capcha Secret<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('cap_secret', $cap_secret, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'cap_secret' , 'placeholder' => 'Google Capcha Secret']) }}
                                @if ($errors->has('cap_secret'))
                                    <span class="parsley-required">{{ $errors->first('cap_secret') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Google Capcha Email<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('email', $email, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'email' , 'placeholder' => 'Google Capcha Email']) }}
                                @if ($errors->has('email'))
                                    <span class="parsley-required">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="dker p-a text-right">
                            <a href="{{ URL('admin/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                            <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                        </div> 

                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
