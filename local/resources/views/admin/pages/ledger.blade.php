<?php 
$page_name = 'ledger Summary';
$allRecord = $deposits;
$allRecord2 = $data;
$primary_key = 'capcod';

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
			<div class="col-md-6 text-left row">
				<div class="box-header">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-6 text-right">
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
    @if(!Auth::user()->hasRole('user'))
		{!! Form::open(array('url' => 'admin/ledger/detail','method' => 'get')) !!}
		<div class="box-body p-v-md">
          <div class="row row-sm">
            <div class="hide col-xs-12 col-md-3 {{ $errors->has('stdate') ? 'has-danger' : '' }}">
            {{ Form::text('stdate', $stdate, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'stdate' , 'ui-jp' => 'datetimepicker', 'placeholder' => 'Start Date' , 'ui-options' => "{
                format: 'MM/DD/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
			}" ]) }}
            @if ($errors->has('stdate'))
                <span class="parsley-required">{{ $errors->first('stdate') }}</span>
            @endif
            </div>
           	<div class="hide col-xs-12 col-md-3 {{ $errors->has('endate') ? 'has-danger' : '' }}">
            {{ Form::text('endate', $endate, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'endate' , 'ui-jp' => 'datetimepicker', 'placeholder' => 'End Date' , 'ui-options' => "{
                format: 'MM/DD/YYYY',
                    icons: {
                      time: 'fa fa-clock-o',
                      date: 'fa fa-calendar',
                      up: 'fa fa-chevron-up',
                      down: 'fa fa-chevron-down',
                      previous: 'fa fa-chevron-left',
                      next: 'fa fa-chevron-right',
                      today: 'fa fa-screenshot',
                      clear: 'fa fa-trash',
                      close: 'fa fa-remove'
                    }
			}" ]) }}
            @if ($errors->has('endate'))
                <span class="parsley-required">{{ $errors->first('endate') }}</span>
            @endif
            </div>
            <div class="col-md-4 col-xs-12 {{ $errors->has('cmb_user') ? 'has-danger' : '' }}">
            <select class="form-control userAutoFillup" ui-jp="select2" ui-options="{theme: 'bootstrap'}" id="cmb_user" name="cmb_user">
              		<option value="">-- Select User --</option>
              </select>
              @if ($errors->has('cmb_user'))
                <span class="parsley-required">{{ $errors->first('cmb_user') }}</span>
            	@endif
            </div>

            <div class="col-xs-2 col-md-1">
            <button class="md-btn md-fab m-b-sm blue" style="margin-top: -13px;"><i class="material-icons md-24">&#xE8B6;</i></button>
            </div>
          </div>
        </div>
        {!! Form::close() !!}
        <br>
        <div class="box-divider m-a-0"></div>
        @endif
      <div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
                    <tr>
                        <th>Deposit Id</th>
                        <th>Plan Name</th>
                        <th>Date of Deposit</th>
                        <th>Period</th>
                        <th>Deposit Amount ($)</th>
                        <th>Interest on Maturity ($)</th>
                        <th>Total ($)</th>
                    </tr>
				</thead>
				<tbody>
					<?php $a = 1; ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
                    <tr>
                    <td class="text-info">{{ $singleRecord->depositno }}</td>
                    <td class="text-info"><span data-html="true" data-toggle="tooltip" title="{{ $singleRecord->plan_name }}">{{ str_limit($singleRecord->plan_name,40) }}</span>
                    <br><span class="text-success">{{ ucwords(str_replace('_', ' ', ucwords($singleRecord->status=="approved" ? "active" :$singleRecord->status ))) }}
                    @if(@$singleRecord->description != '')
                    <a href="javascript:void(0);" class="text-success" data-toggle="tooltip" title="{{ $singleRecord->description }}"> <i class="material-icons text-success">&#xE88F;</i></a>
                    @endif
                    </span>
                    </td>
                    <td class="text-info" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
                    <td class="text-info"></td>
                    <td class="text-info" nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
                    <td class="text-info">-</td>
                    <td class="text-info" nowrap="nowrap">$ {{ number_format($singleRecord->amount,2) }}</td>
                    </tr>
                        <?php  
                        $withdraws = App\Withdraw::where('depositid',$singleRecord->depositid)->where('withdraw_type','deposit')->where('created_by',$cmb_user)->where('status','approved')->get();
                        
                        $flag = 0;
                        ?>
  						@foreach($allRecord2 as $singleRecord2)
  						@if($singleRecord2['depositid'] == $singleRecord->depositid)
                        <?php
                        $textWithdraw = '';
                        if(count($withdraws) > 0)
                        {
                            foreach ($withdraws as $withdraw) 
                            {
                                if($withdraw->created_at->toDateString() >= $singleRecord2['startDate']->toDateString()  && $withdraw->created_at->toDateString() <= $singleRecord2['endDate']->toDateString())
                                {
                                    if($flag == 0)
                                    {
                                        $textWithdraw = '<b><span class="text-danger"> * Withdrawal Deposit of <u>$ '.number_format($withdraw->amount,2).'</u> on date <u>'.dispayTimeStamp($withdraw->created_at)->toFormattedDateString().'</u> </span></b>';
                                        $flag = 1;
                                        break;
                                    }
                                }
                            }
                        }

                        $delApplySt = '';
                        $delApplyEn = '';
                        if($singleRecord2['depositspecialstatus'] == 1)
                        {
                            $delApplySt = '<del>';
                            $delApplyEn = '</del>';
                        }
                        ?>
                        <del><tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td nowrap="nowrap">{!! $delApplySt !!} {{ dispayTimeStamp($singleRecord2['startDate'])->toDayDateTimeString() }}  <b>to</b>  {{ dispayTimeStamp($singleRecord2['endDate'])->toDayDateTimeString() }} {!! $delApplyEn !!} <br>{!! $textWithdraw !!}</td>
                            <td nowrap="nowrap">{!! $delApplySt !!} $ {{ number_format($singleRecord2['amount'],2) }} {!! $delApplyEn !!}</td>
                            <td nowrap="nowrap">{!! $delApplySt !!} $ {{ number_format($singleRecord2['totalInterest'],2) }} {!! $delApplyEn !!}</td>
                            <td nowrap="nowrap">{!! $delApplySt !!} $ {{ number_format($singleRecord2['amount'] + $singleRecord2['totalInterest'],2) }} {!! $delApplyEn !!}</td>
                        </tr></del>
						@endif
						@endforeach
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="7">No Records !</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
</div>
</div>
@endsection
@section('pageScript')
@if(!Auth::user()->hasRole('user'))
<script type="text/javascript"> $( document ).ready(function() { userAutoFillup({{ $cmb_user }}); }); </script>
@endif
@stop