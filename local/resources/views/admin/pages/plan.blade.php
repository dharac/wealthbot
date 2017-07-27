<?php
$page_name = 'plan';
$allRecord = $plans;
$primary_key = 'planid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name).'s')
@section('adminBody')
    <div class="padding">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 text-left row col-xs-6">
                    <div class="box-header">
                        <h2>{{ ucwords($page_name) }}s</h2>
                    </div>
                </div>
                <div class="col-md-6 text-right col-xs-6">
                    <a class="md-btn md-fab m-b-sm blue" href="{{ URL('admin/'.$page_name.'/new') }}" title="Add {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE145;</i></a>
                    <a class="md-btn md-fab m-b-sm indigo hidden" id="linkSearchMaster" href="javascript:void(0);" title="Search {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE8B6;</i></a>
                    <a class="md-btn md-fab m-b-sm pink hidden" href="#" title="Delete {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE872;</i></a>
                </div>
            </div>

            <div class="box-divider m-a-0"></div>
            <div class="table-responsive">
                <table class="table table-striped b-t">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Plan Name</th>
                        <th title="Minimum Amount ($)">Min Amt ($)</th>
                        <th title="Maximum Amount ($)">Max Amt ($)</th>
                        <th title="Loan Interest Payment (%)">Loan Int Pay (%)</th>
                        <th>Terms</th>
                        <th>Founder</th>
                        <th>Status</th>
                        <th>Modified Date</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $a = $allRecord->firstItem(); ?>
                    @if(count($allRecord) > 0)
                        @foreach($allRecord as $singleRecord)
                            <?php  $primary_column = $singleRecord->$primary_key;  ?>
                            <tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
                                <th scope="row">{{ $a }}</th>
                                <td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.'') }}" title="Edit Record" >{{ ucfirst($singleRecord->plan_name) }}</a></td>
                                <td nowrap="nowrap">$ {{ number_format($singleRecord->spend_min_amount,2) }}</td>
                                <td nowrap="nowrap">$ {{ number_format($singleRecord->spend_max_amount,2) }}</td>
                                <td nowrap="nowrap">{{ number_format($singleRecord->profit,2) }} % {{ $singleRecord->periodofProfit }}</td>
                                <td nowrap="nowrap">
                                @if($singleRecord->plan_status == '1')
                                {{ intval($singleRecord->duration) }} {{ $singleRecord->periodofDuration }}
                                @else
                                -
                                @endif
                                </td>
                                <td>@if($singleRecord->founder == 1) Yes @else - @endif</td>
                                <td nowrap="nowrap">
                                @if($singleRecord->status == 'active')
                                <span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
                                @else
                                <span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($singleRecord->status) }}</span>
                                @endif
                                </td>
                                <td title="{{ dispayTimeStamp($singleRecord->updated_at)->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->updated_at)->toDayDateTimeString() }}</td>
                                <td nowrap="nowrap"><a href="{{ URL('admin/'.$page_name.'/view/'.$primary_column.' ') }}" data-placement="left" title="View Record"><i class="material-icons">&#xE8F4;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ URL('admin/plan/edit/'.$primary_column.'') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a><!-- &nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" class="{{ $primary_table_col_del }}" data-id="{{ $primary_column }}" data-pid="{{ $page_name }}" title="Delete Record"><i class="material-icons">&#xE872;</i></a> --></td>
                            </tr>
                            <?php $a++; ?>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="9">No Records !</td>
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