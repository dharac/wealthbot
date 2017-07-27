<?php 
$page_name = 'payout';
$primary_key = 'coucod';
$mode = 'Add';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $country;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $counm              =   $singleRecord->counm;
    $cou_prefix         =   $singleRecord->cou_prefix;
    $cou_code           =   $singleRecord->cou_code;

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $refName            = "";
    $referral           = "";
    $cou_code           = "";
    $amount  = "";
    $email_on_add_edit  = "";


    $btn_status         =   'Send Payment';
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ ucfirst($page_name) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('referral') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">User</label>
                            <div class="col-sm-10">
                                {{ Form::text('referral', $refName, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'autocomplete_referral' , 'placeholder' => 'User' , $email_on_add_edit => $email_on_add_edit]) }}
                                @if($mode == 'Add')
                                    {{ Form::hidden('referral_id', $referral, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'autocomplete_referral_id' , 'placeholder' => 'Referral Id']) }}
                                @endif
                                @if ($errors->has('referral'))
                                    <div id="selction-ajax"></div>
                                    <span class="parsley-required">{{ $errors->first('referral') }}</span>
                                @endif
                            </div>
                        </div>  

                         <div class="form-group row {{ $errors->has('bitcoin') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Bitcoin Wallet Address</label>
                            <div class="col-sm-10">
                                {{ Form::text('bitcoin', $cou_code, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'bitcoin' , 'placeholder' => 'Bitcoin Wallet Address']) }}
                                @if ($errors->has('bitcoin'))
                                    <span class="parsley-required">{{ $errors->first('bitcoin') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('amount') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Amount</label>
                            <div class="col-sm-10">
                                {{ Form::text('amount', $amount, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'cou_prefix' , 'placeholder' => 'Amount']) }}
                                @if ($errors->has('amount'))
                                    <span class="parsley-required">{{ $errors->first('amount') }}</span>
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
<link rel="stylesheet" href="{!! asset('vendor/EasyAutocomplete/css/easy-autocomplete.min.css') !!}"> 
<link rel="stylesheet" href="{!! asset('vendor/EasyAutocomplete/css/easy-autocomplete.themes.min.css') !!}"> 
@endsection
@section('pageScript')
<script src="{!! asset('vendor/EasyAutocomplete/js/jquery.easy-autocomplete.min.js') !!}"></script>
<script src="{!! asset('js/admin/user.js') !!}"></script>
@stop
