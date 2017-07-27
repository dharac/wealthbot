<?php 
$allRecord 				= $walletsRecords;
$page_name 				= 'Wallet';
$name 					= Auth::user()->first_name.' '.Auth::user()->last_name;
$total_wallet_amount 	= 0;
$total_commission 		= 0;
$totalDeposit 			= 0;
$totalInitial 			= 0;
$totalInterest 			= 0;
$total_wallet_amount 	= $wallet['wallet_total'];
$total_commission 		= $commission['commission_total'];
$totalInterest 			= $interest['interest_in'] - $interest['interest_out'];
$totalInitial 			= $initial['initial_in'] - $initial['initial_out'];
$totalDeposit 			= $deposit['deposit_in'] - $deposit['deposit_out'];
$total_wallet_amount 	= number_format((float)$total_wallet_amount, 2, '.', '');
$total_commission 		= number_format((float)$total_commission, 2, '.', '');
$totalInterest 			= number_format((float)$totalInterest, 2, '.', '');
$totalInitial 			= number_format((float)$totalInitial, 2, '.', '');
$totalDeposit 			= number_format((float)$totalDeposit, 2, '.', '');
$deposit_text 			= Crypt::encryptString('1');
$interest_text 			= Crypt::encryptString('2');
$commission_text 		= Crypt::encryptString('3');
$initial_text 			= Crypt::encryptString('4');
$mode 					= Crypt::encryptString('wallet');
$submit_link 			= 'user/amount/move';
?>
@extends('admin.parts.layout')
@section('adminTitle', $page_name.' | '.$name)
@section('adminBody')
<div class="padding">
	<p>Your Wallet Balance: <strong>$ {{ number_format($total_wallet_amount,2) }}</strong> @if($total_wallet_amount > 0)&nbsp;&nbsp;&nbsp;<a href="{{ URL('user/deposit/new/wallet') }}" class="btn btn-sm purple"><i class="material-icons">&#xE862;</i> Redeposit Amount</a>&nbsp;&nbsp;&nbsp;<a href="{{ URL('user/deposit/new/wallet/another') }}" class="btn btn-sm cyan"> <i class="material-icons">&#xE84E;</i> Redeposit For Another User</a>&nbsp;&nbsp;&nbsp;<a href="{{ URL('user/withdraw/new') }}" class="btn btn-sm red"> <i class="material-icons">&#xE8D4;</i> Withdrawal Amount</a>@endif</p>
	<div class="box">
		<div class="row p-a"><div class="box-header"> <h2>{{ ucwords($page_name) }}<small>{{ $name }}</small></h2></div></div>
		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>DESCRIPTION</th>
						<th>AMOUNT ($)</th>
						<th>TRANSFER AMOUNT</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody>
						<tr>
							{!! Form::open(array('url' => $submit_link)) !!}
	                    	{{ Form::hidden('eid', $commission_text, array('id' => 'eid')) }}
								<td>1</td>
								<td>Available Commission</td>
								<td>$ {{ number_format($total_commission,2) }}</td>
								<td>{{ Form::text('amount', '', ['class' => 'form-control' ,'id' => 'amount' , 'placeholder' => 'Amount']) }}</td>
								<td><button class="btn btn-sm blue @if($total_commission <= 0) disabled @endif" @if($total_commission <= 0) disabled @endif title="Move to Wallet"><i class="material-icons">&#xE850;</i> Move to Wallet</button></td>
							{!! Form::close() !!}
						</tr>
						<tr>
							{!! Form::open(array('url' => $submit_link)) !!}
	                    	{{ Form::hidden('eid', $interest_text, array('id' => 'eid')) }}
								<td>2</td>
								<td>Available Interest Out</td>
								<td>$ {{ number_format($totalInterest,2) }}</td>
								<td>{{ Form::text('amount', '', ['class' => 'form-control' ,'id' => 'amount' , 'placeholder' => 'Amount']) }}</td>
								<td><button class="btn btn-sm blue @if($totalInterest <= 0) disabled @endif" @if($totalInterest <= 0) disabled @endif  title="Move to Wallet"><i class="material-icons">&#xE850;</i> Move to Wallet</button></td>
							{!! Form::close() !!}
						</tr>
						<tr>
							{!! Form::open(array('url' => $submit_link)) !!}
	                    	{{ Form::hidden('eid', $deposit_text, array('id' => 'eid')) }}
								<td>3</td>
								<td>Available All Out</td>
								<td>$ {{ number_format($totalDeposit,2) }}</td>
								<td>{{ Form::text('amount', '', ['class' => 'form-control' ,'id' => 'amount' , 'placeholder' => 'Amount']) }}</td>
								<td><button class="btn btn-sm blue @if($totalDeposit <= 0) disabled @endif" @if($totalDeposit <= 0) disabled @endif  title="Move to Wallet"><i class="material-icons">&#xE850;</i> Move to Wallet</button></td>
							{!! Form::close() !!}
						</tr>
						<tr>
							{!! Form::open(array('url' => $submit_link)) !!}
	                    	{{ Form::hidden('eid', $initial_text, array('id' => 'eid')) }}
								<td>4</td>
								<td>Available Initial Deposit Out</td>
								<td>$ {{ number_format($totalInitial,2) }}</td>
								<td>{{ Form::text('amount', '', ['class' => 'form-control' ,'id' => 'amount' , 'placeholder' => 'Amount']) }}</td>
								<td><button class="btn btn-sm blue @if($totalInitial <= 0) disabled @endif" @if($totalInitial <= 0) disabled @endif title="Move to Wallet"><i class="material-icons">&#xE850;</i> Move to Wallet</button></td>
							{!! Form::close() !!}
						</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="padding">
	<div class="box">
		<div class="row p-a"><div class="box-header"> <h2>My Wallet Transactions</h2></div></div>
		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
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
						<td>
						@if($singleRecord->status == 'redeposit' || $singleRecord->status == 'withdraw' || $singleRecord->status == 'redeposit_another_user')
							Wallet <i class="material-icons">&#xE5C8;</i>{{ $status[$singleRecord->status] }}
						@else
							{{ $status[$singleRecord->status] }} <i class="material-icons">&#xE5C8;</i> To Wallet
						@endif
						</td>
						<td>
							@if($singleRecord->status == 'redeposit')
							Deposit Id : <a href="{{ URl('user/deposit/view/'.$singleRecord->deposit->depositid.' ') }}" class="text-info">{{ $singleRecord->deposit->depositno }}</span>
							@elseif($singleRecord->status == 'redeposit_another_user')
							Deposit Id : <span class="text-primary">{{ $singleRecord->deposit->depositno }} </span><br> User : {{ $singleRecord->deposit->user->first_name }} {{ $singleRecord->deposit->user->last_name }} | {{ $singleRecord->deposit->user->username }}
							@elseif($singleRecord->status == 'withdraw')
							Withdraw Id : <a href="{{ URL('user/withdraw/view/'.$singleRecord->withdraw->withdrawcod.' ') }}" class="text-info">{{ $singleRecord->withdraw->withdrawno }}</a>
							@else
							-
							@endif
						</td>
						<td title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
						<td>$ {{ number_format($singleRecord->amount,2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="5">No Records !</td>
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