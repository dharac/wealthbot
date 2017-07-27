<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
<meta charset="utf-8" />
<title>@yield('adminTitle', 'Page') | {{ config('services.SITE_DETAILS.SITE_NAME') }}</title>
<meta name="keywords" content="Wealthbot.ONLINE" />
<meta name="description" content="Get more results in ONE MONTH of Amazon-backed Results, than in Decades of Old Economy!" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="_token" content="{{ csrf_token() }}">
<meta name="_url" content="{{ URL('') }}"/>
<meta name="_assets" content="{{ URL::asset('local/assets') }}"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Webmechanic">
<meta name="mobile-web-app-capable" content="yes">
<link rel="apple-touch-icon" href="{{ URL::asset('local/assets/images/favicon.ico') }}">
<link rel="shortcut icon" sizes="196x196" href="{{ URL::asset('local/assets/images/favicon.ico') }}">
@include('admin.parts.styles')
</head>
<body>
    <div class="app" id="app">
        @include('admin.parts.aside')
        @include('admin.parts.topnav')
        
        <div ui-view="" class="app-body" id="view1">
            @include('admin.parts.errors')
            @yield('adminBody')
            <div class="clearfix"></div>
             @include('admin.parts.footer')
        </div>
    </div>
    </div>
    </div>
    @include('admin.parts.scripts')
    @yield('pageScript')
</body>
</html>
