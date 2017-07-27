<?php 
$page_name      = 'interest-variable';
$page_name2     = 'Interest Variable';
$primary_key    = 'varid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name2))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $interestvariable;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $interest           =   number_format($singleRecord->interest,2);
    $month              =   $singleRecord->month;
    $year               =   $singleRecord->years;

    $btn_status         =   'Update '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $interest           =   '';
    $month              =   date('m');
    $year               =   date('Y');


    $btn_status         =   'Add '.ucfirst($page_name2);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}

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

                    <div class="form-group row {{ $errors->has('interest') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Interest % *</label>
                            <div class="col-sm-10">
                                {{ Form::text('interest', $interest, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'interest' , 'placeholder' => 'Interest %']) }}
                                @if ($errors->has('interest'))
                                    <span class="parsley-required">{{ $errors->first('interest') }}</span>
                                @endif
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('month') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Month *</label>
                            <div class="col-sm-10">
                            {{ Form::selectMonth('month', $month, ['class' => 'form-control col-md-7 col-xs-12']) }}
                                @if ($errors->has('month'))
                                    <span class="parsley-required">{{ $errors->first('month') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('year') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Year *</label>
                            <div class="col-sm-10">
                                {{ Form::selectYear('year',2017 , 2019, $year, ['class' => 'form-control col-md-7 col-xs-12']) }}
                                @if ($errors->has('year'))
                                    <span class="parsley-required">{{ $errors->first('year') }}</span>
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
