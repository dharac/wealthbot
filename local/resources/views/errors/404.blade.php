<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Something went wrong | {{ config('services.SITE_DETAILS.SITE_NAME') }}</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="apple-touch-icon" href="{{ URL::asset('images/favicon.ico') }}">
<link rel="shortcut icon" sizes="196x196" href="{{ URL::asset('local/assets/images/favicon.ico') }}">
<link rel="stylesheet" href="{{ URL::asset('local/assets/css/admin/material-design-icons/material-design-icons.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('local/assets/vendor/bootstrap/css/bootstrap.min.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('local/assets/css/admin/styles/app.min.css') }}">
</head>
    <body>
        <div class="app" id="app">
            <div class="app-body light-blue-500 bg-auto w-full">
                <div class="text-center pos-rlt p-y-md">
                    <!-- <h1 class="text-center pos-rlt p-y-md"><i class="material-icons" style="font-size: 110px;">&#xE420;</i></h1> -->
                    <h2 class="h1 m-y-lg text-white">Oops!. Something went wrong.</h2>
                    <p class="h5 m-y-lg text-u-c font-bold text-white">Sorry for the inconvenience. Please try again later.</p>
                    <a href="{!! URL('/dashboard') !!}" class="md-btn warn md-raised p-x-md"><span class="text-white">Go to the home page</span></a>
                </div>
            </div>
        </div>
    </body>
</html>