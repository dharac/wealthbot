<?php 
$page_name      = 'deposit';
$submit_link    = '';
$eid            = '';
?>
@extends('admin.parts.layout')  
@section('adminTitle', ucfirst($mode).' '.ucfirst($page_name))
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="row p-a">
                    @if($mode == 'View')
                    <div class="col-md-6 text-left row col-xs-10">
                        <div class="box-header">
                            <h2>{{ ucfirst($mode).' '.ucfirst($page_name) }}</h2>
                        </div>
                    </div>
                    <div class="col-md-6 text-right col-xs-2">
                        <a class="md-btn md-fab m-b-sm blue" href="{{ url('user/deposit') }}" title="Back to Deposit History"><i class="material-icons">&#xE15E;</i></a>
                    </div>
                    @else
                     <div class="col-md-6 text-left row col-xs-10">
                        <div class="box-header">
                            <h2>{{ ucfirst($mode).' '.ucfirst($page_name) }}</h2>
                        </div>
                    </div>
                    @if($type ==  'wallet' || $type ==  'user')
                    <?php $total_wallet_amount = $wallet['wallet_total']; ?>
                    <div class="col-md-6 text-right row col-xs-10">
                        <div class="box-header">
                            <h2 class="text-info">Your Wallet Balance $ {{ number_format($total_wallet_amount,2) }}</h2>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    @if($mode == 'Add' || $mode == 'Edit')
                    {!! Form::open(array('url' => '/user/deposit/store' , 'id' => 'new_deposit')) !!}
                    {{ Form::hidden('type', $type, array('id' => 'type')) }}
                    @if($type ==  'user')
                        <div class="form-group row {{ $errors->has('user') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Select User *</label>
                                <div class="col-sm-10">
                                    {{ Form::select('user', array('' => '-- Select User --') + $users, '', array('id' => 'country','class' => 'form-control col-md-7 col-xs-12 select2' , 'ui-jp' => 'select2' , 'ui-options' => "{theme: 'bootstrap'}")) }}
                                    @if ($errors->has('user'))
                                        <span class="parsley-required">{{ $errors->first('user') }}</span>
                                    @endif
                            </div>
                        </div>
                    @endif
                    <div class="form-group row {{ $errors->has('rdplan') ? 'has-danger' : '' }}">
                        <label class="col-sm-2 form-control-label">Plans *</label>
                        <div class="col-sm-10">
                            @foreach($plans as $plan)
                            <label class="md-check form-control-label"> {{ Form::radio('rdplan', $plan->planid , '' , []) }} <i class="blue"></i>{{ $plan->plan_name }}</label><br>
                            @endforeach
                            @if ($errors->has('rdplan'))
                            <span class="parsley-required">{{ $errors->first('rdplan') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row {{ $errors->has('amount') ? 'has-danger' : '' }}">
                        <label class="col-sm-2 form-control-label">Amount $ (USD) *</label>
                        <div class="col-sm-10">
                            {{ Form::text('amount', '', ['class' => 'form-control' ,'id' => 'amount' , 'placeholder' => 'Enter Amount in USD']) }}
                            @if ($errors->has('amount'))
                            <span class="parsley-required">{{ $errors->first('amount') }}</span>
                            @endif
                        </div>
                    </div> 

                    <div class="dker p-a text-right">
                        <a href="{{ URL('user/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                        <button type="submit" class="btn btn-fw primary btn-go-fast-deposit"><span><i class="fa fa-location-arrow"></i>&nbsp;Deposit Now</span></button>
                    </div>
                    {!! Form::close() !!}
                    @else
                    <div class="table-responsive">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>Plan Name</td>
                                <th>{{ $investment->plan_name ? $investment->plan_name : '-' }}</th>
                            </tr>
                            <tr>
                                <td>Amount ($)</td>
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
                        <span class="text-info m-r-sm" title="{{ ucfirst($investment->status) }}"><i class="material-icons">&#xE90A;</i> {{ ucfirst($investment->status) }}</span>
                        @endif</th>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>


@if($mode == 'Add' || $mode == 'Edit')
<div class="padding">
    <div class="box">
        <div class="row p-a">
            <div class="col-md-6 text-left row">
                <div class="box-header">
                    <h2>Active Plans</h2>
                </div>
            </div>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped b-t">
                <thead>
                    <tr>
                        <th>Plan Name</th>
                        <th>Min Amt ($) - Max Amt ($)</th>
                        <th>Private Loan Interest Payment (%)</th>
                        <th>Terms</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                    <tr>
                        <td>{{ ucfirst($plan->plan_name) }}</td>
                        <td nowrap="nowrap">$ {{ number_format($plan->spend_min_amount,2) }}&nbsp;&nbsp;&nbsp;<b>-</b>&nbsp;&nbsp;&nbsp;$ {{ number_format($plan->spend_max_amount,2) }}</td>
                        <td nowrap="nowrap">{{ number_format($plan->profit,2) }} % {{ $plan->periodofProfit }}</td>
                        <td nowrap="nowrap">
                            @if($plan->plan_status == '1')
                            {{ intval($plan->duration) }}  {{ $paymentPeriods[$plan->duration_time] }}
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/deposit.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop