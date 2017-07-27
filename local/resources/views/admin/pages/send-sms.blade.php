<?php 
$page_name = 'Sent SMS Details';
$allRecord = $smss;
$primary_key = 'smsid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')

@if($q != '')
	<div class="padding"><div class="col-md-12"><b>{{ $allRecord->total() }}</b> Results found for: <b>@if(isset($q)) {{ $q }} @endif</b> &nbsp;&nbsp;<a href="{{ url('admin/sms') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row">
				<div class="box-header">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-6 text-right">
				

			{!! Form::open(array('url' => 'admin/sms','method' => 'get', 'class' => 'navbar-form form-inline navbar-item' )) !!}
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
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Message</th>
						<th>Message Id</th>
						<th>Created Date</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td nowrap="nowrap">{{ ucfirst($singleRecord->first_name) }} {{ ucfirst($singleRecord->last_name) }} <br> <a href="{{ url('admin/user/view/'.$singleRecord->id.'') }}" class="text-info"> {{ $singleRecord->username }} </a>
						</td>
						<td>{{ $singleRecord->sms }}</td>
						<td nowrap="nowrap">{{ $singleRecord->message_id }}</td>
						<td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
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