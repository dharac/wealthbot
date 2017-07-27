<?php 
$page_name = 'Commissions Earned';
$page_name2 = 'level-commision';
$primary_key = 'comid';
$allRecord = $levelCommisions;

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')

<div class="commission-desc"></div>

<div class="col-md-12 padding">
        <div class="b-b b-primary nav-active-primary">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link a-available-commissions active" href="javascript:void(0);" data-toggle="tab"  data-target="#available_commissions"><i class="material-icons">&#xE877;</i> Available Commissions</a>
            </li>
            <li class="nav-item">
              <a class="nav-link a-pending-commissions" href="javascript:void(0);" data-toggle="tab"  data-target="#pending_commissions"><i class="material-icons">&#xE88F;</i> Pending Commissions</a>
            </li>
          </ul>
        </div>
        <div class="tab-content p-a m-b-md" style="padding: 0px !important;padding-top:20px !important;">

          <div class="tab-pane animated fadeIn text-muted active" id="available_commissions">

				@if($dateRange != "")
				<div class="padding" style="padding-left: 0px;padding-bottom: 15px !important;padding-top: 5px;">{{ $page_name }} show between <b>{{ $dateRange }}</b> &nbsp;&nbsp;<a href="{{ url('user/level-commision') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div>
				@endif

				<div class="box">
					<div class="row p-a">
						<div class="col-md-7 text-left row">
							<div class="box-header box-header-data" data-stdt="{{ $stdt }}" data-endt="{{ $endt }}">
								<h2>Available Commissions <small>Level Commissions</small></h2>
							</div>
						</div>
						<div class="col-md-5 text-right">
						{!! Form::open(array('url' => 'user/level-commision','method' => 'get')) !!}
						<input type="hidden" name="startdt" id="startdt">
						<input type="hidden" name="enddt" id="enddt">
						<button class="md-btn md-fab m-b-sm blue pull-right" style="margin-top:-5px;"><i class="material-icons md-24">&#xE8B6;</i></button>
						<lable id="reportrange" class="pull-right datepicker_cls"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;<span></span> <b class="caret"></b>
						</lable>
						{!! Form::close() !!}
						</div>
					</div>

					<div class="box-divider m-a-0"></div>
					<div class="table-responsive">
						<table class="table table-striped b-t">
							<thead>
								<tr>
									<th>#</th>
									<th>Username</th>
									<th>Name</th>
									<th>Referral Level</th>
									<th>Status</th>
									<th>Commission Earned Date</th>
									<th>Commission Paid %</th>
									<th>Commission ($)</th>
								</tr>
							</thead>
							<tbody>
								<?php $a = $allRecord->firstItem(); ?>
								@if(count($allRecord) > 0)
								@foreach($allRecord as $singleRecord)
								<?php  $primary_column = $singleRecord->$primary_key;  ?>
								<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
									<th scope="row">{{ $a }}</th>
									<td>{{ $singleRecord->username }}</td>
									<td>@if(Auth::user()->id == $singleRecord->refernceid) {{ ucfirst($singleRecord->first_name) }} {{ ucfirst($singleRecord->last_name) }} @else - @endif</td>
									<td>{{ App\myCustome\myCustome::addOrdinalNumberSuffix($singleRecord->referral_level) }} Level</td>
									<td nowrap="nowrap">
									@if($singleRecord->status == 'approved')
									<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst('available') }}</span>
									@elseif($singleRecord->status == 'pending')
									<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
									@else
									<span class="text-info" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
									@endif
									</td>
									<td title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
									<td nowrap="nowrap">{{ number_format($singleRecord->com_rate,2) }} %</td>
									<td nowrap="nowrap">$ {{ number_format($singleRecord->commission,2) }}</td>
								</tr>
								<?php $a++; ?>
								@endforeach
								@else
								<tr>
									<td class="text-center" colspan="8">No Records !</td>
								</tr>
								@endif
							</tbody>
							@if(count($allRecord) > 0)
			                    <tfoot>
			                        <tr>
			                            <th colspan="7" class="text-right no-border"><strong>Total</strong></th>
			                            <th nowrap="nowrap"><strong>$ {{ number_format($totalAmt,2) }}</strong></th>
			                        </tr>
			                    </tfoot>
			                    @endif
						</table>
					</div>

					<footer class="dker p-a">
						<div class="row">
							<div class="col-sm-4 text-left">
								<small class="text-muted inline m-t-sm m-b-sm">Showing {{ $allRecord->firstItem() }} to {{ $allRecord->lastItem() }} of {{ $allRecord->total() }} entries</small>
							</div>
							<div class="col-sm-8 text-right text-center-xs">
								@if($mode == 'search')
									{!! $allRecord->appends(['startdt' => $stdt,'enddt' => $endt])->render() !!}
								@else
									{{ $allRecord->links() }}
								@endif
							</div>
						</div>
					</footer>
				</div>
          </div>

          <div class="tab-pane animated fadeIn text-muted" id="pending_commissions">
            
          	<div class="box">
					<div class="row p-a">
						<div class="col-md-12 text-left row">
							<div class="box-header box-header-data">
								<h2>Pending Commissions<small>Level Commissions</small></h2>
							</div>
						</div>
					</div>

					<div class="box-divider m-a-0"></div>
					<div class="table-responsive">
					</div>
				</div>
          </div>
        </div>
      </div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/level-commision.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript"> $( document ).ready(function() { getBalance(); }); </script>
@stop