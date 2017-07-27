<?php 
$page_name = 'Available Commissions';
$page_name2 = 'level-commision';
$allRecord = $levelCommisions;
$primary_key = 'comid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')

<div class="commission-desc"></div>
<div class="clearfix"></div>

<div class="padding">
	<div class="col-md-12 col-xs-12">
	@if($dateRange != "")
		{{ $page_name }} show between <b>{{ $dateRange }}</b> &nbsp;&nbsp;<a href="{{ url('admin/level-commision') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a>
	@endif
	</div>
</div>
<div class="clearfix"></div>

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 col-xs-12 text-left row">
				<div class="box-header box-header-data" data-stdt="{{ $stdt }}" data-endt="{{ $endt }}">
					<h2>{{ $page_name }} ( <b>Total $ {{ number_format($totalAmt,2) }}</b> )  <small>Level Commissions</small></h2>
				</div>
			</div>
			<div class="col-md-6 col-xs-12 text-right">
			{!! Form::open(array('url' => 'admin/level-commision/export','method' => 'post')) !!}
			<button class="md-btn md-fab m-b-sm teal user-export-popup" style="float: right;margin-left: 10px;margin-top: -6px;"><i class="material-icons">&#xE2C0;</i></button>
			{!! Form::close() !!}
			{!! Form::open(array('url' => 'admin/level-commision','method' => 'get')) !!}
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
						<th>Referrer </th>
						<th>Referee </th>
						<th title="Referral Level">Referral Level</th>
						<th>Status</th>
						<th>Commission Earned Date</th>
						<th>Amount ($)</th>
						<th title="Commissions Paid %">Commission %</th>
						<th title="Commissions">Commission ($)</th>
						<!-- <th>Action</th> -->
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td><a class="text-info" href="{{ URL('admin/user/view/'.$singleRecord->id.' ') }}">{{ ucfirst($singleRecord->first_name).' '.ucfirst($singleRecord->last_name) }} | {{ $singleRecord->username }}</a></td>
						<td>{{ ucfirst($singleRecord->down_first_name).' '.ucfirst($singleRecord->down_last_name) }} | {{ $singleRecord->down_username }}</td>
						<td nowrap="nowrap">{{ App\myCustome\myCustome::addOrdinalNumberSuffix($singleRecord->referral_level) }} Level</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'approved')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> Available </span>
						@elseif($singleRecord->status == 'pending')
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@else
						<span class="text-info" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@endif
						</td>
						<td title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->interest,2) }}</td>
						<td nowrap="nowrap">{{ number_format($singleRecord->com_rate,2) }} %</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->commission,2) }}</td>
						<!-- <td>
							@if($singleRecord->status == 'pending')
							{!! Form::open(array('url' => 'admin/level-commision/approve' )) !!}
                    		{{ Form::hidden('eid', $primary_column , array('id' => 'eid')) }}
                    			<button class="trans-button" title="Click to Approve" onclick="return confirm('Are you sure you want to Approve this Commision ?');"><i class="material-icons">&#xE163;</i></button>
                    		{!! Form::close() !!}
                    		@else
                    			<span class="text-success" title="{{ ucfirst($singleRecord->status) }} on {{ $singleRecord->updated_at->toDayDateTimeString() }}"><i class="material-icons">&#xE877;</i></span>
                    		@endif
						</td> -->
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="10">No Records !</td>
					</tr>
					@endif
				</tbody>
				@if(count($allRecord) > 0)
				<tfoot>
					<tr>
						<th colspan="8" class="text-right no-border"><strong>Total</strong></th>
						<th nowrap="nowrap"><strong>$ {{ number_format($totalAmt,2) }}</strong></th>
					</tr>
				</tfoot>
				@endif
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
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/level-commision.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript"> $( document ).ready(function() { getBalance(); }); </script>
@stop