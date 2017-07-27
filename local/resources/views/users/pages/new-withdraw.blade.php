<?php 
    $page_name = 'withdraw';
    $page_name2 = 'Withdrawal';
    $submit_link = '';
    $eid = '';
?>
@extends('admin.parts.layout')
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<div class="clearfix"></div>
<div class="col-md-12 padding">
    @if(Auth::user()->hasRole('user') &&  !App\myCustome\myCustome::bitCoinAddressValidate(Auth::user()->bitcoin_id))
        <div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong><i class="material-icons">&#xE003;</i> Alert!</strong> Indicates an empty or non-real  <b>Bitcoin Wallet Address</b>.  <a href="{{ url('admin/user/profile/update') }}" class="text-info">Update Profile</a></div>
    @endif
</div>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="row p-a">
                    @if($mode == 'View')
                        <div class="col-md-6 text-left row col-xs-12"><div class="box-header"><h2>{{ $mode.' '.ucfirst($page_name2) }}</h2></div></div>
                        <div class="col-md-6 text-right col-xs-12"><a class="md-btn md-fab m-b-sm blue" href="{{ url('user/'.$page_name.'') }}" title="Back to {{ ucfirst($page_name2) }}s"><i class="material-icons">&#xE15E;</i></a></div>
                    @else
                        <?php $total_wallet_amount = $wallet['wallet_total']; ?>
                        <div class="col-md-6 text-left row col-xs-10"><div class="box-header"><h2>{{ $mode.' '.ucfirst($page_name2) }}</h2></div></div>
                        <div class="col-md-6 text-right col-xs-12"><div class="box-header"><h2 class="text-info">Your Wallet Balance $ {{ number_format($total_wallet_amount,2) }}</h2></div></div>
                    @endif
                </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    @if($mode == 'Add' || $mode == 'Edit')
                    {!! Form::open(array('url' => '/user/withdraw/store' , 'id' => 'new_withdrawal')) !!}
					<div class="form-group row {{ $errors->has('amount') ? 'has-danger' : '' }}">
						<label class="col-sm-2 form-control-label">Amount $ (USD) *</label>
						<div class="col-sm-10">
							{{ Form::text('amount', '', ['class' => 'form-control' ,'id' => 'amount' , 'placeholder' => 'Enter Amount $ (USD)']) }}
							@if ($errors->has('amount'))
		                    	<span class="parsley-required">{{ $errors->first('amount') }}</span>
		                	@endif
						</div>
					</div>

					<div class="dker p-a text-right">
                        <a href="{{ URL('user/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                        <button type="submit" class="btn btn-fw primary btn-go-fast-withdrawal"><span><i class="fa fa-location-arrow"></i>&nbsp;{{ $page_name2 }} Now</span></button>
                    </div> 
					{!! Form::close() !!}
                    @else
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>Amount</td>
                                    <th>$ {{ number_format($withdraw->amount,2) }}</th>
                                </tr>

                                <tr>
                                    <td>{{ $page_name2 }} Type</td>
                                    <th>{{ $WithdrawType[$withdraw->withdraw_type] }}</th>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <th>
                                        @if($withdraw->status == 'approved')
                                            <span class="text-success" title="{{ ucfirst($withdraw->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($withdraw->status) }}</span>
                                        @elseif($withdraw->status == 'pending')
                                            <span class="text-danger" title="{{ ucfirst($withdraw->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($withdraw->status) }}</span>
                                        @else
                                            <span class="text-info" title="{{ ucfirst($withdraw->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($withdraw->status) }}</span>
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <td>Withdrawal Date</td>
                                    <th title="{{ $withdraw->created_at->diffForHumans() }}">{{ $withdraw->created_at->toDayDateTimeString() }}</th>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection