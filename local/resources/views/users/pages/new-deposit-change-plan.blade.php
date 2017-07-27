<?php 
$page_name = 'Change Plan';
$submit_link = '';
$eid = '';
?>
@extends('admin.parts.layout')  
@section('adminTitle', ucfirst($page_name))
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="row p-a">
                    <div class="col-md-6 text-left row col-xs-12">
                        <div class="box-header"><h2>{{ ucfirst($page_name) }}</h2></div>
                    </div>
                    <div class="col-md-6 text-right row col-xs-12">
                        <div class="message-update"></div>
                    </div>
                </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Deposit Id</th>
                                    <th>Plan Name</th>
                                    <th>Period</th>
                                    <th>Deposit Amount($)</th>
                                    <th>Interest on Maturity ($)</th>
                                    <th>Total ($)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deposits['result1'] as $deposit)
                                <tr class="dp-main-{{ $deposit->depositid }}">
                                    <?php 
                                    if(array_key_exists('data'.$deposit->depositid, $deposits['result2']))
                                    {
                                        $singleRecord2 = $deposits['result2']['data'.$deposit->depositid];

                                        $to             = $singleRecord2['startDate'];
                                        $from           = $singleRecord2['endDate'];

                                        if($deposit->plan_status == 1)
                                        {
                                            $planDuration           = intval($deposit->duration); //001
                                            $planPeriod             = $deposit->duration_time; // MONTH WEEK DAY HOUR YEAR ETC..
                                            $planEndDate            = App\myCustome\myCustome::getAccDate($deposit->created_at,$planPeriod,$planDuration);
                                            $from = $planEndDate;
                                        }

                                        $diff_in_days   = $to->diffInDays($from);
                                        $lbltext        = "";
                                        $btnText        = '<button class="md-btn md-fab m-b-sm grey" disabled="disabled"><i class="material-icons" data-toggle="tooltip" title="Plan change can be done only prior to 15 days of cycle expiration date">&#xE163;</i></button>';
                                        $today          = Carbon\Carbon::now();

                                        if($diff_in_days >= 30)
                                        {
                                            $btnText         = '<button class="md-btn md-fab m-b-sm blue plan-change-popup" title="Change Plan" data-id="'.$deposit->depositid.'"><i class="material-icons">&#xE163;</i></button>';
                                            $from1           = clone $from;
                                            $getDate         = App\myCustome\myCustome::getAccDate($from1,2,-15);

                                            if(strtotime(Carbon\Carbon::now()) >= strtotime($getDate))
                                            {
                                                $btnText = '<button class="md-btn md-fab m-b-sm grey"  disabled="disabled"><i class="material-icons" data-toggle="tooltip" title="Plan change can be done only prior to 15 days of cycle expiration date">&#xE163;</i></button>';
                                            }

                                            $dpc = App\DepositPlanChange::getSingle($deposit->depositid);
                                            if(count($dpc) > 0)
                                            {
                                                if($dpc->status == 'approved')
                                                {
                                                    $btnText = '<button class="md-btn md-fab m-b-sm red plan-request-cancel" data-id="'.$deposit->depositid.'" data-pl="'.$dpc->new_planid.'" data-toggle="tooltip" title="Cancel Request"><i class="material-icons md-24">&#xE888;</i></button>';
                                                    $lbltext = '<i class="material-icons">&#xE003;</i> You have changed the current plan to <b>'.$dpc->new_plan_name.'</b>. You can cancel this change up to 15 days before the end of the current cycle up until <b>'.dispayTimeStamp($getDate)->toDayDateTimeString().' '.Config::get('app.timezone_display2').'</b>';
                                                }
                                            }
                                        }
                                    ?>
                                    <td nowrap="nowrap" class="text-info">{{ $deposit->depositno }}</td>
                                    <td  title="{{ $deposit->plan_name }}" data-html="true" data-toggle="tooltip" >{{ str_limit($deposit->plan_name,40) }}</td>
                                    <td nowrap="nowrap">{{ dispayTimeStamp($singleRecord2['startDate'])->toDayDateTimeString() }}  <b>to</b>  {{ dispayTimeStamp($singleRecord2['endDate'])->toDayDateTimeString() }} </td>
                                    <td nowrap="nowrap">$ {{ number_format($singleRecord2['amount'],2) }}</td>
                                    <td nowrap="nowrap">$ {{ number_format($singleRecord2['totalInterest'],2) }}</td>
                                    <td nowrap="nowrap">$ {{ number_format($singleRecord2['amount'] + $singleRecord2['totalInterest'],2) }}</td>
                                    <td class="plan_ch_btn_{{ $deposit->depositid }}">{!! $btnText !!}</td>
                                    <?php }
                                    else
                                    { ?>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <?php }
                                    ?>
                                    <td class="text-center"></td>
                                </tr>
                                @if($lbltext != "")
                                <tr class="dp-message-{{ $deposit->depositid }}">
                                    <td colspan="7" class="text-center text-danger">{!! $lbltext !!}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="change_plan_modal" class="modal" data-backdrop="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Change Plan</h5></div>
            <div class="modal-body text-center p-lg">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Plan Name</th>
                                <th>Minimum Amount ($)</th>
                                <th>Maximum Amount ($)</th>
                                <th>Loan Interest (%)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn danger p-x-md" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
<script src="{!! URL::asset('local/assets/js/deposit-change-plan.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop