<?php 
$page_name 		= 'Payout Report';
$allRecord 		= $payouts['list'];

$cnt = count($allRecord);
if($cnt >0)
{
	$date = array();
	for($i=0;$i<$cnt;$i++)
	{
		$date[$i] 	= strtotime($allRecord[$i]['endDate']);
	}
	array_multisort($date, SORT_ASC, $allRecord);
	$allRecord  = $allRecord;

	$nature_of_plans = App\mycustome\mycustome::natureOfPlan();
}

$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')

@if($dateRange != "")
	<div class="padding"><div class="col-md-12">{{ $page_name }} show between <b>{{ $dateRange }}</b> &nbsp;&nbsp;<a href="{{ url('admin/payout-report') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row col-xs-6">
				<div class="box-header box-header-data" data-stdt="{{ $stdt }}" data-endt="{{ $endt }}">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-6 text-right">
			{!! Form::open(array('url' => 'admin/payout-report','method' => 'get')) !!}
			<input type="hidden" name="startdt" id="startdt">
			<input type="hidden" name="enddt" id="enddt">
			<button class="md-btn md-fab m-b-sm blue pull-right" style="margin-top:-5px;"><i class="material-icons md-24">&#xE8B6;</i></button>
			<lable id="payout_report_range" class="pull-right datepicker_cls"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>
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
						<th>Name / Plan Name / Nature of Plan</th>
						<th>Deposit Date</th>
						<th>Maturity Date</th>
						<th>Deposit Amount ($)</th>
						<th>Interest ($)</th>
						<th>Payout Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$a = 1;
					$finalTotal = 0;
					 ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<tr>
						<th scope="row">{{ $a }}</th>
						<td>{{ ucfirst($singleRecord['name']) }} | {{ $singleRecord['username'] }}
						<br>
						<span class="text-info" title="Plan Name">{{ $singleRecord['plan_name'] }}</span> <br> <span class="text-success" title="Nature of Plan">{{ $nature_of_plans[$singleRecord['nature_of_plan']] }}</span>
						</td>
						<td nowrap="nowrap">{{ dispayTimeStamp($singleRecord['startDate'])->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">{{ dispayTimeStamp($singleRecord['endDate'])->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord['amount'],2) }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord['interest'],2) }}</td>
						<?php
						$totalAmt = 0;
						if($singleRecord['nature_of_plan'] == 1)
						{
							$totalAmt = $singleRecord['amount'] + $singleRecord['interest'];	
						}
						else if($singleRecord['nature_of_plan'] == 3)
						{
							$totalAmt = 0;	
						}
						else if($singleRecord['nature_of_plan'] == 4)
						{
							$totalAmt = $singleRecord['initialamt'];
						}
						else if($singleRecord['nature_of_plan'] == 2)
						{
							$totalAmt = $singleRecord['interest'];	
						}
						$finalTotal = $totalAmt + $finalTotal;
						?>
						<td nowrap="nowrap">$ {{ number_format($totalAmt,2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					<tfoot>
						<tr>
							<th colspan="5"></th>
							<th>Total</th>
							<th>{{ number_format($finalTotal,2) }}</th>
						</tr>
					</tfoot>
					@else
					<tr>
						<td class="text-center" colspan="7">No Records !</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/payout-report.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop