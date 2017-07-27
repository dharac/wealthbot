<?php
$page_name = 'plan';
$primary_key = 'planid';
?>
@extends('admin.parts.layout')
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')

<?php
$level[1]                   =   '';
$level[2]                   =   '';
$level[3]                   =   '';
$level[4]                   =   '';

$duration1                  = 0;
$duration2                  = 0;
$duration3                  = 0;

if($mode == 'Edit')
{
    $singleRecord           =   $plan;
    $eid                    =   $singleRecord->$primary_key;

    //CHANGE

    $plan_status        = $singleRecord->plan_status;
    $plan_statusY       = false;
    $plan_statusN       = true;
    if($plan_status == '1')
    {
        $plan_statusY = true;
        $plan_statusN = false;

        $duration    =   $singleRecord->duration;
        $array = str_split($duration);
        $duration1 = $array[0];
        $duration2 = $array[1];
        $duration3 = $array[2];

    }

    $plan_name              =   $singleRecord->plan_name;
    $spend_min_amount       =   $singleRecord->spend_min_amount;
    $spend_max_amount       =   $singleRecord->spend_max_amount;
    $profit                 =   $singleRecord->profit;
    $interest_period_type   =   $singleRecord->interest_period_type;
    $duration               =   $singleRecord->duration;
    $duration_time          =   $singleRecord->duration_time;
    $status                 =   $singleRecord->status;
    $nature_of_plan         =   $singleRecord->nature_of_plan;
    $founder                =   $singleRecord->founder;
    $new_founder            =   $singleRecord->new_founder;

    if(count($planLevels) > 0)
    {
        foreach ($planLevels as $planLevel)
        {
            $level[$planLevel->level] = $planLevel->commision;
        }
    }

    $btn_status             =   'Update '.ucfirst($page_name);
    $submit_link            =   'admin/'.$page_name.'/update';
}
else
{
    $plan_statusY           =   true;
    $plan_statusN           =   false;
    $plan_name              =   '';
    $spend_max_amount       =   '';
    $spend_min_amount       =   '';
    $interest               =   '';
    $interest_type          =   '';
    $period                 =   '';
    $profit                 =   '';
    $interest_period_type   =   '';
    $duration               =   '';
    $duration_time          =   '';
    $status                 =   '';
    $nature_of_plan         =   '';
    $founder                =   '';
    $new_founder            =   '';

    $btn_status             =   'Add '.ucfirst($page_name);
    $submit_link            =   'admin/'.$page_name.'/store';
    $eid                    =   '';
}
?>
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="row p-a">
                        <div class="col-md-6 text-left row col-xs-6">
                            <div class="box-header">
                                <h2>{{ $mode.' '.ucfirst($page_name) }}</h2>
                            </div>
                        </div>
                        @if($mode == 'View')
                            <div class="col-md-6 text-right col-xs-6">
                                <a class="md-btn md-fab m-b-sm blue" href="{{ url('admin/'.$page_name.'') }}" title="Back to {{ ucfirst($page_name) }}s"><i class="material-icons">&#xE15E;</i></a>
                                <a href="{{ URL('admin/'.$page_name.'/edit/'.$plan->$primary_key.' ') }}" class="md-btn md-fab m-b-sm cyan" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>
                            </div>
                        @endif
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                    @if($mode == 'Add' || $mode == 'Edit')
                        {!! Form::open(array('url' => $submit_link)) !!}
                        {{ Form::hidden('eid', $eid, array('id' => 'eid')) }}
                            <div class="form-group row {{ $errors->has('plan_name') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Plan Name <span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('plan_name', $plan_name, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'plan_name' , 'placeholder' => 'Plan Name']) }}
                                    @if ($errors->has('plan_name'))
                                        <span class="parsley-required">{{ $errors->first('plan_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('nature_of_plan') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Nature of Plan <span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::select('nature_of_plan', $natureOfPlan, $nature_of_plan, array('id' => 'nature_of_plan','class' => 'form-control col-md-7 col-xs-12' )) }}
                                    @if ($errors->has('nature_of_plan'))
                                        <span class="parsley-required">{{ $errors->first('nature_of_plan') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('spend_min_amount') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Minimum Amount ($)<span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('spend_min_amount', $spend_min_amount, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'spend_min_amount' , 'placeholder' => 'Minimum Amount ($)']) }}
                                    @if ($errors->has('spend_min_amount'))
                                        <span class="parsley-required">{{ $errors->first('spend_min_amount') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('spend_max_amount') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Maximum Amount ($)<span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('spend_max_amount', $spend_max_amount, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'spend_max_amount' , 'placeholder' => 'Maximum Amount ($)']) }}
                                    @if ($errors->has('spend_max_amount'))
                                        <span class="parsley-required">{{ $errors->first('spend_max_amount') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('profit') ? 'has-danger' : '' }}">
                              <label class="col-sm-2 form-control-label">Loan Interest Payment (%) <span class="parsley-required">*</span></label>
                              <div class="col-sm-10">
                                <div class="row">
                                  <div class="col-md-4">
                                    {{ Form::text('profit', $profit, ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'profit' , 'placeholder' => 'Loan Interest Payment (%)']) }}
                                    @if ($errors->has('profit'))
                                        <span class="parsley-required">{{ $errors->first('profit') }}</span>
                                    @endif
                                  </div>
                                  <div class="col-md-3">
                                  <label class="form-control-label">Loan Interest Payment Period <span class="parsley-required">*</span></label>
                                  </div>
                                  <div class="col-md-5">
                                    {{ Form::select('interest_period_type', $paymentPeriods ,$interest_period_type , ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'interest_period_type' ] ) }}
                                    @if ($errors->has('interest_period_type'))
                                        <span class="parsley-required">{{ $errors->first('interest_period_type') }}</span>
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group row {{ $errors->has('plan_status') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Does this plan end ?</label>
                                <div class="col-sm-10"> 

                                    <label class="md-check">
                                            {{ Form::radio('plan_status', '1', $plan_statusY , ['class' => 'flat' ,'id' => 'plan_statusY' ]) }}
                                            <i class="green"></i>
                                            Yes
                                    </label>

                                    <label class="md-check">
                                        {{ Form::radio('plan_status', '0', $plan_statusN , ['class' => 'flat' ,'id' => 'plan_statusN' ]) }}
                                        <i class="green"></i>
                                        No
                                    </label>
                                    @if ($errors->has('plan_status'))
                                        <span class="parsley-required">{{ $errors->first('plan_status') }}</span>
                                    @endif
                                </div>
                            </div>

                             <div class="plan_end_area @if($plan_statusY != '1') hide @endif @if(old('plan_status') == '0') hide @endif form-group row {{ $errors->has('duration_time') ? 'has-danger' : '' }}">
                              <label class="col-sm-2 form-control-label">Plan Duration Time<span class="parsley-required">*</span></label>
                              <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        {{ Form::select('duration_time', $paymentPeriods1 ,$duration_time , ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'duration_time' ]
                                    ) }}
                                    @if ($errors->has('duration_time'))
                                        <span class="parsley-required">{{ $errors->first('duration_time') }}</span>
                                    @endif
                                  </div>
                                  <div class="col-md-3">
                                    {{ Form::selectRange('duration1', 0, 9, $duration1 , ['class' => 'form-control col-md-7 col-xs-12']) }}
                                  </div>

                                  <div class="col-md-3">
                                    {{ Form::selectRange('duration2', 0, 9,  $duration2 , ['class' => 'form-control col-md-7 col-xs-12']) }}
                                  </div>

                                  <div class="col-md-3">
                                    {{ Form::selectRange('duration3', 0, 9, $duration3 , ['class' => 'form-control col-md-7 col-xs-12']) }}
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Founder</label>
                                <div class="col-sm-10">
                                <label class="md-check">
                                {{ Form::checkbox('founder', 1, $founder, ['class' => 'form-control']) }}
                                <i class="blue"></i>
                                Founder
                                </label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 form-control-label">New Founder</label>
                                <div class="col-sm-10">
                                <label class="md-check">
                                {{ Form::checkbox('new_founder', 1, $new_founder, ['class' => 'form-control']) }}
                                <i class="blue"></i>
                                New Founder
                                </label>
                                </div>
                            </div>

                             <div class="form-group row">
                                <label class="col-sm-2 form-control-label">Status</label>
                                <div class="col-sm-10">
                                    {{ Form::select('status', array('active' => 'Active', 'inactive' => 'Inactive'), $status , ['class' => 'form-control' ]) }}
                                </div>
                            </div>
                        
                            <div class="form-group row {{ $errors->has('level_1') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Commission Level 1(%)<span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('level_1', $level[1], ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'level_1' , 'placeholder' => 'commission level 1']) }}
                                    @if ($errors->has('level_1'))
                                    <span class="parsley-required">{{ $errors->first('level_1') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('level_2') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Commission Level 2(%)<span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('level_2', $level[2], ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'level_2' , 'placeholder' => 'commission level 2']) }}
                                    @if ($errors->has('level_2'))
                                    <span class="parsley-required">{{ $errors->first('level_2') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('level_3') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Commission Level 3(%)<span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('level_3', $level[3], ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'level_3' , 'placeholder' => 'commission level 3']) }}
                                    @if ($errors->has('level_3'))
                                    <span class="parsley-required">{{ $errors->first('level_3') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('level_4') ? 'has-danger' : '' }}">
                                <label class="col-sm-2 form-control-label">Commission Level 4(%)<span class="parsley-required">*</span></label>
                                <div class="col-sm-10">
                                    {{ Form::text('level_4', $level[4], ['class' => 'form-control col-md-7 col-xs-12' ,'id' => 'level_4' , 'placeholder' => 'commission level 4']) }}
                                    @if ($errors->has('level_4'))
                                    <span class="parsley-required">{{ $errors->first('level_4') }}</span>
                                    @endif
                                </div>
                            </div>

                             <div class="dker p-a text-right">
                                <a href="{{ URL('admin/'.$page_name) }}" class="btn btn-fw info">Cancel</a>
                                <button type="submit" class="btn btn-fw primary"><i class="fa fa-location-arrow"></i>&nbsp;{{ $btn_status }}</button>
                            </div> 
                        {!! Form::close() !!}
                        @else
                        

                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>Plan Name</td>
                                    <th>{{ ucfirst($plan->plan_name) }}</th>
                                </tr>
                                <tr>
                                    <td>Nature of Plan</td>
                                    <th>{{ $natureOfPlan[$plan->nature_of_plan] }}</th>
                                </tr>
                                <tr>
                                    <td>Minimum Amount ($)</td>
                                    <th>$ {{ number_format($plan->spend_min_amount,2) }}</th>
                                </tr>
                                <tr>
                                    <td>Maximum Amount ($)</td>
                                    <th>$ {{ number_format($plan->spend_max_amount,2) }}</th>
                                </tr>
                                <tr>
                                    <td>Loan Interest Payment (%) </td>
                                    <th>{{ $plan->profit ? $plan->profit : '-' }} %</th>
                                </tr>
                                <tr>
                                    <td>Loan Interest Payment Period</td>
                                    <th>{{ $paymentPeriods[$plan->interest_period_type] }}</th>
                                </tr>
                                <tr>
                                    <td>Does this plan end ?</td>
                                    <th> @if($plan->plan_status == 1) Yes @else No @endif</th>
                                </tr>
                                @if($plan->plan_status == 1)
                                <tr>
                                    <td>Duration</td>
                                    <th>{{ intval($plan->duration) }} {{ $paymentPeriods1[$plan->duration_time] }}</th>
                                </tr>
                                @endif
                                <tr>
                                    <td>Status</td>
                                    <th>
                                        @if($plan->status == 'active')
                                            <span class="text-success" title="{{ ucfirst($plan->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($plan->status) }}</span>
                                        @else
                                            <span class="text-danger" title="{{ ucfirst($plan->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($plan->status) }}</span>
                                        @endif
                                    </th>
                                </tr>

                                <tr>
                                    <td>Founder</td>
                                    <th>@if($plan->founder == 1) Yes @else -  @endif</th>
                                </tr>

                                <tr>
                                    <td>New Founder</td>
                                    <th>@if($plan->new_founder == 1) Yes @else -  @endif</th>
                                </tr>

                                <tr>
                                    <td>Created Date</td>
                                    <th title="{{ $plan->created_at->diffForHumans() }}">{{ $plan->created_at->toDayDateTimeString() }}</th>
                                </tr>
                                <tr>
                                    <td>Modified Date</td>
                                    <th title="{{ dispayTimeStamp($plan->updated_at)->diffForHumans() }}">{{ dispayTimeStamp($plan->updated_at)->toDayDateTimeString() }}</th>
                                </tr>
                            </tbody>
                        </table>

                    <div class="box-divider m-a-0"></div>
                    <br>
                    <h5>Level Commissions</h5>
                    <table class="table table-hover">
                    <thead>
                    <tr>
                    <th>Level</th>
                    <th>Commission (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($planLevels) > 0)
                        @foreach ($planLevels as $planLevel)
                            <tr>
                            <td nowrap="nowrap">{{ App\myCustome\myCustome::addOrdinalNumberSuffix($planLevel->level) }} Level</td>
                                <td>{{ number_format($planLevel->commision,2) }} %</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    </table>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
