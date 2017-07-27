<?php 
$page_name 		= 'country';
$allRecord 		= $countries;
$primary_key 	= 'coucod';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
@if($q != '')
    <div class="padding"><div class="col-md-12"><b>{{ $allRecord->total() }}</b> Results found for: <b>@if(isset($q)) {{ $q }} @endif</b> &nbsp;&nbsp;<a href="{{ url('admin/country') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row col-xs-12">
				<div class="box-header">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-6 text-right col-xs-12">
			<div class="col-md-10 col-xs-12">
				{!! Form::open(array('url' => 'admin/country','method' => 'get', 'class' => 'navbar-form form-inline navbar-item' )) !!}
				<div class="form-group l-h m-a-0">
					<div class="input-group">
						<input type="text" name="q" value="{{ $q }}" class="form-control b-a" placeholder="Search"> 
						<span class="input-group-btn">
						<button type="submit" class="btn  btn-default b-a no-shadow"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</div>
			{!! Form::close() !!}
			</div>
			<div class="col-md-2 col-xs-12 text-right">
				<a class="md-btn md-fab m-b-sm blue" href="{{ URL('admin/'.$page_name.'/new') }}" title="Add {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE145;</i></a>
			</div>
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Country</th>
						<th>Country Code</th>
						<th>Country ISO</th>
						<th>Modified Date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record" >{{ ucfirst($singleRecord->counm) }}</a></td>
						<td>{{ $singleRecord->cou_code }}</td>
						<td>{{ $singleRecord->cou_prefix }}</td>
						<td title="{{ dispayTimeStamp($singleRecord->updated_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->updated_at)->toDayDateTimeString() }}</td>
						<td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" class="{{ $primary_table_col_del }}" data-id="{{ $primary_column }}" data-pid="{{ $page_name }}" title="Delete Record"><i class="material-icons">&#xE872;</i></a></td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="7">No Records !</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		<footer class="dker p-a">
			<div class="row">
				<div class="col-sm-4 text-left">
					<small class="text-muted inline m-t-sm m-b-sm">Showing {{ $allRecord->firstItem() }} to {{ $allRecord->lastItem() }} of {{ $allRecord->total() }} entries</small>
				</div>
				<div class="col-sm-8 text-right text-center-xs">
					@if($q == '')
						{{ $allRecord->links() }}
					@else
						{!! $allRecord->appends(['q' => $q ])->render() !!}
					@endif
				</div>
			</div>
		</footer>
	</div>
</div>
@endsection