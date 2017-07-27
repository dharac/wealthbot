<?php 
$temparray      =  array();
$as             = '';
?>
@extends('admin.parts.layout')
@section('adminTitle', 'Referrals Information')
@section('adminBody')
<div class="padding">
    <div class="box">
        <div class="row p-a">
            <div class="col-md-6 text-left row">
                <div class="box-header">
                    <h2>Referrals Information</h2>
                </div>
            </div>
            <div class="col-md-6 text-right">
            </div>
        </div>

        <div class="box-divider m-a-0"></div>
        <div class="table-responsive" style="cursor: pointer;">
        @if(Auth::user()->hasRole('user'))
            <br>
            <div class="col-md-12">
                <h6> Referral URL : <a href="{{ url('track/'.$user->username) }}" target="_blank" class="text-info">{{ url('track/'.$user->username) }}</a></b></h6>
            </div>
        @endif
        <div class="chart" id="basic-example" draggable="true" ondragstart="drag(event)"></div>
        </div> 
    </div>
</div>

<link rel="stylesheet" href="{{ URL::asset('local/assets/vendor/tree/Treant.css') }}">
<link rel="stylesheet" href="{{ URL::asset('local/assets/vendor/tree/basic-example.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{{ URL::asset('local/assets/vendor/tree/raphael.js') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{{ URL::asset('local/assets/vendor/tree/Treant.js') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{{ URL::asset('local/assets/js/jquery-ui.js') }}"></script>
<script>
var container = $('#basic-example');
container.x = 0;
container.y = 0;
var dragging = false;
var previousmouse;

$( document ).ready(function() {
var config = {
        container: "#basic-example",
        
        connectors: {
            type: 'step'
        },
        node: {
            HTMLclass: 'nodeExample1'
        }
    },
    ceo<?php echo $user->id; ?> = {
        text: {
            name: "{!! ucfirst($user->first_name) !!} {!! ucfirst($user->last_name) !!}",
            title: "{!! $user->username !!}",
        },
        image: "{!! URL::asset('local/assets/images/admin/usericon.png') !!}"
    },
        @for($i=0;$i<count($referrals);$i++)
        <?php 
        $finalAmt = 0; 
        if(count($referrals[$i]['amtlist']) > 0)
        {
            foreach($referrals[$i]['amtlist'] as $t)
            {
                $finalAmt = $finalAmt + $t;
            }
        }

        $name       = ucfirst($referrals[$i]['first_name']).' '.ucfirst($referrals[$i]['last_name']);
        $username   = $referrals[$i]['username'];

        if(Auth::user()->hasRole('user'))
        {
            if($referrals[$i]['uplineid'] != Auth::user()->id)
            {
                $name = "";
            }
        }
        ?>

    ceo{{ $referrals[$i]['userid'] }} = {
        parent: ceo{{ $referrals[$i]['uplineid'] }},
        text:{
            name: "{!! $name !!}",
            title: "{{ $username }}",
            contact: "$ {{ number_format($finalAmt,2) }}",
        },
        stackChildren: true,
        image: "{!! URL::asset('local/assets/images/admin/usericon.png') !!}"
    },
<?php 
$df = 'ceo'.$referrals[$i]['userid'];
array_push($temparray,$df);
 ?>
@endfor
    chart_config = [
        config,
        ceo<?php echo $user->id; ?>,
        <?php 
        for($j=0;$j<count($temparray);$j++)
        {
            if($as == '')
            {
                $as = 1;
                echo $temparray[$j];
            }
            else
            {
                echo ','.$temparray[$j];
            }
        }
         ?>
    ];    
    new Treant( chart_config );
});

function drag(e){
   e.preventDefault();
}

$('#basic-example').mousedown(function(e){
    dragging = true;
    previousmouse = {x: e.pageX, y: e.pageY};
});

$('#basic-example').mouseup(function(e){
    dragging = false;
});

$('#basic-example').mousemove(function(e){
    $('.chart').css('overflow-x','visible');
    $('.Treant').css('overflow','visible');
    if(dragging){

      container.x += e.pageX - previousmouse.x;
        container.y += e.pageY - previousmouse.y;
        container.css('transform','translate('+container.x+'px,'+container.y+'px)');
         previousmouse = {x: e.pageX, y: e.pageY};
    }
});

</script>
@stop