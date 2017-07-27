<?php 
$page_name = 'user';
$page_name2 = 'Send Email';
$primary_key = 'id';
?>
@extends('admin.parts.layout')  
@section('adminTitle', ucfirst($page_name2))
@section('adminBody')

<?php 
    
    $singleRecord       =   $user;
    $eid                =   $singleRecord->$primary_key;
    $subject            =   "";
    $body               =   "";


    $btn_status         =   'Send Email';
    $submit_link        =   'admin/'.$page_name.'/send-email';

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ $page_name2 }}</h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('name') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Name </label>
                            <div class="col-sm-10">
                                <label class="form-control-label">{{ ucfirst($singleRecord->first_name) }} {{ ucfirst($singleRecord->last_name) }} | {{ $singleRecord->email }} </label>
                            </div>
                        </div> 

                    <div class="form-group row {{ $errors->has('subject') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Subject *</label>
                            <div class="col-sm-10">
                                {{ Form::text('subject', $subject, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'subject' , 'placeholder' => 'Subject']) }}
                                @if ($errors->has('subject'))
                                    <span class="parsley-required">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('body') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Message *</label>
                            <div class="col-sm-10">
                                {{ Form::textarea('body', $body, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'body' , 'placeholder' => 'Body ']) }}
                                @if ($errors->has('body'))
                                    <span class="parsley-required">{{ $errors->first('body') }}</span>
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
@section('pageScript')
<script type="text/javascript" src="{{ URL::asset('local/assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script> 
$(document).ready(function() {
    CKEDITOR.replace('body'); 
});
</script>
@stop
