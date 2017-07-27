<?php
$page_name = 'available payouts';
$page_name2 = 'withdraw';
$allRecord = $availabelPayouts;
$primary_key = 'withdrawid';

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
            <div class="col-md-12 col-xs-12 text-left row">
                <div class="box-header">
                    <h2>{{ ucwords($page_name) }}</h2>
                </div>
            </div>
        </div>

        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped b-t">
                <thead>
					<tr>
						<th>#</th>
						<th>Details</th>
						<th>Created Date</th>
						<th>Amount</th>
					</tr>
				</thead>
                <tbody>
				<?php $a = $allRecord->firstItem(); ?>
				@if(count($allRecord) > 0)
				@foreach($allRecord as $singleRecord)
				<?php  $primary_column = $singleRecord->$primary_key;  ?>
				<tr title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
					<th scope="row">{{ $a }}</th>
					<td>
					@if($singleRecord->status == 'interest')
					<span class="text-success"><i class="material-icons">&#xE877;</i> From Interest</span>
					@elseif($singleRecord->status == 'all_out')
					<span class="text-primary"><i class="material-icons">&#xE877;</i> From Deposit</span>
					@elseif($singleRecord->status == 'withdraw_initial_deposit')
					<span class="text-info"><i class="material-icons">&#xE877;</i> From Initial Out</span>
					@endif<br>
					<a href="{{ URL('user/deposit') }}" class="text-info">{{ $singleRecord->depositno }}</a><br>
					{{ $singleRecord->plan_name }}
					</td>
					<td>{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
					<td>$ {{ number_format($singleRecord->amount,2) }}</td>
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
