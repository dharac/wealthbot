<?php 
$page_name = 'ticket';
$allRecord = $tickets;
$primary_key = 'ticketid';
 ?>
@extends('admin.parts.layout')
@section('adminTitle', ucfirst($page_name).'s')
@section('adminBody')
<div class="padding">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 text-left row col-xs-10">
                    <div class="box-header">
                        <h2>{{ ucwords($page_name) }}s</h2>
                    </div>
                </div>
                <div class="col-md-6 text-right col-xs-2">
                    <a class="md-btn md-fab m-b-sm blue" href="{{ URL('user/'.$page_name.'/new') }}" title="Add {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE145;</i></a>
                </div>
            </div>

            <div class="box-divider m-a-0"></div>
            <div class="table-responsive">
                <table class="table table-striped b-t">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ticket No</th>
                        <th>Subject</th>
                        <th>Messages</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php $a = $allRecord->firstItem(); ?>
                @if(count($allRecord) > 0)
                    @foreach($allRecord as $singleRecord)
                        <?php  $primary_column = $singleRecord->$primary_key;  ?>
                        <tr title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
                            <th scope="row">{{ $a }}</th>
                            <td><a href="{{ URL('/user/'.$page_name.'/view/'.$primary_column.' ') }}" data-placement="left" title="View Record" class="text-info" >{{ $singleRecord->ticket_no }}</a></td>
                            <td title="{{ $singleRecord->subject }}">{{ str_limit($singleRecord->subject,15) }}</td>
                            <td title="{{ $singleRecord->excerpt }}">{{ str_limit($singleRecord->excerpt,26) }}</td>
                            <td>{{ $statuss[$singleRecord->user_status] }}</td>
                            <td title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
                            <td><a href="{{ URL('/user/'.$page_name.'/view/'.$primary_column.' ') }}" data-placement="left" title="View Record"><i class="material-icons">&#xE8F4;</i></a>@if($singleRecord->status != "closed") &nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ URL('user/'.$page_name.'/edit/'.$primary_column.'') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>@endif</td>
                        </tr>
                        <?php $a++; ?>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="10">No Records !</td>
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