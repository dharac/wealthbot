<?php 
$page_name = 'ledger Summary';
$allRecord = $deposits;
$allRecord2 = $data;
$primary_key = 'capcod';


$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 text-left row">
				<div class="box-header">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-6 text-right">
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
        <div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Deposit Id</th>
            			<th>Plan Name</th>
            			<th>Date of Deposit</th>
						<th>Period</th>
						<th>Deposit Amt ($)</th>
						<th>Interest on Maturity ($)</th>
						<th>Total ($)</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = 1; $flag = 0; ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
						<tr>
							<td class="text-info" scope="row">{{ $a }}</td>
							<td class="text-info">{{ $singleRecord->depositno }}</td>
							<td class="text-info"><span data-html="true" data-toggle="tooltip" title="{{ $singleRecord->plan_name }}">{{ str_limit($singleRecord->plan_name,40) }}</span>
							<br><span class="text-success">{{ ucwords(str_replace('_', ' ', ucwords($singleRecord->status=="approved" ? "active" :$singleRecord->status ))) }}
							@if(@$singleRecord->description != '')
							<a href="javascript:void(0);" class="text-success" data-toggle="tooltip" title="{{ $singleRecord->description }}"> <i class="material-icons text-success">&#xE88F;</i></a>
							@endif
							</span>
							</td>
							<td class="text-info" nowrap="nowrap">{{ $singleRecord->created_at->toDayDateTimeString() }}</td>
              				<td class="text-info"></td>
							<td class="text-info" nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
							<td class="text-info">-</td>
							<td class="text-info" nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
						</tr>
						<?php  $withdraws = App\Withdraw::where('depositid',$singleRecord->depositid)->where('withdraw_type','deposit')->where('created_by',Auth::user()->id)->first(); ?>
						@foreach($allRecord2 as $singleRecord2)
						@if($singleRecord2['depositid'] == $singleRecord->depositid)
						<?php

						$textWithdraw = '';
						if(count($withdraws) > 0)
						{
							if($withdraws->created_at->toDateString() >= $singleRecord2['startDate']->toDateString()  && $withdraws->created_at->toDateString() <= $singleRecord2['endDate']->toDateString() )
							{
								if($flag == 0)
								{
									$textWithdraw = '<b><span class="text-danger"> * Withdrawal Deposit of <u>$ '.number_format($withdraws->amount,2).'</u> on date <u>'.$withdraws->created_at->toFormattedDateString().'</u> </span></b>';
									$flag = 1;
								}
							}
						}
						 ?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td nowrap="nowrap">{{ $singleRecord2['startDate']->toDayDateTimeString() }}  <b>to</b>  {{ $singleRecord2['endDate']->toDayDateTimeString() }} <br>{!! $textWithdraw !!}</td>
							<td nowrap="nowrap">$ {{ number_format($singleRecord2['amount'],2) }}</td>
							<td nowrap="nowrap">$ {{ number_format($singleRecord2['totalInterest'],2) }}</td>
							<td nowrap="nowrap">$ {{ number_format($singleRecord2['amount'] + $singleRecord2['totalInterest'],2) }}</td>
						</tr>
						@endif
						@endforeach
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

</div>
</div>
@endsection