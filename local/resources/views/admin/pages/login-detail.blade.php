<?php 
$page_name = 'login-detail';
$page_name2 = 'Login Details';
$allRecord = $loginDetails;
$primary_key = 'capcod';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name2))
@section('adminBody')
@if($dateRange != "")
	<div class="padding"><div class="col-md-12">{{ $page_name2 }} show between <b>{{ $dateRange }}</b> &nbsp;&nbsp;<a href="{{ url('admin/login-detail') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-7 text-left row">
				<div class="box-header box-header-data" data-stdt="{{ $stdt }}" data-endt="{{ $endt }}">
					<h2>{{ ucwords($page_name2) }}</h2>
				</div>
			</div>
			<div class="col-md-5 text-right">
			{!! Form::open(array('url' => 'admin/login-detail','method' => 'get')) !!}
			<input type="hidden" name="startdt" id="startdt">
			<input type="hidden" name="enddt" id="enddt">
			<button class="md-btn md-fab m-b-sm blue pull-right" style="margin-top:-5px;"><i class="material-icons md-24">&#xE8B6;</i></button>
			<lable id="reportrange" class="pull-right datepicker_cls"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>
			</lable>
			{!! Form::close() !!}
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Device / OS / IP</th>
						<th>Browser</th>
						<th>Login Time</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td>{{ $singleRecord->first_name }} {{ $singleRecord->last_name }} | {{ $singleRecord->username }}</td>
						<td nowrap="nowrap">
						@if($singleRecord->device == 'tablet')
                            <i class="material-icons">&#xE331;</i>&nbsp;&nbsp;{{ ucfirst($singleRecord->device) }}
                            @elseif($singleRecord->device == 'mobile')
                            <i class="material-icons">&#xE325;</i>&nbsp;&nbsp;{{ ucfirst($singleRecord->device) }}
                            @else
                            <i class="material-icons">&#xE31E;</i>&nbsp;&nbsp;{{ ucfirst($singleRecord->device) }}
                            @endif | {{ $singleRecord->os }} |
                            {{ $singleRecord->ip }} 
						</td>
						<td nowrap="nowrap">{{ $singleRecord->browser }}</td>
						<td title="{{ dispayTimeStamp($singleRecord->updated_at)->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->updated_at)->toDayDateTimeString() }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="8">No Records !</td>
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
					@if($mode == 'search')
						{!! $allRecord->appends(['startdt' => $stdt,'enddt' => $endt])->render() !!}
					@else
						{{ $allRecord->links() }}
					@endif
				</div>
			</div>
		</footer>
	</div>
</div>
@endsection