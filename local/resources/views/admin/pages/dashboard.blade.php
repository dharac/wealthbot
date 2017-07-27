@extends('admin.parts.layout')
@section('adminTitle', 'Dashboard')
@section('adminBody')
<?php  $clsData1  = array('green-700','indigo','pink','purple','blue','cyan','teal','green','lime','success','deep-orange-500','orange-500','amber-500','yellow-500','brown-800'); ?>
<div class="padding">
    <div class="margin">
        <h5 class="m-b-0 _300">Hi {{ ucfirst(Auth::user()->first_name) }}, @if($logdetails > 1) Welcome back @else Welcome to {{ config('services.SITE_DETAILS.SITE_NAME') }} @endif <small style="font-size: 12px;">{{ \Carbon\Carbon::now()->toDayDateTimeString() }} {{ Config::get('app.timezone') }} | <b>{{ dispayTimeStamp(\Carbon\Carbon::now())->toDayDateTimeString() }} {{ Config::get('app.timezone_display2') }}</b></small></h5>
    </div>
<div class="dashboard-icon">
<div class="row">    
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="box p-a green-500">
            <div class="pull-right m-l">
                <div class="box-tool">
                    <ul class="nav" style="margin-top:10px;">
                        <li class="nav-item inline dropdown">
                            <a class="nav-link" data-toggle="dropdown" aria-expanded="true"><i class="material-icons md-18">&#xE5D4;</i></a>
                            <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-user pull-right">
                            <a class="dropdown-item dropdown-user-item-24hours"  data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 24 Hours</a>
                            <a class="dropdown-item dropdown-user-item-48hours"  data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 48 Hours</a>
                            <a class="dropdown-item dropdown-user-item-72hours"  data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 72 Hours</a>
                            <a class="dropdown-item dropdown-user-item-7"  data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 7 Days</a>
                            <a class="dropdown-item dropdown-user-item-14" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 14 Days</a>
                            <a class="dropdown-item dropdown-user-item-21" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 21 Days</a>
                            <a class="dropdown-item dropdown-user-item-30" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 30 Days</a>
                            <a class="dropdown-item dropdown-user-item-60" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 60 Days</a>
                            <a class="dropdown-item dropdown-user-item-90" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 90 Days</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear">
                <h4 class="m-a-0 text-md"><a href="{{ URL('admin/user') }}"><span class="total_user_count">0</span> <span class="text-sm">Registered Members</span></a></h4>
                <small class="text-muted"><span class="user_count"></span> <span class="last"> Registered in Last</span> <span class="user-day">24</span> <span class="user-time">Hours</span></small>
            </div>
        </div>
    </div>

<div class="col-sm-6 col-md-4 col-lg-3">
<div class="box-color p-a light-blue-500">
    <div class="pull-right m-l">
        <div class="box-tool">
            <ul class="nav" style="margin-top:10px;">
                <li class="nav-item inline dropdown">
                    <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                    <i class="material-icons md-18">&#xE5D4;</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-payout pull-right">
                        <a class="dropdown-item dropdown-item-7"  data-id="0" data-value="0" href="javascript:void(0);">Next 7 Days</a>
                        <a class="dropdown-item dropdown-item-14" data-id="0" data-value="0" href="javascript:void(0);">Next 14 Days</a>
                        <a class="dropdown-item dropdown-item-21" data-id="0" data-value="0" href="javascript:void(0);">Next 21 Days</a>
                        <a class="dropdown-item dropdown-item-30" data-id="0" data-value="0" href="javascript:void(0);">Next 30 Days</a>
                </li>
            </ul>
            </div>
        </div>
        <div class="clear">
            <h4 class="m-a-0 text-md"><a href="{{ URL('admin/payout-report') }}"><span class="payout-value">0</span> <span class="text-sm">Total Interest Payout</span></a></h4>
            <small class="text-muted">Next <span class="payout-day">7</span> Days</small>
        </div>
    </div>
