<?php 
$page_name = 'sms-management';
$page_name2 = 'Sms';
$primary_key = 'smsid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name2))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $smsManagement;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $subject            =   $singleRecord->subject;
    $body               =   $singleRecord->body;

    $btn_status         =   'Update '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else if($mode == 'Signature')
{
    $singleRecord       =   $smsManagement;
    $eid                =   $singleRecord->$primary_key;

    $subject            =  '';
    $body               =   $singleRecord->body;

    $btn_status         =   'Update '.ucfirst('signature');
    $submit_link        =   'admin/'.$page_name.'/signature';
}
else
{
    $subject            =   "";
    $body               =   "";

    $btn_status         =   'Add '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header">  
           @if($mode == 'Signature')
                <h2>{{ ucfirst('edit').' '.$mode }}<small></small></h2>
            @else
                 <h2>{{ $mode.' '.ucfirst($page_name2) }}<small></small></h2>
             @endif
            </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}
                        @if($mode != 'Signature')
                        <div class="form-group row {{ $errors->has('subject') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Subject<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('subject', $subject, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'subject' , 'placeholder' => 'Sms Subject']) }}
                                @if ($errors->has('subject'))
                                    <span class="parsley-required">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                        <div class="form-group row {{ $errors->has('body') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Sms Message<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::textarea('body', $body, ['class' => 'form-control col-md-7 col-xs-12 sms-message' ,'onKeyDown'=>'textCounter(this,"q17length",350)','onKeyUp'=>'textCounter(this,"q17length",350)','id' => 'body' , 'placeholder' => 'Sms Message']) }}
                                @if ($errors->has('body'))
                                    <span class="parsley-required">{{ $errors->first('body') }}</span>
                                @endif
                                <div>
                             @if($mode != 'Signature')
                             <div class="text-info"><input class="text-info" style="border:none;" readonly="readonly" type="text" id='q17length' name="q17length" size="3" maxlength="3" value="350" /> <span>characters left</span></div>
                             @endif
                            </div>
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
<script type="text/javascript" >
$(document).ready(function(){

    var cntfield = $("#q17length").val(350 - $(".sms-message").val().length);
 });
    function textCounter(field, cnt, maxlimit) {         
        var cntfield = document.getElementById(cnt)   
        if (field.value.length > maxlimit) {

            field.value = field.value.substring(0, maxlimit);
        }
        else
        {
            cntfield.value = maxlimit - field.value.length;
        }
    }   
    
</script>

@stop
