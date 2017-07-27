<?php 
$page_name = 'sms-management';
$page_name2 = 'sms Management';
$allRecord = $SmsManagements;
$signature_record = $sms_signatures;

$primary_key = 'smsid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";

?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name2))
@section('adminBody')
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-12 text-left row">
				<div class="box-header">
					<h2>{{ ucwords($page_name2) }}</h2>
				</div>
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<div class="table-responsive">
			<table class="table table-striped b-t">
				<thead>
					<tr>
						<th>#</th>
						<th>Subject</th>
						<th>Body</th>
						<th>Status</th>
						<th>Modified Date</th>
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
						<td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record">{{ $singleRecord->subject }}</a></td>
						<td>{{ str_limit(strip_tags($singleRecord->body),50) }}</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'active')
						<span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
						@else
						<span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($singleRecord->status) }}</span>
						@endif
						</td>
						<td title="{{ dispayTimeStamp($singleRecord->updated_at)->diffForHumans() }}" nowrap="nowrap">{{ dispayTimeStamp($singleRecord->updated_at)->toDayDateTimeString() }}</td>
						<td nowrap="nowrap">
						@if($singleRecord->status == 'active')
						<a href="{{ URL('admin/'.$page_name.'/status/'.$primary_column.' ') }}" data-placement="left" title="Click Inactive"><i class="text-success material-icons">&#xE8E1;</i></a>
						@else
						<a href="{{ URL('admin/'.$page_name.'/status/'.$primary_column.' ') }}" data-placement="left" title="Click Active"><i class="text-warning material-icons">&#xE8E1;</i></a>
						@endif
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>
						</td>
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

	<div class="box">
        <div class="row p-a">
            <div class="col-md-6 text-left row">
                <div class="box-header">
                    <h2>Signature</h2>
                </div>
            </div>
            <div class="col-md-6 text-right">
            </div>
        </div>

       <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped b-t">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject</th>
                        <th>Body</th>
                        <th>Modified Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = $signature_record->firstItem(); ?>
                    @if(count($signature_record) > 0)
                        @foreach($signature_record as $singleRecord)
                            <tr title="sms_signature_{{ $singleRecord->$primary_key }}">
                            <th scope="row">{{ $index }}</th>
                          
                            <td><a href="{{ URL('admin/'.$page_name.'/edit/'.$singleRecord->$primary_key.' ') }}" data-placement="left" title="Edit Record">{{ str_limit(strip_tags($singleRecord->subject),50) }}<a></td>
                          
                            <td>{{ str_limit(strip_tags($singleRecord->body),50) }}</td>
                            
                           <td title="{{ $singleRecord->updated_at->diffForHumans() }}" nowrap="nowrap">{{ $singleRecord->updated_at->toDayDateTimeString() }}</td>
                        <td nowrap="nowrap">
                        <a href="{{ URL('admin/'.$page_name.'/edit/'.$singleRecord->smsid.' ') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>
                        </td>
                            </tr>
                        <?php $index++; ?>
                        @endforeach
                    @else
                    <tr>
                        <td class="text-center" colspan="5">No Records !</td>
                    </tr>
                    @endif

               </tbody>
            </table>
        </div>
    </div>

</div>
@endsection