</div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box p-a deep-orange-500">
        <div class="pull-left m-r">
            <span class="w-40 deep-orange-400 text-center rounded"><i class="fa fa-btc" aria-hidden="true"></i></span>
        </div>
        <div class="clear"><a href="{{ URL('admin/withdraw') }}"><h4 class="m-a-0 text-md"><span class="m-a-0 text-md pending_withdrawl">0</span> </h4></a><small class="text-muted">Total Requested Payouts</small>
        </div>
    </div>
</div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a primary">
        <div class="pull-right m-l">
            <span class="w-40 dker text-center rounded">
          <i class="material-icons">swap_horiz</i>
        </span>
        </div>
        <div class="clear">
            <h4 class="m-a-0 text-md"><a href="{{ URL('admin/withdraw') }}"><span class="total_withdrawals_pending">0</span> <span class="text-sm">Withdrawals Pending</span></a></h4>
            <small class="text-muted"><span class="total_withdrawals_approved">0</span> approved.</small>
        </div>
    </div>
</div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a pink-500">
        <div class="pull-right m-l">
            <div class="box-tool">
                <ul class="nav" style="margin-top:10px;">
                    <li class="nav-item inline dropdown">
                        <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                            <i class="material-icons md-18">&#xE5D4;</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-loan pull-right">
                            <a class="dropdown-item dropdown-loan-item-24hours" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 24 Hours</a>
                            <a class="dropdown-item dropdown-loan-item-48hours" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 48 Hours</a>
                            <a class="dropdown-item dropdown-loan-item-72hours" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 72 Hours</a>
                            <a class="dropdown-item dropdown-loan-item-7" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 7 Days</a>
                            <a class="dropdown-item dropdown-loan-item-14" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 14 Days</a>
                            <a class="dropdown-item dropdown-loan-item-21" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 21 Days</a>
                            <a class="dropdown-item dropdown-loan-item-30" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 30 Days</a>
                            <a class="dropdown-item dropdown-loan-item-60" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 60 Days</a>
                            <a class="dropdown-item dropdown-loan-item-90" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 90 Days</a>
                            <a class="dropdown-item dropdown-loan-item-180" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 6 Months</a>
                            <a class="dropdown-item dropdown-loan-item-270" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 9 Months</a>
                            <a class="dropdown-item dropdown-loan-item-360" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 12 Months</a>
                            <a class="dropdown-item dropdown-loan-item-720" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 24 Months</a>
                    </li>
                </ul>
                </div>
            </div>
            <div class="clear">
                <a href="javascript:void(0);" class="view-user-by-plan">
                    <h4 class="m-a-0 text-md"><span class="loan-value">0</span> <span class="text-sm">New Loans</span></h4>
                </a>
                <small class="text-muted">Last <span class="loan-day">24</span > <span class="loan-time">Hours</span></small>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a purple-500">
        <div class="pull-right m-l">
            <div class="box-tool">
                <ul class="nav" style="margin-top:10px;">
                    <li class="nav-item inline dropdown">
                        <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                            <i class="material-icons md-18">&#xE5D4;</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-aLender pull-right">
                            <a class="dropdown-item dropdown-aLender-item-24hours" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 24 Hours</a>
                            <a class="dropdown-item dropdown-aLender-item-48hours" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 48 Hours</a>
                            <a class="dropdown-item dropdown-aLender-item-72hours" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 72 Hours</a>
                            <a class="dropdown-item dropdown-aLender-item-7" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 7 Days</a>
                            <a class="dropdown-item dropdown-aLender-item-14" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 14 Days</a>
                            <a class="dropdown-item dropdown-aLender-item-21" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 21 Days</a>
                            <a class="dropdown-item dropdown-aLender-item-30" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 30 Days</a>
                            <a class="dropdown-item dropdown-aLender-item-60" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 60 Days</a>
                            <a class="dropdown-item dropdown-aLender-item-90" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 90 Days</a>
                            <a class="dropdown-item dropdown-aLender-item-6" data-id="0" data-value="0" href="javascript:void(0);" data-time="0">Last 6 Months</a>
                    </li>
                </ul>
                </div>
            </div>
            <div class="clear">
                <a href="{{ URL('admin/loan') }}">
                    <h4 class="m-a-0 text-md"><span class="aLender-value">0</span> <span class="text-sm">Loaned per Lender Avg.</span></h4>
                </a>
                <small class="text-muted">Last <span class="aLender-day">24</span > <span class="aLender-time">Hours</span></small>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="box p-a grey-700">
            <div class="pull-left m-r">
                <span class="w-40 grey-600 text-center rounded">
                  <i class="fa fa-btc" aria-hidden="true"></i>
                </span>
            </div>
            <div class="clear">
                <h4 class="m-a-0 text-md"><a href="{{ URL('admin/loan') }}"><span class="active_total_lenders_percent">0</span> <span class="text-sm"> <span class="active_total_lenders">0</span> Of Total </span></a></h4>
                <small class="text-muted">Lenders With Active Loans</small>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="box p-a brown-500">
            <div class="pull-right m-l">
                <div class="box-tool">
                    <ul class="nav" style="margin-top:10px;">
                        <li class="nav-item inline dropdown">
                            <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                                <i class="material-icons md-18">&#xE5D4;</i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-deposite pull-right">
                                <a class="dropdown-item dropdown-item-deposite1" data-id="0" data-value="0" data-deposite="0" href="javascript:void(0);">More than 1</a>
                                <a class="dropdown-item dropdown-item-deposite2" data-id="0" data-value="0" data-deposite="0" href="javascript:void(0);">More than 2</a>
                                <a class="dropdown-item dropdown-item-deposite3" data-id="0" data-value="0" data-deposite="0" href="javascript:void(0);">More than 3</a>
                        </li>
                    </ul>
                    </div>
                </div>
                <div class="clear">
                    <h4 class="m-a-0 text-md"><a href="{{ URL('admin/loan') }}"><span class="deposit_percent">0</span><span class="deposit text-sm">0</span><span class="text-sm">Active Lenders</span></a></h4>
                    <small class="text-muted">with more than <span class="deposite_count">1</span> deposit </small>
                </div>
            </div>
    </div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a lime-900">
    <div class="pull-right m-l">
        <div class="box-tool">
            <ul class="nav" style="margin-top:10px;">
                <li class="nav-item inline dropdown">
                    <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                        <i class="material-icons md-18">&#xE5D4;</i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-referal pull-right">
                        <a class="dropdown-item dropdown-item-referal1" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">1 Duplicator</a>
                        <a class="dropdown-item dropdown-item-referal2" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">2 Duplicators</a>
                        <a class="dropdown-item dropdown-item-referal3" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">3 Duplicators</a>
                        <a class="dropdown-item dropdown-item-referal10" data-id="0" data-value="0" data-referal="0" href="javascript:void(0);" data-userID="0">10+ Duplicators</a>
                </li>
            </ul>
            </div>
        </div>
        <div class="clear">
            <h4 class="m-a-0 text-md"><span class="referal-percentage">0</span>  <span class="text-sm"> (<span class="referal-value" data-toggle="modal" data-target="#UserModal" style="cursor: pointer;" title="View Username">0</span>) Active Lenders with <span class="count_referral">1</span> Duplicator Second Levels Down</span></h4>
        </div>
    </div>
