@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <h3>{{ config('services.SITE_DETAILS.SITE_VIDEO1_TEXT') }}</h3>
                        <a href="{{ config('services.SITE_DETAILS.SITE_VIDEO1') }}" target="_blank">
                        <img src="{{ URL::asset('local/assets/images/wb/video-1.jpg') }}" alt="video" class="img-responsive">
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <h3>{{ config('services.SITE_DETAILS.SITE_VIDEO2_TEXT') }}</h3>
                        <a href="{{ config('services.SITE_DETAILS.SITE_VIDEO2') }}" target="_blank">
                            <img src="{{ URL::asset('local/assets/images/wb/video-2.jpg') }}" alt="video" class="img-responsive">
                        </a>
                       
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <h3>News</h3>
                        <ul class="news-title">
                        <marquee scrollamount="1" direction="up" loop="true" onmouseover="this.stop();" onmouseout="this.start();" style="min-height: 210px;">
                        @foreach($newss as $news)
                        <li style="border-bottom: 1px solid #999;"><a href="{{ url('news/'.$news->newsid.'') }}"> {{ $news->news_header }}</a><br><small>{{ $news->created_at->toFormattedDateString() }}</small></li>
                        @endforeach
                        </marquee>
                        </ul>
                    </div>
                </div>
            </div>
@endsection
