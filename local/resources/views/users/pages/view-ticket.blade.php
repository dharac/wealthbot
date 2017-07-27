<?php $page_name = 'ticket'; 
$status_value = 'closed';
$message_string = " If you're satisfied with the response, please check closed.";
if($ticket->user_status == 'closed')
{
  $status_value = 'reopen';
  $message_string = " This Ticket is currently closed. If you wish to reopen this ticket please tick the checkbox to reopen ticket.";
}
?>
@extends('admin.parts.layout')
@section('adminTitle', $ticket->ticket_no.' | Ticket Detail')
@section('adminBody')
<div class="padding p-b-xs">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 text-left row col-xs-10">
                    <div class="box-header">
                        <h2>Ticket Details | <span class="text-info">{{ $ticket->ticket_no }}</span></h2>
                    </div>
                </div>
                <div class="col-md-6 text-right row col-xs-2">
                  <a class="md-btn md-fab m-b-sm blue" href="{{ URL('user/'.$page_name.'') }}" title="Back to {{ ucfirst($page_name) }}"><i class="material-icons">&#xE15E;</i></a>
                </div>
            </div>
            <div class="box-divider m-a-0"></div>
            <div class="box-body">
            <div class="panel-body">
                <strong>Subject</strong> : {{ ucfirst($ticket->subject)  }}<br>
                <strong>Messages</strong> : {{ $ticket->message }}<br>
                <strong>Email</strong> : {{ $ticket->email ? $ticket->email : '-' }}<br>
                <strong>Number</strong> : {{ $ticket->phone ? $ticket->phone : '-' }}<br>
                <strong>Status</strong> : {{ $statuss[$ticket->user_status] ? ucfirst($statuss[$ticket->user_status]) : '-' }}<br>
                <strong>Created Date</strong> : {{ dispayTimeStamp($ticket->created_at) ? dispayTimeStamp($ticket->created_at)->toDayDateTimeString() : '-' }}<br>
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
          <div class="p-x m-b-sm font-bold">{{ ucfirst($reply->first_name).' '.ucfirst($reply->last_name) }} | {{ ucwords($statuss[$reply->user_status]) }} </div>
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
          <div class="p-x m-b-sm font-bold">{{ ucfirst($reply->first_name).' '.ucfirst($reply->last_name) }} | {{ ucwords($statuss[$reply->user_status]) }} </div>
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
                        {!! Form::open(array('url' => 'user/ticket/reply/store')) !!}
                        <strong>Ticket No</strong> : {{ $ticket->ticket_no }}<br>
                        {{ Form::hidden('ticketid',$ticket->ticketid ) }}
                        <strong>Subject</strong> : {{ ucfirst($ticket->subject)  }}<br>
                        <strong>Current Status</strong> : {{ ucwords($statuss[$reply->user_status]) }}<br>
                          
                          <div class="form-group row">
                            <label class="col-sm-2 form-control-label">Status</label>
                                <div class="col-sm-10">
                                <label class="md-check">
                                    {{ Form::checkbox('status', $status_value,'', ['class' => 'flat' ,'id' => 'status' ]) }}
                                    <i class="blue"></i>
                                    {{ ucfirst($status_value) }}
                                </label>
                                <br><strong>Note</strong> :{{ $message_string }}
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