</div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a blue-grey-400">
        <div class="pull-right m-l">
            <div class="box-tool">
                <ul class="nav" style="margin-top:10px;">
                    <li class="nav-item inline dropdown">
                        <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                            <i class="material-icons md-18">&#xE5D4;</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-referal3 pull-right">
                            <a class="dropdown-item dropdown-item-referal31" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">1 Duplicator</a>
                            <a class="dropdown-item dropdown-item-referal32" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">2 Duplicators</a>
                            <a class="dropdown-item dropdown-item-referal33" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">3 Duplicators</a>
                            <a class="dropdown-item dropdown-item-referal310" data-id="0" data-value="0" data-referal="0" href="javascript:void(0);" data-username="0" data-userID="0">10+ Duplicators</a>
                    </li>
                </ul>
                </div>
            </div>
            <div class="clear">
                <h4 class="m-a-0 text-md"><span class="referal3-percentage">0</span>  <span class="text-sm"> (<span class="referal3-value" data-toggle="modal" data-target="#UserModal3" style="cursor: pointer;" title="View Username">0</span>) Active Lenders with <span class="count3_referral">1</span> Duplicator Third Levels Down</span></h4>
            </div>
        </div>
    </div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a pink-A200">
        <div class="pull-right m-l">
            <div class="box-tool">
                <ul class="nav" style="margin-top:10px;">
                    <li class="nav-item inline dropdown">
                        <a class="nav-link" data-toggle="dropdown" aria-expanded="true">
                            <i class="material-icons md-18">&#xE5D4;</i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-scale dropdown-menu-scale-referal5 pull-right">
                            <a class="dropdown-item dropdown-item-referal51" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">1 Duplicator</a>
                            <a class="dropdown-item dropdown-item-referal52" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">2 Duplicators</a>
                            <a class="dropdown-item dropdown-item-referal53" data-id="0" data-value="0" href="javascript:void(0);" data-referal="0" data-username="0" data-userID="0">3 Duplicators</a>
                            <a class="dropdown-item dropdown-item-referal510" data-id="0" data-value="0" data-referal="0" href="javascript:void(0);" data-username="0" data-userID="0">10+ Duplicators</a>
                    </li>
                </ul>
                </div>
            </div>
            <div class="clear">
                <h4 class="m-a-0 text-md"><span class="referal5-percentage">0</span>  <span class="text-sm"> (<span class="referal5-value" data-toggle="modal" data-target="#UserModal5" style="cursor: pointer;" title="View Username">0</span>) Active Lenders with <span class="count5_referral">1</span> Duplicator Fifth Levels Down</span></h4>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="box p-a">
            <div class="pull-left m-r">
                <span class="w-40 warn text-center rounded">
            <i class="fa fa-btc" aria-hidden="true"></i>
          </span>
            </div>
            <div class="clear">
                <h4 class="m-a-0 text-md"><a href="{{ URL('admin/loan') }}"><span class="remain_total_payment_percent">0</span> <span class="text-sm"> <span class="remain_total_payment">0</span> Of Payments didn't complete</span></a></h4>
            </div>
        </div>
    </div>


    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="box-color p-a indigo-900">
            <div class="pull-left m-r"><span class="w-40 dker text-center rounded"><i class="material-icons">place</i></span></div>
            <div class="clear">
                <h4 class="m-a-0 text-md" data-toggle="modal" data-target="#country_report_modal" style="cursor: pointer;"><span>{{ count($countryWiseLenders) }}</span> <span class="text-sm"> Countries <span style="cursor: pointer;opacity: 0.6"> (View map)</span></span></h4>
                <small class="text-muted">Newest : <span class="latest_country"></span></small>
            </div>
        </div>
    </div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a indigo-500">
        <div class="pull-left m-r"><span class="w-40 dker text-center rounded"><i class="material-icons">comment</i></span></div>
        <div class="clear">
            <h4 class="m-a-0 text-md"><a href="{{ URL('admin/ticket') }}"><span class="total_tickets_new">0</span> <span class="text-sm">New Tickets</span></a></h4>
            <small class="text-muted"><a href="{{ URL('admin/ticket') }}"><span class="total_tickets_reopen">0</span> <span class="text-sm">Reopen</span></a></small>
        </div>
    </div>
</div>

<div class="col-sm-6 col-md-4 col-lg-3">
    <div class="box-color p-a teal-900">
        <div class="clear">
            <h4 class="m-a-0 text-md"><a href="{{ URL('admin/ticket') }}"><span class="total_tickets_pending">0</span> <span class="text-sm">Pending Tickets | </span></a><a href="{{ URL('admin/ticket') }}"><span class="total_tickets_inprogress">0</span> <span class="text-sm">Inprogress</span></a></h4>
            <small class="text-muted"><a href="{{ URL('admin/ticket') }}"><span class="total_tickets_awaiting_your_reply">0</span> <span class="text-sm">Awaiting your reply</span></a></small>
        </div>
    </div>
</div>

</div>

      <!-- User name Modal Start-->
      <div id="UserModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Username Listing with <span class="duplicators_count"></span> Duplicator</h4>
            </div>
            <div class="modal-body" style="background: #f0f0f0;">
              <span class="user_listing"></span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div id="UserModal3" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Username Listing with <span class="duplicators_count3"></span> Duplicator</h4>
            </div>
            <div class="modal-body" style="background: #f0f0f0;">
                <span class="user_listing3"></span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <div id="UserModal5" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Username Listing with <span class="duplicators_count5"></span> Duplicator</h4>
            </div>
            <div class="modal-body" style="background: #f0f0f0;">
                <span class="user_listing5"></span>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- User name Modal End-->

      <!-- popup modal -->
      <div id="country_report_modal" class="modal fade " role="dialog">
        <div class="modal-dialog modal-lg">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Country Wise Count of Registered Lenders </h4>
            </div>
            <div class="modal-body">
            <?php $data = '';?>

            @if(count($countryWiseLenders) > 0)
            <?php //echo '<pre>'; print_r($countryWiseLenders->toArray()); exit; ?>
              @foreach($countryWiseLenders as $country)
                 <?php 
                 $country_nm = ($country->counm == "United States") ? "United States of America" : $country->counm;
                 $data .="{name : '".str_replace("'","", $country_nm)."', value : ".$country->user_count. " },"; ?>      
              @endforeach
            @endif           
           
          <div class="box-body">
            <div ui-jp="chart" ui-options=" {
              title : {                 
                  x:'center',
                  y:'top'
              },
              tooltip : {
                  trigger: 'item',                  
              },
              dataRange: {
                  min: 0,
                  max: 1200,
                  text:['High','Low'],
                  realtime: false,
                  calculable : true,
                  color: ['orangered','yellow','lightskyblue']
              },
              series : [
                  {
                      name: 'Registered Lenders',
                      type: 'map',
                      mapType: 'world',
                      roam: true,
                      mapLocation: {
                          y : 60
                      },
                      itemStyle:{
                          emphasis:{label:{show:true}}
                      },
                      data:[                                               
                        <?php echo $data; ?>
                      ]
                  }
              ]
            }" style="height:450px;" >
            </div>
        </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default danger" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
       <div class="commission-desc"></div>
        <div class="clearfix"></div>
      </div>

      <div class="row">


      <div class="col-md-12 col-xl-4">
      <div class="box">
        <div class="box-header">
          <h3>Latest Registered Lender</h3>
        </div>
        <ul class="list-group no-border">
            @if(count($users) > 0)
                @foreach($users as $user)
                <?php  $random_keys = array_rand($clsData1,1); $clsLife = $clsData1[$random_keys]; ?>
                    <li class="list-group-item">
                        <a href="<?php echo url('admin/user/view/'.$user->id.''); ?>" class="pull-left w-40 m-r">
                            <span class="w-40 avatar {{ $clsLife }}">
                            <span>{{ substr($user->first_name, 0,1) }}</span>
                            <i class="on b-white bottom"></i>
                            </span>
                        </a>
                        <div class="clear">
                        <a href="<?php echo url('admin/user/view/'.$user->id.''); ?>" class="_500 block">{{ ucfirst($user->first_name).' '.ucfirst($user->last_name) }} | {{ $user->username }}</a>
                        <span class="text-muted" title="{{ dispayTimeStamp($user->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($user->created_at)->diffForHumans() }}</span>
                        </div>
                    </li>
                 @endforeach
                @else
                <li class="list-item">No Records !</li>
                @endif
          </ul>

        <div class="box-footer">
            <a href="{{ URL('admin/user/new') }}" class="btn btn-sm btn-outline b-info rounded text-u-c pull-right">Add user</a>
            <a href="{{ URL('admin/user') }}" class="btn btn-sm white text-u-c rounded">More</a>
        </div>

        </div>
    </div>


		    <div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>Latest Private Loans</h3>
					</div>
					<div class="box-body">
					  	<div class="streamline b-l m-l">
					  	@if(count($investments) > 0)
		            		@foreach($investments as $investment)
					        <div class="sl-item b-blue">
					          <div class="sl-content">
					            <div class="sl-date text-muted" title="{{ dispayTimeStamp($investment->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($investment->created_at)->diffForHumans() }} | {{ str_replace('_', ' ', ucwords(strtolower($investment->payment_through))) }}</div>
					            <div><a href="{{ url('admin/user/view/'.$investment->user->id.'') }}" class="text-info"> {{ ucfirst($investment->user->first_name).' | '.$investment->user->username }} </a> deposited amount <a href="{{ url('admin/loan/view/'.$investment->depositid.' ') }}" data-html="true" data-toggle="tooltip" class="text-success" title="{{ $investment->plan->plan_name }}"> $ {{ number_format($investment->amount,2) }}</a></div>
					          </div>
					        </div>
		            		@endforeach
		            		@else
							<div class="sl-item b-info">
								<div class="sl-content">
									<div>No Records! </div>
								</div>
							</div>
		            		@endif
					    </div>
					</div>
				  	<div class="box-footer">
				  		<a href="{{ url('admin/loan') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>

			<div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>Latest Interest Payments</h3>
					</div>
					<div class="box-body">
					  	<div class="streamline b-l m-l">
					  	@if(count($interestPayments) > 0)
							@foreach($interestPayments as $interestPayment)
					        <div class="sl-item b-warning">
					          <div class="sl-content">
					            <div class="sl-date text-muted" title="{{ dispayTimeStamp($interestPayment->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($interestPayment->created_at)->diffForHumans() }}</div>
					            <div><a href="{{ url('admin/user/view/'.$interestPayment->id.'') }}" class="text-info"> {{ ucfirst($interestPayment->first_name).' | '.$interestPayment->username }} </a> earned <span class="text-success">$ {{ number_format($interestPayment->pro_amount,2) }}</span> interest</div>
					          </div>
					        </div>
		            		@endforeach
		            		@else
							<div class="sl-item b-info">
								<div class="sl-content">
									<div>No Records! </div>
								</div>
							</div>
		            		@endif
					    </div>
					</div>
				  	<div class="box-footer">
				  		<a href="{{ url('admin/interest-payment') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>
			
		</div>

		<div class="row">
			<div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>Latest Commissions Earned</h3>
					</div>
					<div class="box-body">
					  	<div class="streamline b-l m-l">
					  	@if(count($levelCommisions) > 0)
							@foreach($levelCommisions as $levelCommision)
					        <div class="sl-item b-warning">
					          <div class="sl-content">
					            <div class="sl-date text-muted" title="{{ dispayTimeStamp($levelCommision->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($levelCommision->created_at)->diffForHumans() }}</div>
					            <div><a href="{{ url('admin/user/view/'.$levelCommision->id.'') }}" class="text-info"> {{ ucfirst($levelCommision->first_name).' | '.$levelCommision->username }} </a> earned <a href="javascript:void(0)" class="text-success">$ {{ number_format($levelCommision->commission,2) }}</a> commission</div>
					          </div>
					        </div>
		            		@endforeach
		            		@else
							<div class="sl-item b-info">
								<div class="sl-content">
									<div>No Records! </div>
								</div>
							</div>
		            		@endif
					    </div>
					</div>
				  	<div class="box-footer">
				  		<a href="{{ url('admin/level-commision') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>				

            @if(count($tickets) > 0)
			<div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>Latest Tickets</h3>
					</div>
					<div class="box-body">
					  	<div class="streamline b-l m-l">
							@foreach($tickets as $ticket)
					        <div class="sl-item b-info">
					          <div class="sl-content">
					            <div class="sl-date text-muted" title="{{ dispayTimeStamp($ticket->updated_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($ticket->updated_at)->diffForHumans() }}</div>
					            <div><a href="{{ url('admin/user/view/'.$ticket->user->id.'') }}" class="text-info"> {{ ucfirst($ticket->user->first_name).' | '.$ticket->user->username }} </a> @if($ticket->status == 'new') generate @else reopen @endif <a href="{{ url('admin/ticket/view/'.$ticket->ticketid.' ') }}" class="text-success" data-html="true" data-toggle="tooltip" title="{{ $ticket->subject }}"> {{ $ticket->ticket_no }}</a></div>
					          </div>
					        </div>
		            		@endforeach
					    </div>
					</div>
				  	<div class="box-footer">
				  		<a href="{{ url('admin/ticket') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>
            @endif

            @if(count($withdrawals) > 0)
			<div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>New Withdrawals</h3>
					</div>
					<div class="box-body">
					  	<div class="streamline b-l m-l">
							@foreach($withdrawals as $withdrawal)
					        <div class="sl-item b-info">
					          <div class="sl-content">
					            <div class="sl-date text-muted" title="{{ dispayTimeStamp($withdrawal->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($withdrawal->created_at)->diffForHumans() }}</div>
					            <div><a href="{{ url('admin/user/view/'.$withdrawal->id.'') }}" class="text-info"> {{ ucfirst($withdrawal->first_name).' | '.$withdrawal->username }} </a> requested for Withdrawal <a href="{{ url('admin/withdraw/view/'.$withdrawal->withdrawcod.' ') }}" class="text-success"> $ {{ number_format($withdrawal->amount,2) }}</a></div>
					          </div>
					        </div>
		            		@endforeach
					    </div>
					</div>
				  	<div class="box-footer">
				  		<a href="{{ url('admin/withdraw') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>
            @endif

		</div>

		<div class="row">

		<div class="col-md-6 col-xl-4">
				<div class="box">
					<div class="box-header">
						<h3>Plan Change Request</h3>
					</div>

          <ul class="list-group no-border">
            @if(count($depositPlanChanges) > 0)
              @foreach($depositPlanChanges as $depositPlanChange)
              <?php  $random_keys = array_rand($clsData1,1); $clsLife = $clsData1[$random_keys]; ?>
              <li class="list-group-item">
                <a herf="" class="list-left" title="{{ dispayTimeStamp($depositPlanChange->updated_at)->toDayDateTimeString() }}">
                    <span class="w-40 avatar {{ $clsLife }}">
                      <span>{{ substr($depositPlanChange->first_name, 0,1) }}</span>
                      <i class="on b-white bottom"></i>
                    </span>
                  </a>
                <div class="clear">
                  <a href="{{ URL('admin/user/view/'.$depositPlanChange->id.'') }}" class="_500 block text-info" title="{{ dispayTimeStamp($depositPlanChange->updated_at)->diffForHumans() }}">{{ ucfirst($depositPlanChange->first_name).' | '.$depositPlanChange->username }}</a>
                  @if($depositPlanChange->status == 'approved') <span class="text-success">requested for plan change from</span> @else <span class="text-danger">cancelled plan change request from</span> @endif <span class="text-info">{{ $depositPlanChange->old_plan_name }}</span> <i class="material-icons">&#xE5C8;</i> <a href="{{ url('admin/loan/view/'.$depositPlanChange->depositid.'') }}" class="text-info">@if($depositPlanChange->status == 'approved') {{ $depositPlanChange->new_plan_name }} @else <del title="Cancelled">{{ $depositPlanChange->new_plan_name }}</del> @endif</a> {{ dispayTimeStamp($depositPlanChange->updated_at)->diffForHumans() }}
                </div>
              </li>
              @endforeach
              @else
              <li>No Records!</li>
              @endif
          </ul>
				  	<div class="box-footer">
				  		<a href="{{ url('admin/deposit/change/plan') }}" class="btn btn-sm white text-u-c rounded">More</a>
				  	</div>
			  	</div>
			</div>	

			</div>
</div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/level-commision.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/dashboard.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop