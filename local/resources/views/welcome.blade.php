@extends('layouts.app')
@section('title', 'Welcome')
@section('content')
<div class="container">
    <div class="row">

    <style>

            .full-height {
                height: 60vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

        <div class="flex-center position-ref full-height">

        <div class="content">
            <div class="title m-b-md"> Welcome to  {{ config('services.SITE_DETAILS.SITE_NAME') }}</div>
        </div>

        </div>

    </div>
</div>
@endsection