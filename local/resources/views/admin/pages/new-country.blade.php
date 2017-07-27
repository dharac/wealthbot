<?php 
$page_name = 'country';
$primary_key = 'coucod';
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
    $counm              =   "";
    $cou_prefix         =   "";
    $cou_code           =   "";


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

                    <div class="form-group row {{ $errors->has('counm') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Country Name *</label>
                            <div class="col-sm-10">
                                {{ Form::text('counm', $counm, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'counm' , 'placeholder' => 'Country Name (India)']) }}
                                @if ($errors->has('counm'))
                                    <span class="parsley-required">{{ $errors->first('counm') }}</span>
                                @endif
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('cou_code') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Country Code</label>
                            <div class="col-sm-10">
                                {{ Form::text('cou_code', $cou_code, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'cou_code' , 'placeholder' => 'Country Code (+91)']) }}
                                @if ($errors->has('cou_code'))
                                    <span class="parsley-required">{{ $errors->first('cou_code') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('cou_prefix') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Country ISO</label>
                            <div class="col-sm-10">
                                {{ Form::text('cou_prefix', $cou_prefix, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'cou_prefix' , 'placeholder' => 'Country Perfix (IND) ']) }}
                                @if ($errors->has('cou_prefix'))
                                    <span class="parsley-required">{{ $errors->first('cou_prefix') }}</span>
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
