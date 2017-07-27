<?php 
$page_name = 'page';
$primary_key = 'pageid';
?>
@extends('admin.parts.layout')
@section('adminTitle', 'Add Page')
@section('adminBody')
<?php 
$page_type = "";
if($mode == 'Edit')
{
    $singleRecord       =   $page;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $title              =   $singleRecord->title;
    $pageEditor         =   $singleRecord->content;
    $pageEditorURL      =   "";
    $page_type          =   $singleRecord->content_type;

    $html               = true;
    $url                = false;

    if($page_type == 'url')
    {
        $html           = false;
        $url            = true;
        $pageEditorURL  =   $singleRecord->content;
    }

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $title              =   "";
    $pageEditor         =   "";
    $pageEditorURL      =   "";
    
    $html               = true;
    $url                = false;


    $btn_status         =   'Add '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/store';
    $eid                =   "";
}


$addclsurl = "hide";
$addclshtml = "";   
if(old('type') == 'url' || $page_type == 'url')
{
    $addclsurl = "";
    $addclshtml = "hide";   
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

                    <div class="form-group row {{ $errors->has('title') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Title *</label>
                            <div class="col-sm-10">
                                {{ Form::text('title', $title, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'title' , 'placeholder' => 'Page Title']) }}
                                @if ($errors->has('title'))
                                    <span class="parsley-required">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('type') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Page Type</label>
                            <div class="col-sm-10"> 
                                <label class="md-check">
                                    {{ Form::radio('type', 'html', $html , ['class' => 'flat' ,'id' => 'type_html' ]) }}
                                    <i class="green"></i>
                                    HTML &nbsp;&nbsp;
                                </label>

                                <label class="md-check">
                                        {{ Form::radio('type', 'url', $url , ['class' => 'flat' ,'id' => 'type_url' ]) }}
                                        <i class="green"></i>
                                        URL
                                </label>

                                @if($errors->has('type'))
                                    <span class="parsley-required">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group row {{ $errors->has('pageEditor') ? 'has-danger' : '' }} html_area {{ $addclshtml }}">
                            <label class="col-sm-2 form-control-label">Page Content</label>
                            <div class="col-sm-10">
                                {{ Form::textarea('pageEditor', $pageEditor, ['cols' => 80,'rows' => 10,'id' => 'pageEditor' , 'placeholder' => 'Page Title']) }}
                                @if ($errors->has('pageEditor'))
                                    <span class="parsley-required">{{ $errors->first('pageEditor') }}</span>
                                @endif
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('page_url') ? 'has-danger' : '' }} url_area {{ $addclsurl }}">
                            <label class="col-sm-2 form-control-label">URL </label>
                            <div class="col-sm-10">
                                {{ Form::text('page_url', $pageEditorURL, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'page_url' , 'placeholder' => 'Page URL']) }}
                                @if ($errors->has('page_url'))
                                    <span class="parsley-required">{{ $errors->first('page_url') }}</span>
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
    CKEDITOR.replace('pageEditor'); 
});
</script>
@stop