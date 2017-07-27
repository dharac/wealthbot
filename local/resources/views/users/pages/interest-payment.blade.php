<?php 
$page_name = 'Interest Payment';
$page_name2 = 'interest-payment';
$allRecord = $interestPayments;
$primary_key = 'int_proid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
@if($dateRange != "")
	<div class="padding"><div class="col-md-12">{{ $page_name }} show between <b>{{ $dateRange }}</b> &nbsp;&nbsp;<a href="{{ url('user/interest-payment') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-7 text-left row">
				<div class="box-header box-header-data" data-stdt="{{ $stdt }}" data-endt="{{ $endt }}">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-5 text-right">
			{!! Form::open(array('url' => 'user/interest-payment','method' => 'get')) !!}
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
						<th>Plan Name</th>
						<th>Status</th>
						<th>Interest Earned Date</th>
						<th>Amount ($)</th>
						<th>Interest ($)</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td>{{ $singleRecord->plan_name }}</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'approved')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
						@elseif($singleRecord->status == 'pending')
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@else
						<span class="text-info" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@endif
						</td>
						<td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->pro_amount,2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="7">No Records !</td>
					</tr>
					@endif
				</tbody>
				@if(count($allRecord) > 0)
                <tfoot>
                    <tr>
                        <th nowrap="nowrap" colspan="5" class="text-right no-border"><strong>Total</strong></th>
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
