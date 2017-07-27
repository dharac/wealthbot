<?php
$page_name = 'Withdrawal History';
$page_name2 = 'withdraw';
$allRecord = $withdraws;
$primary_key = 'withdrawid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', $page_name)
@section('adminBody')

@if(Auth::user()->hasRole('user') &&  !App\myCustome\myCustome::bitCoinAddressValidate(Auth::user()->bitcoin_id))
<div class="col-md-12 padding" style="padding-bottom: 0px;">
<div class="alert alert-warning">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
  <strong><i class="material-icons">&#xE003;</i> Alert!</strong> Indicates an empty or non-real  <b>Bitcoin Wallet Address</b>.  <a href="{{ url('admin/user/profile/update') }}" class="text-info">Update Profile</a>
</div>
</div>
<div class="clearfix"></div>
@endif

    <div class="padding">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 col-xs-6 text-left row">
                    <div class="box-header">
                        <h2>{{ ucwords($page_name) }}</h2>
                    </div>
                </div>
                <div class="col-md-6 col-xs-6 text-right">
                    <a class="md-btn md-fab m-b-sm blue" href="{{ URL('user/'.$page_name2.'/new') }}" title="Make {{ ucfirst($page_name2) }}"><i class="material-icons md-24">&#xE145;</i></a>
                </div>
            </div>

            <div class="box-divider m-a-0"></div>
            <div class="table-responsive">
                <table class="table table-striped b-t">
                    <thead>
						<tr>
							<th>#</th>
							<th>Withdrawal Id</th>
							<th>Withdrawal Type</th>
							<th>Status</th>
							<th>Withdrawal Date</th>
							<th>Withdrawal Amount ($)</th>
						</tr>
					</thead>
                    <tbody>
					<?php $a = $allRecord->firstItem(); ?>
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td>{{ $singleRecord->withdrawno }}</td>
						<td>{{ $WithdrawType[$singleRecord->withdraw_type] }}</td>
						<td>
						@if($singleRecord->status == 'approved')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
						@elseif($singleRecord->status == 'pending')
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@else
						<span class="text-info" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@endif</td>
						<td>{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
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
