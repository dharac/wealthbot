<?php 
$page_name = 'getresponse';
$primary_key = 'getresid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $getresponse;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $username               =   $singleRecord->username;
    $campaignId             =   $singleRecord->campaignId;
    $fromFieldId            =   $singleRecord->fromFieldId;
    $getresponse_api_key    =   $singleRecord->getresponse_api_key;
    $bcc                    =   $singleRecord->bcc;

    $campains               = App\GetResponse::getCampaigns();
    dd($campains);

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $username               =   "";
    $campaignId             =   "";
    $fromFieldId            =   "";
    $getresponse_api_key    =   "";
    $bcc                    =   "";

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

                    <div class="form-group row {{ $errors->has('username') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Username / Email  <span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('username', $username, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'username' , 'placeholder' => 'Username / Email']) }}
                                @if ($errors->has('username'))
                                    <span class="parsley-required">{{ $errors->first('username') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('getresponse_api_key') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Getresponse Api Key<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('getresponse_api_key', $getresponse_api_key, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'getresponse_api_key' , 'placeholder' => 'Getresponse Api Key']) }}
                                @if ($errors->has('getresponse_api_key'))
                                    <span class="parsley-required">{{ $errors->first('getresponse_api_key') }}</span>
                                @endif
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('campaignId') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Campaign Id<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('campaignId', $campaignId, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'campaignId' , 'placeholder' => 'Campaign Id']) }}
                                @if ($errors->has('campaignId'))
                                    <span class="parsley-required">{{ $errors->first('campaignId') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('fromFieldId') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">From Field Id<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('fromFieldId', $fromFieldId, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'fromFieldId' , 'placeholder' => 'From Field Id']) }}
                                @if ($errors->has('fromFieldId'))
                                    <span class="parsley-required">{{ $errors->first('fromFieldId') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('bcc') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">BCC Emails</label>
                            <div class="col-sm-10">
                                {{ Form::text('bcc', $bcc, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'bcc' , 'placeholder' => 'BCC Emails']) }}
                                @if ($errors->has('bcc'))
                                    <span class="parsley-required">{{ $errors->first('bcc') }}</span>
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

@if($mode == 'Edit')
<?php dd($campains); ?>
@endif

@endsection
