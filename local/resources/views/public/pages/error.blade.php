@extends('layouts.app')
@section('title', 'Payment Error')
@section('content')
<div class="mh-450">
    <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">Payment Error.</div>
            <div class="box">
                <div class="box-divider m-a-0"></div>
                <div class="panel-body">
                    <center>
                        <h1 class="text-danger">Payment Error. Try Again...</h1>
                        @if(!Auth::guest())
                            <a href="{{ URL('dashboard') }}">Go to Dashboard</a>
                        @endif
                   </center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection