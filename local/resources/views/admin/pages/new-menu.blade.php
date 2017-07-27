<?php 
$page_name = 'menu';
$primary_key = 'menuid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $menu;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $menu_name          =   $singleRecord->menu_name;
    $page_ids           =   $singleRecord->page_ids;
    $page_ids           =   explode(',',$page_ids);

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'admin/'.$page_name.'/update';
    
}
else
{
    $menu_name          =   "";
    $page_ids           =   "";

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

                    <div class="form-group row {{ $errors->has('menu_name') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Menu Name <span class="parsley-required">*</span></label>
                            <div class="col-sm-10">
                                {{ Form::text('menu_name', $menu_name, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'menu_name' , 'placeholder' => 'Menu Name']) }}
                                @if ($errors->has('menu_name'))
                                    <span class="parsley-required">{{ $errors->first('menu_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('pages') ? 'has-danger' : '' }} user_area">
                            <label class="col-sm-2 form-control-label">Pages *</label>
                            <div class="col-sm-10">

                                {{ Form::select('pages[]', $pages, $page_ids, array('id' => 'pages','class' => 'form-control select2-multiple', 'multiple' => 'multiple' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                @if ($errors->has('pages'))
                                    <span class="parsley-required">{{ $errors->first('pages') }}</span>
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
