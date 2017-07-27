<?php  $page_name = 'withdraw';  ?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
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
                <a class="md-btn md-fab m-b-sm blue" href="{{ url('admin/withdraw') }}" title="Back to {{ $page_name }}"><i class="material-icons">&#xE15E;</i></a>
                @if($withdraw->status == 'pending')
                    {!! Form::open(array('url' => 'admin/withdraw-pay/' , 'style' =>     'display: inline-block;' )) !!}
                        {{ Form::hidden('eid', $withdraw->withdrawcod , array('id' => 'eid')) }}
                        <button class="md-btn md-fab m-b-sm indigo" style="float: right;margin-left:5px;" title="Click to Approve" onclick="return confirm('Are you sure you want to Approve this Withdraw ?');"><i class="material-icons">&#xE163;</i></button>
                    {!! Form::close() !!}
                @endif                
            </div>
            @endif
        </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">

                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <th>{{ ucfirst($withdraw->first_name) }} {{ $withdraw->last_name }}</th>
                            </tr>
                            <tr>
                                <td>Username</td>
                                <th>{{ $withdraw->username }}</th>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <th>$ {{ $withdraw->amount ? number_format($withdraw->amount,2) : '-' }}</th>
                            </tr>

                            <tr>
                                <td>Withdraw Type</td>
                                <th>{{ $WithdrawType[$withdraw->withdraw_type] }}</th>
                            </tr>

                            <tr>
                                <td>Status</td>
                                <th>@if($withdraw->status == 'approved')
                                <span class="text-success" title="{{ ucfirst($withdraw->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($withdraw->status) }}</span>
                                @elseif($withdraw->status == 'pending')
                                <span class="text-danger" title="{{ ucfirst($withdraw->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($withdraw->status) }}</span>
                                @else
                                <span class="text-info" title="{{ ucfirst($withdraw->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($withdraw->status) }}</span>
                                @endif</th>
                            </tr>
                            <tr>
                                <td>Created Date</td>
                                <th title="{{ $withdraw->created_at->diffForHumans() }}">{{ $withdraw->created_at->toDayDateTimeString() }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
