<?php 
$page_name = 'mass-email';
$page_name1 = 'Email';
?>
@extends('admin.parts.layout')  
@section('adminTitle', ucfirst($page_name1))
@section('adminBody')

<?php 

    $subject        = "Hey [FIRSTNAME] [LASTNAME], check this out...";
	$body        	= "Hello [USERNAME],<br><br>Welcome [FIRSTNAME] [LASTNAME] in Wealthbot.";
    
    $btn_status     = 'Send Email';
    $submit_link    = 'admin/'.$page_name.'/send';
    $eid            = "";
?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ ucfirst($page_name1) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('type') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Send Type</label>
                            <div class="col-sm-10"> 
                                <label class="md-check">
                                    {{ Form::radio('type', 'user', '' , ['class' => 'flat' ,'id' => 'type_u','checked' => 'checked' ]) }}
                                    <i class="green"></i>
                                    Send to selected Users &nbsp;&nbsp;
                                </label>

                                <label class="md-check">
                                        {{ Form::radio('type', 'all', '' , ['class' => 'flat' ,'id' => 'type_a' ]) }}
                                        <i class="green"></i>
                                        Send to all Users
                                </label>

                                @if ($errors->has('type'))
                                    <span class="parsley-required">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('users') ? 'has-danger' : '' }} user_area">
                            <label class="col-sm-2 form-control-label">Users *</label>
                            <div class="col-sm-10">
                                <select id="users" name="users[]" class="form-control select2-multiple userAutoFillup" multiple="multiple" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                                </select>
                                @if ($errors->has('users'))
                                    <span class="parsley-required">{{ $errors->first('users') }}</span>
                                @endif
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
                            <label class="col-sm-2 form-control-label">Body *</label>
                            <div class="col-sm-10">
                               {{ Form::textarea('body', $body, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'body' , 'placeholder' => 'Body']) }}
                                @if ($errors->has('body'))
                                    <span class="parsley-required">{{ $errors->first('body') }}</span>
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
<script type="text/javascript"> $( document ).ready(function() { userAutoFillup(); }); </script>
<script type="text/javascript" src="{{ URL::asset('local/assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script> 
$(document).ready(function() {
    CKEDITOR.replace('body'); 
});
</script>
@stop