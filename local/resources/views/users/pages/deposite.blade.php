<?php
$page_name = 'Deposit History';
$page_name2 = 'deposit';
$allRecord = $investments;
$primary_key = 'depositid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', $page_name)
@section('adminBody')
    <div class="padding">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 text-left row col-xs-10">
                    <div class="box-header">
                        <h2>{{ ucwords($page_name) }}</h2>
                    </div>
                </div>
                <div class="col-md-6 text-right col-xs-2">
                    <a class="md-btn md-fab m-b-sm blue" href="{{ URL('user/'.$page_name2.'/new') }}" title="Make {{ ucfirst($page_name2) }}"><i class="material-icons md-24">&#xE145;</i></a>
                </div>
            </div>

            <div class="box-divider m-a-0"></div>
            <div class="table-responsive">
                <table class="table table-striped b-t">
                    <thead>
						<tr>
							<th>#</th>
                            <th>Deposit Id / Plan Name</th>
							<th>Status</th>
                            <th>Deposit Date</th>
                            <th>Maturity Date</th>
                            <th>Amount ($)</th>
						</tr>
					</thead>
                    <tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr>
						<th scope="row">{{ $a }}</th>
                        <td><a class="text-info" href="{{ URL('user/'.$page_name2.'/view/'.$primary_column.' ') }}" data-placement="left" title="View Record">{{ $singleRecord->depositno }}</a><br>{{ $singleRecord->plan->plan_name }}</td>
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
                        <td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
                        <td nowrap="nowrap">
                            @if($singleRecord->plan->plan_status == 1)
                            @if($singleRecord->maturity_date != "")
                                {{ Carbon\Carbon::createFromFormat('Y-m-d', $singleRecord->maturity_date)->format('D, M d, Y') }}
                            @endif
                            @else
                            <center>-</center>
                            @endif
                        </td>
                        <td nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="5">No Records !</td>
					</tr>
					@endif
                    </tbody>
                    @if(count($allRecord) > 0)
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-right no-border"><strong>Total</strong></th>
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
                        {{ $allRecord->links() }}
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection
