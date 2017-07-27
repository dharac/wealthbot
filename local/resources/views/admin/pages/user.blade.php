<?php 
$page_name1 = 'users';
$page_name = 'user';
$allRecord = $users;
$primary_key = 'id';

$search_string = '';
if($mode == 'search')
{
    $page_name1 = 'Search Users';
    $search_string = $searchValue; 
}

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name1))
@section('adminBody')

@if($mode == 'search')
	<div class="padding"><div class="col-md-12"><b>{{ $allRecord->total() }}</b> Results found for: <b>@if(isset($searchValue)) {{ $searchValue }} @endif</b> &nbsp;&nbsp;<a href="{{ url('admin/user') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif

<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-6 col-xs-6 text-left row">
				<div class="box-header">
					<h2>{{ ucwords($page_name1) }}</h2>
				</div>
			</div>
			<div class="col-md-6 col-xs-6 text-right">
				@if($mode != 'search')
				<a class="md-btn md-fab m-b-sm blue" href="{{ URL('admin/'.$page_name.'/new') }}" title="Add {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE145;</i></a>&nbsp;
				<a class="md-btn md-fab m-b-sm teal user-export-popup" href="javascript:void(0);"><i class="material-icons">&#xE2C0;</i></a>
				@endif
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Referrer</th>
						<th>Founder</th>
						<th>Status</th>
						<th>Registration Date</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
						<th scope="row">{{ $a }}</th>
						<td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record"  >{{ ucfirst($singleRecord->first_name) }} {{ ucfirst($singleRecord->last_name) }}</a> | {{ $singleRecord->username }}
						<br>
						@if($singleRecord->confirmed == '1')
						<span class="text-success" title="Email Verified"><i class="material-icons">&#xE8E8;</i> {{ $singleRecord->email }}</span>
						@else
						<span class="text-danger" title="Email Not Verified" ><i class="material-icons">&#xE8AE;</i> {{ $singleRecord->email }}</span>
						@endif <br>
						<span class="text-accent">{{ $singleRecord->display_name }}</span>
						</td>
						<td>
						@if($singleRecord->ufirst_name != "" || $singleRecord->ulast_name != "")
						<a href="javascript:void(0);" data-id="{{ $primary_column }}" class="view-upper-level text-info">{{ ucfirst($singleRecord->ufirst_name) }} {{ ucfirst($singleRecord->ulast_name) }}</a>
						@else
						{{ config('services.SITE_DETAILS.SITE_NAME') }}
						@endif
						</td>
						<td>@if($singleRecord->founder == 1) Yes @else - @endif</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'active')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
						@elseif($singleRecord->status == 'inactive')
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($singleRecord->status) }}</span>
						@else
						<span class="text-info" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($singleRecord->status) }}</span>
						@endif
						</td>
						<td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">
						<a href="{{ URL('admin/'.$page_name.'/referrals/'.$primary_column.' ') }}" data-placement="left" title="Referrals Information"><i class="material-icons">&#xE335;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="{{ URL('admin/'.$page_name.'/email/'.$primary_column.' ') }}" data-placement="left" title="Send Email"><i class="material-icons">&#xE0BE;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ URL('admin/'.$page_name.'/view/'.$primary_column.' ') }}" data-placement="left" title="View Record"><i class="material-icons">&#xE8F4;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ URL('admin/'.$page_name.'/delete?page_name='.$page_name.'&id='.$primary_column.'&search_string='.$search_string) }}" title="Delete Record"><i class="material-icons">&#xE872;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void({{ $primary_column }});" class="referral-report-popup" data-id="{{ $primary_column }}" title="View Deposit"><i class="material-icons">&#xE3CA;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('admin/user/panel/'.$primary_column.'') }}" class="" title="Go to User Panel"><i class="material-icons">&#xE853;</i></a>
						{!! Form::open(array('url' => 'admin/user/password/email' , 'style' =>     'display: inline-block;' )) !!}
						{{ Form::hidden('email', $singleRecord->email , array('id' => 'email')) }}
						&nbsp;|&nbsp;<button class="trans-button" title="Reset Password" onclick="return confirm('Are you sure you want to reset this password ?');"><i class="material-icons">&#xE898;</i></button>
						{!! Form::close() !!}
						</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="7">No Records !</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>

		<footer class="dker p-a">
			<div class="row">
				<div class="col-sm-6 col-md-6 col-xs-12 text-left">
					<small class="text-muted inline m-t-sm m-b-sm">Showing {{ $allRecord->firstItem() }} to {{ $allRecord->lastItem() }} of {{ $allRecord->total() }} entries</small>
				</div>
				<div class="col-sm-6 col-md-6 col-xs-12 text-right text-center-xs">
					@if($mode == 'search')
						{!! $allRecord->appends(['q' => $searchValue])->render() !!}
					@else
						{{ $allRecord->links() }}
					@endif
				</div>
			</div>
		</footer>
	</div>
</div>

<div id="user_export_modal" class="modal" data-backdrop="true">
    <div class="modal-dialog modal-lg">
    	{!! Form::open(array('url' => 'admin/user/export')) !!}
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Export User Data</h5></div>
            <div class="modal-body text-center p-lg">

				<div class="form-group row {{ $errors->has('counm') ? 'has-danger' : '' }}">
					<label class="col-sm-4 text-left form-control-label">Export Format </label>
					<div class="col-sm-8 text-left" style="padding-top: 7px;">
						<label class="md-check"><input type="radio" name="exporttype" checked="checked" value="xlsx" class="has-value"><i class="pink"></i> Excel</label>&nbsp;&nbsp;&nbsp;
						<label class="md-check"><input type="radio" name="exporttype" value="csv" class="has-value"><i class="pink"></i> CSV</label>
					</div>
				</div>

           	<div class="table-responsive"></div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn danger p-x-md" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-fw primary"><i class="material-icons">&#xE2C0;</i> Export Data</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/referral-report.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/user.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop