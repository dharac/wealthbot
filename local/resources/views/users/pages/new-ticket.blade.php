<?php 
$page_name = 'ticket';
$primary_key = 'ticketid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<?php 
if($mode == 'Edit')
{
    $singleRecord       =   $ticket;
    $eid                =   $singleRecord->$primary_key;

    //CHANGE
    $subject            =   $singleRecord->subject;
    $message            =   $singleRecord->message;
    $email              =   $singleRecord->email;
    $phone              =   $singleRecord->phone;

    $btn_status         =   'Update '.ucfirst($page_name);
    $submit_link        =   'user/'.$page_name.'/update';
}
else
{
    $subject            = "";
    $message            = "";
    $email              = Auth::user()->email;
    $phone              = Auth::user()->phone;
    
    $btn_status         =   'Add '.ucfirst($page_name);
    $submit_link        =   'user/'.$page_name.'/store';
    $eid                =   "";
}

?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ $mode.' '.ucfirst($page_name) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link , 'id' => 'new_ticket')) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('subject') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Subject *</label>
                            <div class="col-sm-10">
                               {{ Form::text('subject', $subject, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'subject' , 'placeholder' => 'Subject']) }}
                                @if ($errors->has('subject'))
                                    <span class="parsley-required">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('message') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Message *</label>
                            <div class="col-sm-10">
                               {{ Form::textarea('message', $message, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'message' , 'placeholder' => 'Message']) }}
                                @if ($errors->has('message'))
                                    <span class="parsley-required">{{ $errors->first('message') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Email</label>
                            <div class="col-sm-10">
                                {{ Form::text('email', $email, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'email' , 'placeholder' => 'Email']) }}
                                @if ($errors->has('email'))
                                    <span class="parsley-required">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div> 


                         <div class="form-group row {{ $errors->has('phone') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Cell Phone</label>
                            <div class="col-sm-10">
                                {{ Form::text('phone', $phone, ['class' => 'form-control col-md-7 col-xs-12 phone' ,'id' => 'phone' , 'placeholder' => 'Phone']) }}
                                @if ($errors->has('phone'))
                                    <span class="parsley-required">{{ $errors->first('phone') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="dker p-a text-right">
                            <a href="{{ URL('user/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                            <button type="submit" class="btn btn-fw primary btn-go-fast-ticket"><span><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</span></button>
                        </div> 

                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
<script type="text/javascript" data-cfasync="false" src="{!! URL::asset('local/assets/js/admin/tickets.js') !!}?v={{ config('services.SCRIPT.VERSION') }}"></script>
@stop