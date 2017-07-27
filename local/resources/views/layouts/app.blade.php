<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">
        <meta name="_url" content="{{ URL('') }}"/>
        <link rel="apple-touch-icon" href="{{ URL::asset('local/assets/images/favicon.ico') }}">
        <link rel="shortcut icon" sizes="196x196" href="{{ URL::asset('local/assets/images/favicon.ico') }}">
        <title>@yield('title', 'Page') | {{ config('services.SITE_DETAILS.SITE_NAME') }}</title>
        <meta name="keywords" content="Wealthbot.ONLINE" />
        <meta name="description" content="Get more results in ONE MONTH of Amazon-backed Results, than in Decades of Old Economy!" />
        @include('public.parts.styles')
    </head>
    <body>
    <header>
        <div class="container">
            <nav role="navigation" class="navbar navbar-default" style="border:0px;">
                <div class="navbar-header">
                    <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ url('home') }}" class="navbar-brand"><img src="{{ URL::asset('local/assets/images/logo.jpg') }}" width="100%"></a>
                </div>
                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        @if (Auth::guest())
                            <li><a href="{{ url('home') }}">Home</a></li>
                            <li class="active"><a href="{{ url('login') }}">Login</a></li>
                            <li><a href="{{ url('register') }}">Register</a></li>
                        @else
                        <li><a href="{{ url('home') }}">Home</a></li>
                        <li><a href="{{ url('dashboard') }}">Dashboard</a></li>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">{{ ucfirst(Auth::user()->first_name) }}<b class="caret"></b></a>
                            <ul role="menu" class="dropdown-menu">
                                <li><a href="{{ url('admin/user/profile') }}">Profile</a></li>
                                <li><a href="{{ url('admin/user/password') }}">Change Password</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('/logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                            </ul>
                        </li>
                        @endif
                        <li><a href="javascript:void(0)"><div id="google_translate_element"></div></a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

        @if(Request::path() == 'home')
        <section class="main-banner">
            <div id="slider-main" class="carousel slide" data-ride="carousel" data-interval="false">              
              <!-- Wrapper for slides -->
              <div class="carousel-inner" role="listbox">
                <div class="item">
                  <img src="{{ URL::asset('local/assets/images/wb/2.jpg') }}" alt="slide-2">                  
                </div>
                <div class="item">
                    <img src="{{ URL::asset('local/assets/images/wb/3.jpg') }}" alt="slide-2">
                </div>
                <div class="item">
                  <img src="{{ URL::asset('local/assets/images/wb/4.jpg') }}" alt="slide-4">
                </div>
                <div class="item active">
                    <img src="{{ URL::asset('local/assets/images/wb/bitcoin-banner.jpg') }}" alt="slide-5">
                </div>
              </div>
              <!-- Controls -->
              <a class="left carousel-control" href="#slider-main" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#slider-main" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>   
        </section>
        @endif

        <section id="video-section">
        @if(Session::has('message'))
        <div class="col-md-12 padding" style="padding-bottom: 0px;margin-top: 10px;">
            <div class="alert {!! Session::get('alert-class') !!}  alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                {!! Session::get('message') !!}
            </div>
        </div>
        <div class="clearfix"></div>
        @endif
        
        @yield('content')
        </section>

        <footer class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="wrapper">
                            <p>&copy; {{ date('Y') }} {{ config('app.name') }} </p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="wrapper">
                            <a href="{{ config('services.SITE_DETAILS.SITE_DEPOSIT_PDF') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_DEPOSIT_TEXT') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_VIDEO1') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_VIDEO1_TEXT') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_VIDEO2') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_VIDEO2_TEXT') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_AGGREMENT') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_AGGREMENT_TEXT') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK_PRESENTATION') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT_PRESENTATION') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK_COMPLIANCE') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT_COMPLIANCE') }}</a><span> | </span>
                            <a href="{{ config('services.SITE_DETAILS.SITE_FOOTER_LINK_FAQ') }}" target="_blank">{{ config('services.SITE_DETAILS.SITE_FOOTER_TEXT_FAQ') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        @include('public.parts.scripts')
        @yield('pageScript')
    </body>
</html>