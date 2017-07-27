<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/jquery.min.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/bootstrap.min.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/custome.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript" data-cfasync="false" src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit1&v={{ config('services.SCRIPT.VERSION') }}"></script>

<script>
	$('.carousel').carousel({
	   interval: false
	});
</script>