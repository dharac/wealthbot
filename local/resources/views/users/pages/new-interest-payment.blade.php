<?php 
$page_name = 'Interest Payment';
 ?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.$page_name)
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">

            <div class="box">

            <div class="row p-a">
            <div class="col-md-6 text-left row col-xs-10">
                <div class="box-header">
                    <h2>{{ $mode.' '.ucfirst($page_name) }}</h2>
                </div>
            </div>
            @if($mode == 'View')
            <div class="col-md-6 text-right col-xs-2">
                <a class="md-btn md-fab m-b-sm blue" href="{{ url('user/interest-payment') }}" title="Back to {{ $page_name }}"><i class="material-icons">&#xE15E;</i></a>
            </div>
            @endif
        </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">

                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>Plan Name</td>
                                <th>{{ $interestPayment->plan_name }} </th>
                            </tr>
                            <tr>
                                <td>Amount $ (USD)</td>
                                <th>$ {{ number_format($interestPayment->amount,2) }}</th>
                            </tr>
                            <tr>
                                <td>Interest $ (USD)</td>
                                <th>$ {{ number_format($interestPayment->pro_amount,2) }}</th>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <th>@if($interestPayment->status == 'approved')
                                <span class="text-success" title="{{ ucfirst($interestPayment->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($interestPayment->status) }}</span>
                                @elseif($interestPayment->status == 'pending')
                                <span class="text-danger" title="{{ ucfirst($interestPayment->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($interestPayment->status) }}</span>
                                @else
                                <span class="text-info" title="{{ ucfirst($interestPayment->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($interestPayment->status) }}</span>
                                @endif</th>
                            </tr>
                            <tr>
                                <td>Created Date</td>
                                <th title="{{ $interestPayment->created_at->diffForHumans() }}">{{ $interestPayment->created_at->toDayDateTimeString() }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
