<?php
$page_name = 'ticket detail';
$singleRecord = $ticket;
$primary_key = 'ticketid';
?>
@extends('admin.parts.layout')
@section('adminTitle', $singleRecord->ticket_no.' | '.ucwords($page_name))
@section('adminBody')

<div class="padding p-b-xs">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 text-left row col-xs-8">
                    <div class="box-header">
                        <h2>{{ ucwords($page_name) }} : <span class="text-info">{{ $singleRecord->ticket_no }}</span></h2>
                    </div>
                </div>
                <div class="col-md-6 text-right row col-xs-4">
                <a class="md-btn md-fab m-b-sm blue" href="{{ url('admin/ticket') }}" title="Back to Ticket Support"><i class="material-icons">&#xE15E;</i></a>
                </div>
            </div>
            <div class="box-divider m-a-0"></div>
            <div class="box-body">
            <div class="panel-body">
                <strong>Name</strong> :  {{ ucfirst($singleRecord->first_name).' '.ucfirst($singleRecord->last_name)  }} | {{ $singleRecord->username }}<br>
                <strong>Subject</strong> :  {{ ucfirst($singleRecord->subject)  }}<br>
                <strong>Messages</strong> :  {{ $singleRecord->message }}<br>
                <strong>Email</strong> :  {{ $singleRecord->email ? $singleRecord->email : '-' }}<br>
                <strong>Phone</strong> :  {{ $singleRecord->phone ? $singleRecord->phone : '-' }}<br>
                <strong>Status</strong> :  {{ $singleRecord->status ? ucfirst($display_statuss[$singleRecord->status]) : '-' }}<br>
                <strong>Created Date</strong> :  {{ dispayTimeStamp($singleRecord->created_at) ? dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() : '-' }}<br>
               </div>
            </div>
        </div>
    </div>

<div class="padding">
  <ul class="timeline timeline-center">
    <li class="tl-header">
      <div class="btn white replys">Reply</div>
    </li>
   @if(count($replys) > 0)
    @foreach($replys as $reply)
    @if(Auth::user()->id == $reply->created_by)
    <li class="tl-item tl-left">
      <div class="tl-wrap b-success">
        <span class="tl-date text-muted" title="{{ dispayTimeStamp($reply->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($reply->created_at)->diffForHumans() }}</span>
        <div class="tl-content box-color primary block">
          <span class="arrow b-primary left pull-top hidden-left"></span>
          <span class="arrow b-primary right pull-top visible-left"></span>
          <div class="p-x m-b-sm font-bold">{{ ucfirst($reply->first_name).' '.ucfirst($reply->last_name) }} | {{ ucwords($display_statuss[$reply->status]) }}   </div>
          <div class="box-body b-t b-primary">
            {{ $reply->message }}
          </div>             
        </div>
      </div>
    </li>
    @else
    <li class="tl-item tl-right">
      <div class="tl-wrap b-info">
        <span class="tl-date text-muted" title="{{ dispayTimeStamp($reply->created_at)->toDayDateTimeString() }}">{{ dispayTimeStamp($reply->created_at)->diffForHumans() }}</span>
        <div class="tl-content box-color info block">
          <span class="arrow b-info left pull-top hidden-left"></span>
          <span class="arrow b-info right pull-top visible-left"></span>
          <div class="p-x m-b-sm font-bold">{{ ucfirst($reply->first_name).' '.ucfirst($reply->last_name) }} | {{ ucwords($display_statuss[$reply->status]) }} </div>
          <div class="box-body b-t b-info">
            {{ $reply->message }}
          </div>             
        </div>
      </div>
    </li>
    @endif
    @endforeach
    @else
    <li class="tl-item tl-left">
      <div class="tl-wrap b-primary">
        <span class="tl-date text-muted"></span>
        <div class="tl-content box-color primary p-a-sm">
          <span class="arrow b-primary left pull-top hidden-left"></span>
          <span class="arrow b-primary right pull-top visible-left"></span>
          <div class="text-lt">Awaiting Reply... </div>
        </div>
      </div>
    </li>
    @endif
  </ul>
</div>

<div class="padding" id="replys">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header"> <h2>Reply</h2></div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => 'admin/ticket/reply/store')) !!}
                        <strong>Ticket No</strong> : {{ $singleRecord->ticket_no }}<br>
                        {{ Form::hidden('ticketid',$singleRecord->ticketid ) }}
                        <strong>Subject</strong> : {{ ucfirst($singleRecord->subject)  }}<br>
                        <br>

                        <div class="form-group row {{ $errors->has('status') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Status *</label>
                            <div class="col-sm-10">
                                {{ Form::select('status', $statuss , '' , ['class' => 'form-control' ]) }}
                                @if ($errors->has('status'))
                                    <span class="parsley-required">{{ $errors->first('status') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('message') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 control-label">Message *</label>
                            <div class="col-sm-10">
                                {{ Form::textarea('message',null, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'message' , 'placeholder' => 'Message']) }}
                                @if ($errors->has('message'))
                                    <span class="parsley-required">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="dker p-a text-right">
                            <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;Send</button>
                        </div>
                        {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
