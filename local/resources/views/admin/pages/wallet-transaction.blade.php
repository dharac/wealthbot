<?php 
$page_name 		= 'Wallet Transactions';
$allRecord 		= $walletsRecords;
$primary_key 	= 'coucod';

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
		<div class="row p-a"><div class="box-header"> <h2>{{ ucwords($page_name) }}</h2></div></div>
		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Name / Username</th>
						<th>Transfer From / Transfer To</th>
						<th>Description</th>
						<th>Transfer Date</th>
						<th>Transfer Amount ($)</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<tr>
						<th scope="row">{{ $a }}</th>
						<td><a href="{{ URL('admin/user/view/'.$singleRecord->user->id.'') }}" class="text-info"> {{ $singleRecord->user->first_name }} {{ $singleRecord->user->last_name }} | {{ $singleRecord->user->username }}</a></td>
						<td>
						@if($singleRecord->status == 'redeposit' || $singleRecord->status == 'withdraw' || $singleRecord->status == 'redeposit_another_user')
							Wallet <i class="material-icons">&#xE5C8;</i>{{ $status[$singleRecord->status] }}
						@else
							{{ $status[$singleRecord->status] }} <i class="material-icons">&#xE5C8;</i> To Wallet
						@endif
						</td>
						<td>
							@if($singleRecord->status == 'redeposit')
							Deposit Id : <a href="{{ URL('admin/loan/view/'.$singleRecord->deposit->depositid.'') }}" class="text-info">{{ $singleRecord->deposit->depositno }}</a>
							@elseif($singleRecord->status == 'redeposit_another_user')
							Deposit Id : <a href="{{ URL('admin/loan/view/'.$singleRecord->deposit->depositid.'') }}" class="text-info">{{ $singleRecord->deposit->depositno }}</a>
							<br> User : {{ $singleRecord->deposit->user->first_name }} {{ $singleRecord->deposit->user->last_name }} | {{ $singleRecord->deposit->user->username }}
							@elseif($singleRecord->status == 'withdraw')
							Withdraw Id : <a href="{{ URL('admin/withdraw/view/'.$singleRecord->withdraw->withdrawcod.'') }}" class="text-info">{{ $singleRecord->withdraw->withdrawno }}</span>
							@else 
							-
							@endif
						</td>
						<td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="4">No Records !</td>
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