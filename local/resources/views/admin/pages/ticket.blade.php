<?php
$page_name = 'ticket';
$allRecord = $tickets;
$primary_key = 'ticketid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
<div class="error-msg"></div>

@if($q != '')
    <div class="padding"><div class="col-md-12"><b>{{ $allRecord->total() }}</b> Results found for: <b>@if(isset($q)) {{ $q }} @endif</b> &nbsp;&nbsp;<a href="{{ url('admin/ticket') }}" class="text-danger" title="Clear Filter"><i class="fa fa-times text-danger"></i></a> </div></div>
@endif

<div class="padding">
    <div class="box">
        <div class="row p-a">
            <div class="col-md-6 col-xs-12 text-left row">
                <div class="box-header">
                    <h2>{{ ucwords($page_name) }}s</h2>
                </div>
            </div>

            <div class="col-md-6 col-xs-12 text-right">
            <div class="col-md-4 col-xs-12">
                {!! Form::open(array('class' => 'navbar-form form-inline navbar-item','style' => 'display: inline-block;' )) !!}
                <select class="form-control" @if($s == "") selected="selected" @endif id="ticket_status">
                    @foreach($statuss as $key => $status)
                        @if($key != 'awaiting_admin_reply')
                            <option value="{{ $key }}" @if($s == $key) selected="selected" @endif>{{ $status }}</option>
                        @endif
                    @endforeach
                </select>
                {!! Form::close() !!}
            </div>
            <div class="col-md-8 col-xs-12">
                {!! Form::open(array('url' => 'admin/ticket','method' => 'get', 'class' => 'navbar-form form-inline navbar-item','style' => 'display: inline-block;' )) !!}
                    <div class="form-group l-h m-a-0">
                        <div class="input-group">
                            <input type="text" name="q" value="{{ $q }}" class="form-control b-a" placeholder="Search"> 
                            <span class="input-group-btn">
                            <button type="submit" class="btn  btn-default b-a no-shadow"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                {!! Form::close() !!}
                @if( count($allRecord) > 0 )
                    <button class="md-btn md-fab m-b-sm blue ticket-close-admin" title="Click to Closed"><i class="material-icons">&#xE163;</i></button>
                @endif
            </div>
        </div>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped b-t">
                <thead>
                <tr>
                    <th><label class="md-check"><input type="checkbox" id="chkMasterCheckbox"><i class="blue"></i></label></th>
                    <th>#</th>
                    <th>Ticket No</th>
                    <th>Name</th>
                    <th>Subject</th>
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
                        <tr class="{{ $primary_table_row.$primary_column }}" title="{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}">
                            <td>@if($singleRecord->status != 'closed')<label class="md-check ch-{{ $primary_column }}"><input type="checkbox" value="{{ $primary_column }}" class="chkSubCheckbox" name="ticket_id[]"><i class="blue"></i></label>@endif</td>
                            <th scope="row">{{ $a }}</th>
                            <td><a href="{{ URL('admin/'.$page_name.'/view/'.$primary_column.' ') }}" class="text-info" data-placement="left" title="View Record | Reply" >{{ $singleRecord->ticket_no }}</a></td>
                            <td>{{ ucfirst($singleRecord->user->first_name).' '.ucfirst($singleRecord->user->last_name) }}<br><a href="{{ URL('admin/user/view/'.$singleRecord->user->id.' ') }}" class="text-info">{{ $singleRecord->user->username }}</a></td>
                            <td title="{{ $singleRecord->subject }}">{{ str_limit($singleRecord->subject, 30) }}</td>
                            <td nowrap="nowrap" class="status-{{ $primary_column }}">
                            @if($singleRecord->status == 'pending')
                            <span class="text-danger m-r-sm"><i class="material-icons">&#xE88B;</i> {{ $statuss[$singleRecord->status] }}</span>
                            @elseif($singleRecord->status == 'reopen')
                            <span class="text-info m-r-sm"><i class="material-icons">&#xE89E;</i> {{ $statuss[$singleRecord->status] }}</span>

                            @elseif($singleRecord->status == 'closed')
                            <span class="text-success m-r-sm"> <i class="material-icons">&#xE877;</i> {{ $statuss[$singleRecord->status] }}</span>
                            @elseif($singleRecord->status == 'awaiting_your_reply')
                            <span class="text-accent m-r-sm"> <i class="material-icons">&#xE15E;</i> {{ $statuss[$singleRecord->status] }}</span>
                            @else
                            <span class="text-primary m-r-sm"> <i class="material-icons">&#xE873;</i> {{ $statuss[$singleRecord->status] }}</span>
                            @endif
                            </td>
                            <td nowrap="nowrap" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</td>
                            <td nowrap="nowrap" ><a href="{{ URL('admin/'.$page_name.'/view/'.$primary_column.'') }}" data-placement="left" title="View Record | Reply"><i class="material-icons">&#xE15E;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" class="{{ $primary_table_col_del }}" data-id="{{ $primary_column }}" data-pid="{{ $page_name }}" title="Delete Record"><i class="material-icons">&#xE872;</i></a></td>
                        </tr>
                        <?php $a++; ?>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="8">No Records !</td>
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
                    @if($q == '' && $s == '')
                        {{ $allRecord->links() }}
                    @else
                        {!! $allRecord->appends(['q' => $q ,'s' => $s ])->render() !!}
                    @endif
                </div>
            </div>
        </footer>
    </div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/tickets.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop