<?php 
$page_name = 'referral-report';
$page_name2 = 'Referrals Report';
$allRecord = $referrals;
$ReferralName = "";

if(count($allRecord) > 0)
{
    if($mode == 'view')
    {
        $a = 1;
        $activeRecord = 0;
        $activeLevelCount = [];
        $inactiveLevelCount = [];
        for($i= 1; $i <= 10;$i++)
        {
            $activeLevelCount[$i] = 0;
            $inactiveLevelCount[$i] = 0;
        }
        foreach ($allRecord as $key => $value)
        {
            if($value['status'] == 'active')
            {
                $activeRecord++;
                $activeLevelCount[$value['level']]++;
            }
            else
            {
                $inactiveLevelCount[$value['level']]++;
            }
        }
    }
}

?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name2))
@section('adminBody')
<div class="padding">
    <div class="box">   
        <div class="row p-a">
            <div class="col-md-12 row"><div class="box-header box-header-data" ><h2>{{ ucwords($page_name2) }}</h2></div></div>
        </div>
        <div class="box-divider m-a-0"></div>
        @if(!Auth::user()->hasRole('user'))
        {!! Form::open(array('url' => 'admin/referral-report/detail','method' => 'get')) !!}
        <div class="box-body p-v-md">
          <div class="row row-sm"> 
            <div class="col-xs-10 col-md-6 {{ $errors->has('cmb_user') ? 'has-danger' : '' }}">
            <select class="form-control col-md-12 col-xs-12 userAutoFillup" ui-jp="select2" ui-options="{theme: 'bootstrap'}" id="cmb_user" name="cmb_user">
                    <option value="">-- Select User --</option>
              </select>
              @if ($errors->has('cmb_user'))
                <span class="parsley-required">{{ $errors->first('cmb_user') }}</span>
                @endif
            </div>

            <div class="col-xs-2 col-md-2">
            <button class="md-btn md-fab m-b-sm blue" style="margin-top: -13px;"><i class="material-icons md-24">&#xE8B6;</i></button>
            </div>
          </div>
        </div>
        {!! Form::close() !!}
        @endif

        @if(count($allRecord) > 0)
        @if($mode == 'view')
        <div class="box-header text-info"><h3>Referrals Information</h3></div>
        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped white b-a">
                <tbody>
                    <tr>
                        <th>Referrals</th>
                        <th>{{ count($allRecord) }}</th>
                    </tr>
                    <tr>
                        <th>Active Referrals</th>
                        <th>{{ $activeRecord }}</th>
                    </tr>
                    <tr>
                        <th>Total Referral Commission</th>
                        <td>
                        <b>
                            $ {{ number_format($commision['commission_total'],2) }} <span class="text-primary"> ( Available Commission )</span>
                        </b>
                        </td>
                    </tr>
                    <tr>
                        <th>Referral Link</th>
                        <th> <a href="{{ url('track/'.$username) }}" target="_blank" class="text-info">{{ url('track/'.$username) }}</a> </th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="box-divider m-a-0"></div>
        <div class="box-header text-info"><h3>Level Information</h3></div>
        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped white b-a">
                <tbody>
                    <tr>
                        <th>Level</th>
                        @for($i= 1; $i <= 10;$i++)
                        <th>{{ App\myCustome\myCustome::addOrdinalNumberSuffix($i) }} Level</th>
                        @endfor
                        <th>Total</th>
                    </tr>
                    <tr>
                        <th>Active Referrals</th>
                        <?php $totalActive = 0; ?>
                        @for($i= 1; $i <= 10;$i++)
                        <td>@if($activeLevelCount[$i] != 0) {{ $activeLevelCount[$i] }} @else - @endif </td>
                        <?php $totalActive = $totalActive + $activeLevelCount[$i];  ?>
                        @endfor
                        <th>{{ $totalActive }}</th>
                    </tr>
                    <tr>
                        <th>Inactive Referrals</th>
                        <?php $totalInactive = 0; ?>
                        @for($i= 1; $i <= 10;$i++)
                        <td>@if($inactiveLevelCount[$i] != 0) {{ $inactiveLevelCount[$i] }} @else - @endif </td>
                        <?php $totalInactive = $totalInactive + $inactiveLevelCount[$i];  ?>
                        @endfor
                        <th>{{ $totalInactive }}</th>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="box-divider m-a-0"></div>
        <div class="box-header text-info"><h3>All Referrals Information</h3></div>
        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped white b-a" ui-jp="dataTable" ui-options="{ bSort: false, pageLength: {{ config('services.DATATABLE.PERPAGE') }} }">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Level ID</th>
                        <th>Username</th>
                        <th>Name</th>
                        <th>Referrer</th>
                        <th>Plan Name</th>
                        <th title="Current Private Loan Amount (USD)">Current Private Loan Amt ($)</th>
                        <th>%</th>
                        <th>Available Commission ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allRecord as $key => $value)
                    <?php 
                        $finalAmt = 0;
                        $plan_name = "";
                    ?>
                    @if(count($value['amtlist']) > 0)
                    @foreach($value['amtlist'] as $t)
                        <?php $finalAmt = $finalAmt + $t; ?>
                    @endforeach
                    @endif

                    @if(count($value['plannamelist']) > 0)
                    @foreach($value['plannamelist'] as $p )
                      @if($plan_name == "")
                      <?php $plan_name .=  '<span>'.$p.'</span>'; ?>
                      @else
                      <?php $plan_name .=  ',<br>'.'<span>'.$p.'</span>';; ?>
                      @endif
                    @endforeach
                    @endif

                    <tr>
                        <td>{{ $a }}</td>
                        <td nowrap="nowrap"><b>{{ App\myCustome\myCustome::addOrdinalNumberSuffix($value['level']) }} Level</b></td>
                        <td nowrap="nowrap">@if($value['status'] == "active")<a href="javascript:void({{ $value['userid'] }});" class="text-info referral-report-popup" data-id="{{ $value['userid'] }}"> {{ $value['username'] }}</a>@else {{ $value['username'] }}  @endif</td>
                        @if(Auth::user()->hasRole('user'))
                        <td>
                        @if($value['uplineid'] == $cmb_user)   
                        {{ $value['first_name'].' '.$value['last_name'] }} 
                        <a href="javascript:void(0);" data-toggle="tooltip" class="text-info" title="Cell Phone : {!! $value['phone'] !!}<br>Email : {!! $value['email'] !!}" data-html="true"><i class="material-icons">&#xE88F;</i></a>
                        @else
                             - 
                        @endif
                        </td>
                        <td>
                            @if($value['uplineid'] == $cmb_user)  
                                {{ $value['referrer'] }}
                            @else 
                            {{ $value['referrerunm'] }}  
                            @endif
                        </td>
                        @else
                        <td>{{ $value['first_name'].' '.$value['last_name'] }}<a href="javascript:void(0);" data-toggle="tooltip" class="text-info" title="Cell Phone : {!! $value['phone'] !!}<br>Email : {!! $value['email'] !!}" data-html="true"><i class="material-icons">&#xE88F;</i></a></td>
                        <td>{{ $value['referrer'] }}</td>
                        @endif
                        <td>@if($plan_name != "") <a href="javascript:void(0)" data-html="true" data-toggle="tooltip" class="text-info" title="{{ $plan_name }}"><i class="material-icons">&#xE88F;</i></a> @else - @endif</td>
                        <td nowrap="nowrap">$ {{ number_format($finalAmt,2) }}</td>
                        <td>@if($value['levelPercent'] != "") <a href="javascript:void(0)" data-html="true" data-toggle="tooltip" class="text-info" title="{{ $value['levelPercent'] }}"><i class="material-icons">&#xE88F;</i></a> @else - @endif</td>
                        <td>$ {{ number_format($value['totalCommision'],2) }}</td>
                    </tr>
                    <?php $a++; ?>
                    @endforeach
                </tbody>
            </table>
            @endif
            @else
            @if($mode == 'view')
                <div class="col-md-12 row clearfix text-center"><br><h3>No Referral Found.</h3><br></div>
            @endif
            @endif
        </div>
</div>
</div>
@endsection
@section('pageScript')
@if(!Auth::user()->hasRole('user'))
<script type="text/javascript"> $( document ).ready(function() { userAutoFillup({{ $cmb_user }}); }); </script>
@endif
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/referral-report.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop