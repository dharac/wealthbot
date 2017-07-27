<?php 
$page_name = 'question';
$primary_key = 'secid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' Security '.ucfirst($page_name))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $questions;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $question              =   $singleRecord->question;

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $question              =   "";


    $btn_status         =   'Add '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ $mode.' Security '.ucfirst($page_name) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('question') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Security Question *</label>
                            <div class="col-sm-10">
                                {{ Form::text('question', $question, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'question' , 'placeholder' => 'Security Question']) }}
                                @if ($errors->has('question'))
                                    <span class="parsley-required">{{ $errors->first('question') }}</span>
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
