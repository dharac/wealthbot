<?php 
$page_name = 'Pending Interest Payment';
$allRecord = $result['list'];
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
}

$pendingInterest = $result['pendingInterest'];
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row">
				<div class="box-header box-header-data">
					<h2>{{ $page_name }}</h2>
				</div>
			</div>
			<div class="col-md-6 row">
				
				<div class="form-group">
				{{ Form::select('nature_of_plan', array('' => '-- All Nature of Plan  --' ) + $natureOfPlan, $natureofplanId , array('id' => 'nature_of_plan','class' => 'form-control select2' ,'ui-options' => "{theme: 'bootstrap'}" ,'ui-jp'=>"select2" )) }}
      			</div>

			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t" ui-jp="dataTable" ui-options="{ bSort: false, pageLength: {{ config('services.DATATABLE.PERPAGE') }} }">
				<thead>
					<tr>
						<th>#</th>
						<th>Name / Plan Name</th>
						<th>Status</th>
						<th>Interest Due Date</th>
						<th>Deposit Amount ($)	</th>
						<th title="Commissions">Interest ($)</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = 1; ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<tr>
						<th scope="row">{{ $a }}</th>
						<td>{{ ucfirst($singleRecord['name']) }} | {{ $singleRecord['username'] }}
						<br>
						<span class="text-info">{{ $singleRecord['plan_name'] }}</span>
						</td>
						<td nowrap="nowrap"><span class="text-danger" title="Pending"><i class="material-icons">&#xE88F;</i> Pending</span></td>
						<td title="{{ dispayTimeStamp($singleRecord['endDate'])->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord['endDate'])->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord['amount'],2) }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord['interest'],2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="6">No Records !</td>
					</tr>
					@endif
				</tbody>
				<tfoot>
					<tr>
						<th colspan="5" class="text-right no-border"><strong>Total</strong></th>
						<th nowrap="nowrap"><strong>$ {{ number_format($pendingInterest,2) }}</strong></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/interest-payment.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop