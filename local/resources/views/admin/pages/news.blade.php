<?php
$page_name = 'news';
$allRecord = $news;
$primary_key = 'newsid';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
    <div class="padding">
        <div class="box">
            <div class="row p-a">
                <div class="col-md-6 text-left row">
                    <div class="box-header">
                        <h2>{{ ucwords($page_name) }}</h2>
                    </div>
                </div>
                <div class="col-md-6 text-right">
                    <a class="md-btn md-fab m-b-sm blue" href="{{ URL('admin/'.$page_name.'/new') }}" title="Add {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE145;</i></a>
                    <a class="md-btn md-fab m-b-sm indigo hidden" id="linkSearchMaster" href="javascript:void(0);" title="Search {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE8B6;</i></a>
                    <a class="md-btn md-fab m-b-sm pink hidden" href="#" title="Delete {{ ucfirst($page_name) }}"><i class="material-icons md-24">&#xE872;</i></a>
                </div>
            </div>

            <div class="box-divider m-a-0"></div>
            <div class="table-responsive">
                <table class="table table-striped b-t">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>News Header</th>
                        <th>News Description</th>
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
                                <td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="{{ $singleRecord->news_header }}" >{{ str_limit($singleRecord->news_header,60) }}</a></td>
                                <td>{{ str_limit($singleRecord->excerpt,20) }}</td>
                                <td>
                                @if($singleRecord->status == 'active')
                                <span class="text-success" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($singleRecord->status) }}</span>
                                @else
                                <span class="text-danger" title="{{ ucfirst($singleRecord->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($singleRecord->status) }}</span>
                                @endif
                                </td>
                                <td title="{{ dispayTimeStamp($singleRecord->updated_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->updated_at)->toDayDateTimeString() }}</td>
                                <td><a href="{{ URL('admin/'.$page_name.'/edit/'.$primary_column.' ') }}" data-placement="left" title="Edit Record"><i class="material-icons">&#xE254;</i></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:void(0)" class="{{ $primary_table_col_del }}" data-id="{{ $primary_column }}" data-pid="{{ $page_name }}" title="Delete Record"><i class="material-icons">&#xE872;</i></a></td>
                            </tr>
                            <?php $a++; ?>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="7">No Records !</td>
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