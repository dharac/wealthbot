<?php
$page_name = 'news';
$primary_key = 'newsid';
?>
@extends('admin.parts.layout')
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

    <?php
    if($mode == 'Edit')
    {
        $singleRecord       =   $news;
        $eid                =   $singleRecord->$primary_key;

        //CHANGE
        $news_header        =   $singleRecord->news_header;
        $news_description   =   $singleRecord->news_description;
        $status             =   $singleRecord->status;

        $btn_status         =   'Update '.ucfirst($page_name);
        $submit_link        =   'admin/'.$page_name.'/update';

    }
    else
    {
        $news_header        =   "";
        $news_description   =   "";
        $status             =   "";


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

                        <div class="form-group row {{ $errors->has('news_header') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">News Header *</label>
                            <div class="col-sm-10">
                                {{ Form::text('news_header', $news_header, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'news_header' , 'placeholder' => 'News Header']) }}
                                @if ($errors->has('news_header'))
                                    <span class="parsley-required">{{ $errors->first('news_header') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('news_description') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">News Description *</label>
                            <div class="col-sm-10">
                                {{ Form::textarea('news_description', $news_description, ['cols' => 80,'rows' => 10,'id' => 'news_description' , 'placeholder' => 'Page Title']) }}
                                @if ($errors->has('news_description'))
                                    <span class="parsley-required">{{ $errors->first('news_description') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Status</label>
                            <div class="col-sm-10">
                                {{ Form::select('status', array('active' => 'Active', 'inactive' => 'Inactive' ), $status , ['class' => 'form-control' ]) }}
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
    CKEDITOR.replace('news_description'); 
});
</script>
@stop
