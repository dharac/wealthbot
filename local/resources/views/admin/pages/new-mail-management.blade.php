<?php 
$page_name = 'mail-management';
$page_name2 = 'Mail';
$primary_key = 'mailid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name2))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $mailManagement;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $subject            =   $singleRecord->subject;
    $body               =   $singleRecord->body;

    $btn_status         =   'Update '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else if($mode == 'Signature')
{
    $singleRecord       =   $mailManagement;
    $eid                =   $singleRecord->$primary_key;
  
   //CHANGE signature
     $subject            =  "fsd";
    $body               =   $singleRecord->body;

   $btn_status         =   'Update '.ucfirst('signature');
    $submit_link        =   'admin/'.$page_name.'/update_signature';
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
                <h2>{{ $mode.' '.ucfirst('update') }}<small></small></h2>
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
                                {{ Form::text('subject', $subject, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'subject' , 'placeholder' => 'Mail Subject']) }}
                                @if ($errors->has('subject'))
                                    <span class="parsley-required">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div>
                        @endif


                        <div class="form-group row {{ $errors->has('body') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Mail Message<span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::textarea('body', $body, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'body' , 'placeholder' => 'Mail Message']) }}
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
    CKEDITOR.replace('body',
    {
        filebrowserBrowseUrl : ('{{ asset("local/assets/vendor/kcfinder/browse.php") }}'),
        filebrowserImageBrowseUrl : ('{{ asset("local/assets/vendor/kcfinder/browse.php?type=Images") }}'),
        filebrowserUploadUrl : ('{{ asset("local/assets/vendor/kcfinder/upload.php") }}'),
        filebrowserImageUploadUrl : ('{{ asset("local/assets/vendor/kcfinder/upload.php?type=Images") }}'),
        filebrowserImageWidth : '50',
        filebrowserImageHeight : '50'
    }); 
});
</script>
@stop