@extends('layouts.app')
@section('title', $news->news_header.' | News')
@section('content')
<br>
<div class="col-md-8 col-md-offset-2">
<div class="panel panel-default">
    <div class="panel-heading"><h2>{{ $news->news_header }}</h2></div>
        <div class="box">
            <div class="box-divider m-a-0"></div>
            <div class="panel-body">
                <p class="text-right text-primary">{!! $news->created_at->toDayDateTimeString() !!}</p>
                {!! $news->news_description !!}
            </div>
        </div>
    </div>

    <div class="col-md-12">
    <div class="row">
    <h3>News</h3>
    <?php $a = 1; ?>
    @foreach($newss as $news)
    <div class="col-md-4 col-xs-12">
    <div class="card-block">
    <h4 class="card-title">{{ str_limit($news->news_header,20) }}</h4>
    <p class="card-text">{{ $news->excerpt }}</p>
    <a href="{{ url('news/'.$news->newsid.'') }}" class="btn btn-primary">Go to News</a>
    </div>
    </div>
    <?php 
    $a++;
    if($a == 4){ $a = 1; echo '<div style="clear:both"><br></div>'; }
    ?>
    @endforeach
    </div>
    </div>
</div>
<div style="clear: both;"></div>
@endsection