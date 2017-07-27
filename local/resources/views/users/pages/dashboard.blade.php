@extends('admin.parts.layout')
@section('adminTitle', 'Dashboard')
@section('adminBody')

<div class="clearfix"></div>
<div class="col-md-12 padding">
	@if(Auth::user()->hasRole('user') &&  !App\myCustome\myCustome::bitCoinAddressValidate(Auth::user()->bitcoin_id))
		<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong><i class="material-icons">&#xE003;</i> Alert!</strong> Indicates an empty or non-real  <b>Bitcoin Wallet Address</b>.  <a href="{{ url('admin/user/profile/update') }}" class="text-info">Update Profile</a></div>
	@endif
	@if(!is_numeric(Auth::user()->phone))
		<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><strong><i class="material-icons">&#xE325;</i> Important</strong> : Make sure that you enter your correct cell phone number in your <a href="{{ url('admin/user/profile/update') }}" title="Update Cell Phone" class="text-info">profile section</a>, otherwise you might not receive important SMS text messages from the system.</div>
	@endif
</div>
	
	<div class="padding">
    <div class="margin">
        <h5 class="m-b-0 _300">Hi {{ ucfirst(Auth::user()->first_name) }}, @if($logdetails > 1) Welcome back @else Welcome to {{ config('services.SITE_DETAILS.SITE_NAME') }} @endif <small style="font-size: 12px;">{{ \Carbon\Carbon::now()->toDayDateTimeString() }} {{ Config::get('app.timezone') }} | <b>{{ dispayTimeStamp(\Carbon\Carbon::now())->toDayDateTimeString() }} {{ Config::get('app.timezone_display2') }}</b></small></h5>
        <br>
        <h6> Referral URL :  <a href="{{ url('track/'.Auth::user()->username) }}" target="_blank" class="text-info">{{ url('track/'.Auth::user()->username) }}</a></b> </h6><br>

        @if(count($deposits['result1']) > 0)
        <div class="row">
        <div class="col-sm-12 col-md-12">
      <div class="box">
        <div class="box-header">
          <span class="label danger pull-right">{{ count($deposits['result1']) }}</span>
          <h3>Current Deposits</h3>
        </div>
        <div class="table-responsive">
        <table class="table">
        <thead>
        	<tr>
        		<th>Plan Name</th>
        		<th>Period</th>
        		<th>Deposit Amount($)</th>
        		<th>Interest on Maturity ($)</th>
        		<th>Total ($)</th>
        		<th>View Daily Interest Earned</th>
        	</tr>
        </thead>
        <tbody>
        @foreach($deposits['result1'] as $deposit)
			<tr>                    
				<?php 
				if(array_key_exists('data'.$deposit->depositid, $deposits['result2']))
				{
					$singleRecord2 = $deposits['result2']['data'.$deposit->depositid];	?>
				<td nowrap="nowrap" title="{{ $deposit->plan_name }}">{{ str_limit($deposit->plan_name,40) }}</td>
				<td nowrap="nowrap">{{ dispayTimeStamp($singleRecord2['startDate'])->toDayDateTimeString() }}  <b>to</b>  {{ dispayTimeStamp($singleRecord2['endDate'])->toDayDateTimeString() }}</td>
				<td nowrap="nowrap">$ {{ number_format($singleRecord2['amount'],2) }}</td>
				<td nowrap="nowrap">$ {{ number_format($singleRecord2['totalInterest'],2) }}</td>
				<td nowrap="nowrap">$ {{ number_format($singleRecord2['amount'] + $singleRecord2['totalInterest'],2) }}</td>

				<?php }
				else
				{ ?>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
					<?php }
				?>
				<td class="text-center"><a href="javascript:void({{ $deposit->depositid }});" data-id="{{ $deposit->depositid }}"  class="chart-report-popup" title="View Chart"><i class="material-icons">&#xE8DE;</i></a></td>
			</tr>
			@endforeach
          </tbody>
        </table>
        </div>
      </div>
    </div>
		</div>
		@endif


        <div class="row">
	        <div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header"><h3>Latest Interest Payments</h3></div>
					<div class="box-body">
					  	<div class="streamline b-l m-l">
					  	@if(count($interestPayments) > 0)
							@foreach($interestPayments as $interestPayment)
					        <div class="sl-item b-accent">
					          <div class="sl-content">
					            <div class="sl-date text-muted" title="{{ dispayTimeStamp($interestPayment->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($interestPayment->created_at)->diffForHumans() }}</div>
					            <div>You have earned <a href="{{ url('user/interest-payment/view/'.$interestPayment->int_proid.'') }}" class="text-info"> $ {{ number_format($interestPayment->pro_amount,2) }}</a> interest </div>
					          </div>
					        </div>
		            		@endforeach
		            		@else
							<div class="sl-item b-info">
								<div class="sl-content">
									<div>No Records! </div>
								</div>
							</div>
		            		@endif
					    </div>
					</div>
				  	<div class="box-footer"><a href="{{ url('user/interest-payment') }}" class="btn btn-sm white text-u-c rounded">More</a></div>
			  	</div>
			</div>
			<div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>Latest Commissions Earned</h3>
					</div>
					<div class="box-body">
						<div class="streamline b-l m-l">
							@if(count($levelCommisions) > 0)
								@foreach($levelCommisions as $levelCommision)
								<div class="sl-item b-primary">
									<div class="sl-content">
										<div class="sl-date text-muted" title="{{ dispayTimeStamp($levelCommision->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($levelCommision->created_at)->diffForHumans() }}</div>
										<div>You have earned <a href="{{ url('user/level-commision/view/'.$levelCommision->comid.'') }}" class="text-info"> $ {{ number_format($levelCommision->commission,2) }}</a> commission</div>
									</div>
								</div>
								@endforeach
							@else
								<div class="sl-item b-info">
									<div class="sl-content">
										<div>No Records! </div>
									</div>
								</div>
							@endif
						</div>
					</div>
				  	<div class="box-footer">
				  		<a href="{{ url('user/level-commision') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>
		</div>
</div>
</div>


<div id="chart_report_modal" class="modal" data-backdrop="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Interest Earn Report</h5></div>
            <div class="modal-body text-center p-lg">
                <div id="chartLine" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn danger p-x-md" data-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/user-dashboard.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop