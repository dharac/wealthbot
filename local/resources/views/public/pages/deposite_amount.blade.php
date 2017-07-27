@extends('layouts.app')
@section('title', 'Coinpayment')
@section('content')
<div class="mh-450">
		<div class="col-md-6 col-md-offset-3 text-center">
			<h2>Loading Payment Gateway...</h2>
			<h4>CoinPayments.net can take up to 2 minutes or more !</h4>
		    <img src="{{ URL::asset('local/assets/images/ring-alt.gif') }}">
		    <h4>Please be patient. this process might take some time,</h4>
		    <h4>Please do not hit refresh or browser back button or close this window</h4>
		</div>

		<div class="container hidden">
		<div class="row">
			<h1>Coinpayment</h1>
					<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th>Make your deposit with this BTC address :</th>
								<td>{{ $credential->merchant_id }}</td>
							</tr>
							<tr>
								<th>Amount (USD)</th>
								<td>${{ $amount }}</td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
						{!! Form::open(array('url' => 'https://www.coinpayments.net/index.php' , 'id' => 'coinPayment')) !!}
							<input type="hidden" value="_pay" name="cmd">
							<input type="hidden" value="1" name="reset">
							<input type="hidden" name="merchant" value="{{ $credential->merchant_id }}">
							<input type="hidden" name="currency" value="USD">

							<input type="hidden" name="first_name" value="{{ $user->first_name }}">
							<input type="hidden" name="last_name" value="{{ $user->last_name }}">
							<input type="hidden" name="email" value="{{ $user->email }}">
							<input type="hidden" name="country" value="{{ $user->country }}">

							<input type="hidden" name="amountf" value="{{ $amount ? $amount : '0' }}">
							<input type="hidden" name="item_name" value="{{ $plan->plan_name ? $plan->plan_name : 'Test' }}">
							@if (Auth::guest())
							<input type="hidden" name="custom" value="">
							@else
							<input type="hidden" name="custom" value="{{ Auth::user()->id ? Auth::user()->id : '0' }}">
							@endif
							<input type="hidden" name="item_number" value="{{ $plan->planid ? $plan->planid : 'Test' }}">
							<input type="hidden" name="want_shipping" value="0">
							<input type="hidden" name="success_url" value="{{ url('payment/success') }}">
			    			<input type="hidden" name="cancel_url" value="{{ url('payment/error') }}">
							<button type="submit" class="btn btn-primary">Deposit</button>
						{!! Form::close() !!}
					</div>
				</div>			
		</div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript">
jQuery(document).ready(function($) {
	$("#coinPayment").submit();
});
</script>
@stop