@if (isset($errors) && $errors->any())
<div class="padding col-md-12" style="padding-bottom: 0px;">
    <div class="alert alert-danger" id="error_display">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
<div class="clearfix"></div>
@endif

@if(Session::has('message'))
<div class="col-md-12 padding" style="padding-bottom: 0px;">
    <div class="alert {!! Session::get('alert-class') !!}  alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        {!! Session::get('message') !!}
    </div>
</div>
<div class="clearfix"></div>
@endif