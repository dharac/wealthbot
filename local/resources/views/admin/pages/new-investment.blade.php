<?php 
$page_name = 'Private Loan';
 ?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.'Private Loan')
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">

            <div class="box">

            <div class="row p-a">
            <div class="col-md-6 text-left row">
                <div class="box-header">
                    <h2>{{ $mode.' '.ucfirst($page_name) }}</h2>
                </div>
            </div>
            @if($mode == 'View')
            <div class="col-md-6 text-right">
                <a class="md-btn md-fab m-b-sm blue" href="{{ url('admin/loan') }}" title="Back to {{ $page_name }}"><i class="material-icons">&#xE15E;</i></a>
            </div>
            @endif
        </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody> 
                                <tr>
                                    <td>Deposit Id</td>
                                    <th>{{ $investment->depositno }}</th>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <th>{{ ucfirst($investment->first_name) }} {{ $investment->last_name ? ucfirst($investment->last_name) : '' }}</th>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <th>{{ $investment->username }}</th>
                                </tr>
                                <tr>
                                    <td>Plan Name</td>
                                    <th>{{ $investment->plan_name ? $investment->plan_name : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Amount</td>
                                    <th>$ {{ $investment->amount ? number_format($investment->amount,2) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <th>{{ $investment->currency ? $investment->currency : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Transaction ID</td>
                                    <th>{{ $investment->transaction_id ? $investment->transaction_id : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Payment Through</td>
                                    <th>{{ $investment->payment_through ? ucwords($investment->payment_through) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Deposit Date</td>
                                    <th title="{{ $investment->created_at->diffForHumans() }}">{{ $investment->created_at->format('D, M d, Y') }}</th>
                                </tr>
                                <tr>
                                    <td>Maturity Date</td>
                                    <th>@if($investment->plan_status == 1)
                                {{ Carbon\Carbon::createFromFormat('Y-m-d', $investment->maturity_date)->format('D, M d, Y') }}
                                @else
                                -
                                @endif</th>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <th>@if($investment->status == 'approved')
                                    <span class="text-success" title="{{ ucfirst($investment->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($investment->status) }}</span>
                                    @elseif($investment->status == 'pending')
                                    <span class="text-danger" title="{{ ucfirst($investment->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($investment->status) }}</span>
                                    @else
                                    <span class="text-info" title="{{ ucfirst($investment->status) }}"><i class="material-icons">&#xE90A;</i> {{ ucfirst($investment->status) }}</span>
                                    @endif</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
