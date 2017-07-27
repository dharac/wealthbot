<?php 
$page_name = 'invite-friend';
$page_name1 = 'Invite Your Friends';
?>
@extends('admin.parts.layout')  
@section('adminTitle', ucfirst($page_name1))
@section('adminBody')

<?php 

    $subject        = "Hey [[friend_name]], check this out...";
	$body        	= "Hi [[friend_name]],"
						."<br /><br />"
						."It's [[your_name]],"

                        ."<br/><br/>"

                        ."I just found out about something pretty cool, and you were the first person I thought of when I saw it."

						."<br/><br/>"
						."I just know you would want to see it too, so check it out."
						."<br><br><a style='color: blue;font-size: 13px;text-decoration: none;line-height: 18px;' href='".URL('/track/'.Auth::user()->username.'')."' target='_blank' >".URL('/track/'.Auth::user()->username.'')."</a>"
                        ."<br/><br/>"
						."Talk soon,"."<br />"."[[your_name]]";

    $email          = "";
    $yourName 		= ucfirst(Auth::user()->first_name).' '.ucfirst(Auth::user()->last_name);
    $yourEmail 		= Auth::user()->email;
    
    $btn_status     = 'Send Email';
    $submit_link    = 'user/'.$page_name.'/store';
    $eid            = "";
?>
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header"> <h2>{{ ucfirst($page_name1) }}<small></small></h2> </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    {!! Form::open(array('url' => $submit_link)) !!}
                    {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}

                    <div class="form-group row {{ $errors->has('yourName') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Your Name</label>
                            <div class="col-sm-10">
                               {{ $yourName }}
                            </div>
                        </div> 

                         <div class="form-group row {{ $errors->has('yourEmail') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Your Email</label>
                            <div class="col-sm-10">
                               {{ $yourEmail }}
                            </div>
                        </div> 

                       <div class="form-group row {{ $errors->has('frnName') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label"></label>
							<div class="col-sm-10">
							<input type="hidden" name="counter" id="counter" value="1">
								<table class="table" id="EmailTable">
									<thead>
										<tr>
											<th></th>
											<th>Friend's name</th>
											<th>Friend's email</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>#1</td>                   
											<td><input required type="text" name="frnName[]" placeholder="Jon Doe" class="form-control" name=""></td>
											<td><input required type="email" name="frnEmail[]" placeholder="jondoe@example.com" class="form-control" name=""></td>
											<td><span style="margin-top: 5px !important;display: block;"><a href="javascript:void(0)" class="btn btn-icon btn-sm blue addMore" title="Add More"><i class="fa fa-plus"></i></a></span></td>
										</tr>
									</tbody>
								</table>
							</div>
                        </div> 

                        <div class="form-group row {{ $errors->has('subject') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Subject</label>
                            <div class="col-sm-10">
                               {{ Form::text('subject', $subject, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'subject' , 'placeholder' => 'Subject']) }}
                                @if ($errors->has('subject'))
                                    <span class="parsley-required">{{ $errors->first('subject') }}</span>
                                @endif
                            </div>
                        </div> 

                        <div class="form-group row {{ $errors->has('body') ? 'has-danger' : '' }}">
                            <label class="col-sm-2 form-control-label">Body</label>
                            <div class="col-sm-10">
                               {{ Form::textarea('body', $body, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'body' , 'placeholder' => 'Body']) }}
                                @if ($errors->has('body'))
                                    <span class="parsley-required">{{ $errors->first('body') }}</span>
                                @endif
                            </div>
                        </div> 


                        <div class="dker p-a text-right">
                            <a href="{{ URL('dashboard') }}" class="btn btn-fw info">Cancel</a>
                            <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                        </div> 

                    {!! Form::close() !!}
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('pageScript')
<script src="{!! URL::asset('local/assets/js/admin/invite-friend.js') !!}"></script>
<script type="text/javascript" src="{{ URL::asset('local/assets/vendor/ckeditor/ckeditor.js') }}"></script>
<script> 
$(document).ready(function() {
    //CKEDITOR.replace('body'); 
    CKEDITOR.replace('body', {readOnly: true} );  
    $("#subject").attr('readonly','readonly');
});
</script>
@stop
