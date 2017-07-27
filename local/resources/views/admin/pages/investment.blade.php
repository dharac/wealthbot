<?php 
$page_name = 'loan';
$page_name2 = 'Private Loans';
$allRecord = $investments;
$primary_key = 'depositid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name2))
@section('adminBody')

@if($dateRange != "")
	<div class="padding"><div class="col-md-12">{{ ucfirst($page_name) }} show between <b>{{ $dateRange }}</b> &nbsp;&nbsp;<a href="{{ url('admin/loan') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row col-xs-12">
				<div class="box-header box-header-data" data-stdt="{{ $stdt }}" data-endt="{{ $endt }}">
					<h2>{{ ucwords($page_name2) }} (<b> Total $ {{ number_format($totalAmt,2) }} </b>)</h2>
				</div>
			</div>
			<div class="col-md-6 text-right col-xs-12">
			<div class="col-md-12">
			{!! Form::open(array('url' => 'admin/loan','method' => 'get')) !!}
			<input type="hidden" name="startdt" id="startdt" >
			<input type="hidden" name="enddt" id="enddt">
			<button class="md-btn md-fab m-b-sm blue pull-right" style="margin-top:-5px;"><i class="material-icons md-24">&#xE8B6;</i></button>
			<lable id="reportrange" class="pull-right datepicker_cls"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>
			</lable>
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
						<th>Deposit Id</th>
						<th>Name / Plan Name</th>
						<th>Loan Date</th>
						<th>Maturity Date</th>
						<th>Status</th>
						<th>Loan Amount ($)</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}">
						<th scope="row">{{ $a }}</th>
						<td><a href="{{ URL('admin/'.$page_name.'/view/'.$primary_column.' ') }}" class="text-info" data-placement="left" title="View Record">{{ $singleRecord->depositno }}</a></td>
						<td><a class="text-info" href="{{ URL('admin/user/view/'.$singleRecord->user->id.' ') }}">{{ ucfirst($singleRecord->user->first_name).' '.ucfirst($singleRecord->user->last_name) }} | {{ $singleRecord->user->username }}</a>
						<br>
						<span class="text-primary">{{ $singleRecord->plan->plan_name }}</span>
						</td>
						<td nowrap="nowrap">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}
						<div class="sl-date text-muted">Payment Through : {{ str_replace('_', ' ', ucwords(strtolower($singleRecord->payment_through))) }}</div>
						</td>
						<td nowrap="nowrap">
							@if($singleRecord->plan->plan_status == 1)
							@if($singleRecord->maturity_date != "")
                                {{ Carbon\Carbon::createFromFormat('Y-m-d', $singleRecord->maturity_date)->format('D, M d, Y') }}
                            @endif
							@else
							<center>-</center>
							@endif
						</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'approved')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucwords($singleRecord->status) }}</span>
						@elseif($singleRecord->status == 'pending')
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucwords($singleRecord->status) }}</span>
						@else
						<span class="text-info m-r-sm" title="{{ ucwords(str_replace('_', ' ', $singleRecord->status)) }}"><i class="material-icons">&#xE90A;</i> {{ ucwords(str_replace('_', ' ', $singleRecord->status)) }}</span>
						@endif
						@if($singleRecord->description != "")
                            <a href="javascript:void(0);" data-toggle="tooltip" title="{{ ucwords($singleRecord->description) }}"><i class="material-icons text-info">&#xE88F;</i></a>
                        @endif
						</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
						<td>
						@if($singleRecord->status == 'pending')
						{!! Form::open(array('url' => 'admin/loan/approve' , 'style' =>     'display: inline-block;' )) !!}
							{{ Form::hidden('eid', $primary_column , array('id' => 'eid')) }}
							<button class="trans-button" title="Click to Approve" onclick="return confirm('Are you sure you want to Approve this Loan ?');"><i class="material-icons">&#xE163;</i></button>
						{!! Form::close() !!}
						@else
						<span class="text-success" title="{{ ucwords(str_replace('_', ' ', $singleRecord->status)) }}"><i class="material-icons">&#xE877;</i></span>
						@endif
						</td>
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
						<th colspan="6" class="text-right no-border"><strong>Total</strong></th>
						<th nowrap="nowrap"><strong>$ {{ number_format($totalAmt,2) }}</strong></th>
						<th></th>
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
						{!! $allRecord->appends(['startdt' => $stdt,'enddt' => $endt ])->render() !!}
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
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/loan.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop