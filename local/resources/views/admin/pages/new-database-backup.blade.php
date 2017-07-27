<?php 
$page_name = 'database-backup';
$page_name2 = 'Database Backup';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name2))
@section('adminBody')

<?php 

    $message            =   "";
    $subject            =   config('services.SITE_DETAILS.SITE_NAME').' Database Backup '.dispayTimeStamp(Carbon\Carbon::now())->toDayDateTimeString();
    $email              =   config('services.SITE_DETAILS.SITE_ADMIN_EMAIL');

    $btn_status         =   'Email '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";

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

                         <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Email Address<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ $email }}
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('subject') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Subject<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('subject', $subject, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'subject' , 'placeholder' => 'Subject']) }}
                                @if ($errors->has('subject'))
                                    <span class="parsley-required">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('message') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Message</label>
                            <div class="col-sm-10">
                                {{ Form::textarea('message', $message, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'message' , 'placeholder' => 'Message']) }}
                                @if ($errors->has('message'))
                                    <span class="parsley-required">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="dker p-a text-right">
                            <a href="{{ URL('dashboard') }}" class="btn btn-fw info">Cancel</a>
                            <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                        </div> 

                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" src="{{ URL::asset('local/assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script> 
$(document).ready(function() {
    CKEDITOR.replace('message'); 
});
</script>
@stop