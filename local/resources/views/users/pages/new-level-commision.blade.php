<?php 
$page_name = 'Level Commision';
$primary_key = 'comid';
?>
@extends('admin.parts.layout')  
@section('adminTitle', $mode.' '.ucfirst($page_name))
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">

            <div class="row p-a">
            <div class="col-md-6 text-left row col-xs-10">
                <div class="box-header">
                    <h2>{{ $mode.' '.ucfirst($page_name) }}</h2>
                </div>
            </div>
            @if($mode == 'View')
            <div class="col-md-6 text-right col-xs-2">
                <a class="md-btn md-fab m-b-sm blue" href="{{ url('user/level-commision') }}" title="Back to {{ ucwords($page_name) }}s"><i class="material-icons">&#xE15E;</i></a>
            </div>
            @endif
        </div>
            <div class="box-divider m-a-0"></div>
                <div class="box-body">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td>Username</td>
                                <th>{{ $levelCommision->username }}</th>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <th>@if(Auth::user()->id == $levelCommision->refernceid) {{ ucfirst($levelCommision->first_name) }} {{ ucfirst($levelCommision->last_name) }} @else - @endif</th>
                            </tr>
                            <tr>
                                <td>Commision</td>
                                <th>$ {{ number_format($levelCommision->commission,2) }}</th>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <th>
                                    @if($levelCommision->status == 'approved')
                                        <span class="text-success" title="{{ ucfirst($levelCommision->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($levelCommision->status) }}</span>
                                    @elseif($levelCommision->status == 'pending')
                                        <span class="text-danger" title="{{ ucfirst($levelCommision->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($levelCommision->status) }}</span>
                                    @else
                                        <span class="text-info" title="{{ ucfirst($levelCommision->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($levelCommision->status) }}</span>
                                    @endif
                                </th>
                            </tr>
                            <tr>
                                <td>Created Date</td>
                                <th title="{{ $levelCommision->created_at->diffForHumans() }}">{{ $levelCommision->created_at->toDayDateTimeString() }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
