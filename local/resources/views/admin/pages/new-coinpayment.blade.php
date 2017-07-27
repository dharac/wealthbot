<?php 
$page_name = 'coinpayment';
$primary_key = 'coinid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $coinpayment;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $merchant_id        =   $singleRecord->merchant_id;
    $public_id          =   $singleRecord->public_id;
    $private_id         =   $singleRecord->private_id;
    $ipn_secret         =   $singleRecord->ipn_secret;
    $ipn_email          =   $singleRecord->ipn_email;

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $merchant_id        =   "";
    $public_id          =   "";
    $private_id         =   "";
    $ipn_secret         =   "";
    $ipn_email          =   "";

    $btn_status         =   'Add '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ $mode.' '.ucfirst($page_name) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('merchant_id') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Merchant Id <span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('merchant_id', $merchant_id, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'merchant_id' , 'placeholder' => 'Merchant Id']) }}
                                @if ($errors->has('merchant_id'))
                                    <span class="parsley-required">{{ $errors->first('merchant_id') }}</span>
                                @endif
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('public_id') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Public Key<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('public_id', $public_id, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'public_id' , 'placeholder' => 'Public Key']) }}
                                @if ($errors->has('public_id'))
                                    <span class="parsley-required">{{ $errors->first('public_id') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('private_id') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Private Key <span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('private_id', $private_id, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'private_id' , 'placeholder' => 'Private Key']) }}
                                @if ($errors->has('private_id'))
                                    <span class="parsley-required">{{ $errors->first('private_id') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('ipn_secret') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Ipn Secret<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('ipn_secret', $ipn_secret, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'ipn_secret' , 'placeholder' => 'Ipn Secret']) }}
                                @if ($errors->has('ipn_secret'))
                                    <span class="parsley-required">{{ $errors->first('ipn_secret') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('ipn_email') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Ipn Email<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('ipn_email', $ipn_email, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'ipn_email' , 'placeholder' => 'Ipn Email']) }}
                                @if ($errors->has('ipn_email'))
                                    <span class="parsley-required">{{ $errors->first('ipn_email') }}</span>
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
