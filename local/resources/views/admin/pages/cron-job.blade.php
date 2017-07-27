<?php 
$page_name = 'Cron Job';
$allRecord = $crons;
$primary_key = 'cronid';

$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-7 text-left row">
				<div class="box-header box-header-data">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
			<div class="col-md-5 text-right">
			</div>
		</div>
		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Description</th>
						<th>Server Time</th>
						<th>Cron Job Time</th>
					</tr>
				</thead>
				<tbody>
					<?php $a = $allRecord->firstItem(); ?>  
					@if(count($allRecord) > 0)
					@foreach($allRecord as $singleRecord)
					<?php  $primary_column = $singleRecord->$primary_key;  ?>
					<tr>
						<th scope="row">{{ $a }}</th>
						<td nowrap="nowrap">{{ $singleRecord->description }}</td>
						<?php 
						$startdt  	= Carbon\Carbon::parse($singleRecord->server_time)->format('Y-m-d H:i:s');
						$startdt 	= Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $startdt);
						?>
						<td title="{{ $startdt->diffForHumans() }}" nowrap="nowrap">{{ $startdt->toDayDateTimeString() }} | {{ $singleRecord->server_timezone }}</td>
						<td title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }} | {{ Config::get('app.timezone_display2') }}</td>
					</tr>
					<?php $a++; ?>
					@endforeach
					@else
					<tr>
						<td class="text-center" colspan="5">No Records !</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>
		<footer class="dker p-a">
			<div class="row">
				<div class="col-sm-4 text-left">
					<small class="text-muted inline m-t-sm m-b-sm">Showing {{ $allRecord->firstItem() }} to {{ $allRecord->lastItem() }} of {{ $allRecord->total() }} entries</small>
				</div>
				<div class="col-sm-8 text-right text-center-xs">
					{{ $allRecord->links() }}
				</div>
			</div>
		</footer>
	</div>
</div>
@endsection