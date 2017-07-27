<?php
$page_name = 'withdraw';
$page_name2 = 'Withdrawal Request';
$allRecord = $withdraws;
$primary_key = 'withdrawcod';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name2))
@section('adminBody')

<div class="error-msg"></div>

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row">
				<div class="box-header">
					<h2>{{ ucwords($page_name2) }}</h2>
				</div>
			</div>
			<div class="col-md-6 text-right">
				@if( count($allRecord) > 0 )
				{!! Form::open(array('url' => 'admin/'.$page_name.'/export')) !!}
				<button class="md-btn md-fab m-b-sm teal" title="Export Withdrawal Record" style="float: right;margin-left: 4px;"><i class="material-icons">&#xE2C0;</i></button>
				{!! Form::close() !!}
				<button class="md-btn md-fab m-b-sm blue withdraw-approve-admin" title="Click to Approve"><i class="material-icons">&#xE163;</i></button>
				@endif
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th><label class="md-check"><input type="checkbox" id="chkMasterCheckbox"><i class="blue"></i></label></th>
						<th>Withdrawal Id</th>
						<th>Name</th>
						<th>Withdrawal Type</th>
						<th>Status</th>
						<th>Withdrawal Date</th>
						<th>Withdrawal Amount ($)</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<td>
						@if($singleRecord->status == 'pending')
						<label class="md-check ch-{{ $primary_column }}"><input type="checkbox" value="{{ $primary_column }}" class="chkSubCheckbox" name="withdraw_id[]"><i class="blue"></i></label>
						@endif
						</td>
						<td><a href="{{ URL('admin/withdraw/view/'.$primary_column.'') }}" class="text-info"> {{ $singleRecord->withdrawno }}</a></td>
						<td><a href="javascript:void({{ $singleRecord->id }});" class="withdraw-report-popup text-info" data-id="{{ $singleRecord->id }}" title="View Details">{{ $singleRecord->username }} | {{ ucfirst($singleRecord->first_name).' '.ucfirst($singleRecord->last_name) }} </a></td>
						<td>{{ $WithdrawType[$singleRecord->withdraw_type] }}</td>
						<td nowrap="nowrap" class="status-{{ $primary_column }}">@if($singleRecord->status == 'approved')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
						@elseif($singleRecord->status == 'pending')
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@else
						<span class="text-info" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@endif</td>
						<td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'pending')
						{!! Form::open(array('url' => 'admin/withdraw-pay/' , 'style' =>     'display: inline-block;' )) !!}
							{{ Form::hidden('eid', $primary_column , array('id' => 'eid')) }}
							<button class="trans-button" title="Click to Approve" onclick="return confirm('Are you sure you want to Approve this Withdraw ?');"><i class="material-icons">&#xE163;</i></button>
						{!! Form::close() !!}
						@else
							<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i></span>
						@endif
						<td>
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
					{{ $allRecord->links() }}
				</div>
			</div>
		</footer>
	</div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/withdraw.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop