<?php 
$page_name 		= 'Plan Change Request';
$allRecord 		= $depositPlanChanges;
$primary_key 	= 'depo_plan_chng';

$primary_column = 0;
$primary_table_row = $page_name.'_table_row';
$primary_table_col_del = 'table-col-del';
$singleRecord = "";
$clsData1  = array('green-700','indigo','pink','purple','blue','cyan','teal','green','lime','success','deep-orange-500','orange-500','amber-500','yellow-500','brown-800');
?>
@extends('admin.parts.layout')
@section('adminTitle', ucwords($page_name))
@section('adminBody')
<div class="padding">
	<div class="box">
		<div class="row p-a">
			<div class="col-md-12 text-left row col-xs-12">
				<div class="box-header">
					<h2>{{ ucwords($page_name) }}</h2>
				</div>
			</div>
		</div>

		<div class="box-divider m-a-0"></div>
		<br>
		<div class="col-md-12" style="background: #FFF;">
        <div class="tab-content">      
          <div class="tab-pane p-v-sm active" id="tab_1">
            <div class="streamline b-l m-b m-l">
             
             @foreach($allRecord as $singleRecord)

            @if(Auth::user()->hasRole('user'))
				<?php 
				$mainUrl = url('user/deposit/view/'.$singleRecord->depositid.'');
				$userurl = '<a href="'.url('admin/user/profile').'" class="text-info"> You</a>';
				$nm = Auth::user()->first_name;
				 ?>
				@else
				<?php 
				$mainUrl = url('admin/loan/view/'.$singleRecord->depositid.'');
				$userurl = '<a href="'.url('admin/user/view/'.$singleRecord->id.'').'" class="text-info">'.ucfirst($singleRecord->first_name).' '.ucfirst($singleRecord->last_name).'</a>';
				$nm = $singleRecord->first_name;
				 ?>
			@endif

              <div class="sl-item">
                <div class="sl-left">
                <?php  $random_keys = array_rand($clsData1,1); $clsLife = $clsData1[$random_keys]; ?>
                  <span class="w-40 avatar {{ $clsLife }}">
                  <span>{{ substr($nm, 0,1) }}</span>
                  <i class="on b-white bottom"></i>
                </span>
                </div>
                <div class="sl-content">
                  <div class="sl-date text-muted" title="{{ dispayTimeStamp($singleRecord->created_at)->diffForHumans() }}">{{ dispayTimeStamp($singleRecord->created_at)->toDayDateTimeString() }}</div>
                  <p>{!! $userurl !!} @if($singleRecord->status == 'approved') <span class="text-success">requested for plan change from</span>  @else <span class="text-danger">cancelled plan change request from</span> @endif </p>
                  <blockquote>
                  <p><a href="{{ $mainUrl }}" class="text-primary" title="Deposit Id"> <b>{{ $singleRecord->depositno }}</b></a> | <a href="{{ $mainUrl }}" class="text-info" title="Old Plan Name">{{ $singleRecord->old_plan_name }}</a> <i class="material-icons">&#xE5C8;</i> <a href="javascript:void(0)" title="New Plan Name" class="text-info">@if($singleRecord->status == 'approved') {{ $singleRecord->new_plan_name }} @else <del title="Cancelled">{{ $singleRecord->new_plan_name }}</del> @endif</a></p>
                  </blockquote>
                </div>
              </div>
              @endforeach

            </div>
          </div>
        </div>
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