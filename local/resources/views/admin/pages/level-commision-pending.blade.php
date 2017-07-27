<?php 
$page_name = 'Pending Commissions';
$allRecord = $result['list'];
$cnt = count($allRecord);
if($cnt >0)
{
	$date = array();
	for($i=0;$i<$cnt;$i++)
	{
		$date[$i] 	= strtotime($allRecord[$i]['endDate']);
		$level[$i] 	= $allRecord[$i]['level'];
	}
	array_multisort($date, SORT_ASC, $level, SORT_ASC, $allRecord);
	$allRecord  = $allRecord;
}

$pendingCommission = $result['pendingCommission'];
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')

<div class="commission-desc"></div>
<div class="clearfix"></div>

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-7 text-left row">
				<div class="box-header box-header-data">
					<h2>{{ $page_name }}<small>Level Commissions</small></h2>
				</div>
			</div>
			<div class="col-md-5 text-right">
			{!! Form::open(array('url' => 'admin/level-commision/pending/export','method' => 'post')) !!}
			<button class="md-btn md-fab m-b-sm teal" title="Export Pending Commissions"><i class="material-icons">&#xE2C0;</i></button>
			{!! Form::close() !!}
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t" ui-jp="dataTable" ui-options="{ bSort: false, pageLength: {{ config('services.DATATABLE.PERPAGE') }} }">
				<thead>
					<tr>
						<th>#</th>
						<th>Referrer</th>
						<th>Referee</th>
						<th title="Referral Level">Referral Level</th>
						<th>Status</th>
						<th>Commission Due Date</th>
						<th>Amount ($)</th>
						<th title="Commissions Paid %">Commission %</th>
						<th title="Commissions">Commission ($)</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = 1; ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<tr>
						<th scope="row">{{ $a }}</th>
						<td>{{ ucfirst($singleRecord['upline_name']) }} | {{ $singleRecord['upline_username'] }}</td>
						<td>{{ ucfirst($singleRecord['name']) }} | {{ $singleRecord['username'] }}</td>
						<td nowrap="nowrap">{{ App\myCustome\myCustome::addOrdinalNumberSuffix($singleRecord['level']) }} Level</td>
						<td nowrap="nowrap">
						<span class="text-danger" title="Pending"><i class="material-icons">&#xE88F;</i> Pending</span>
						</td>
						<td title="{{ dispayTimeStamp($singleRecord['endDate'])->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord['endDate'])->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord['com_amount'],2) }}</td>
						<td nowrap="nowrap">{{ number_format($singleRecord['commission_rate'],2) }} %</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord['commission'],2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="10">No Records !</td>
					</tr>
					@endif
				</tbody>
				
				<tfoot>
					<tr>
						<th colspan="8" class="text-right no-border"><strong>Total</strong></th>
						<th nowrap="nowrap"><strong>$ {{ number_format($pendingCommission,2) }}</strong></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/level-commision.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop