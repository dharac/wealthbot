@extends('admin.parts.layout')
@section('adminTitle', ucfirst($profile->first_name).' '.ucfirst($profile->last_name).' | Profile')
@section('adminBody')
<div class="padding">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="row p-a">
                    <div class="col-md-6 col-xs-6 text-left row">
                        <div class="box-header">
                            <h2>Profile</h2>
                        </div>
                    </div>
                <div class="col-md-6 col-xs-6 text-right">
                    <a class="md-btn md-fab m-b-sm blue" href="{{ url('admin/user/profile/update') }}" title="Edit Profile"><i class="material-icons">&#xE254;</i></a>
                </div>
                </div>
                <div class="box-divider m-a-0"></div>
                <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>First Name</td>
                                    <th>{{ ucfirst($profile->first_name) }}</th>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <th>{{ $profile->last_name ? ucfirst($profile->last_name) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <th>{{ $profile->username }}</th>
                                </tr>
                                <tr>
                                    <td>Referrer</td>
                                    <th>
                                        @if($profile->ufirst_name != "" || $profile->ulast_name != "")
                                        {{ ucfirst($profile->ufirst_name) }} {{ ucfirst($profile->ulast_name) }}
                                        @else
                                            {{ config('services.SITE_DETAILS.SITE_NAME') }}
                                        @endif
                                    </th>
                                </tr>
                                
                                <tr>
                                    <td>Email Address</td>
                                    @if($profile->confirmed == '1')
                                    <td class="text-success" title="Email Verified" nowrap="nowrap"><i class="material-icons">&#xE8E8;</i> {{ $profile->email }}</td>
                                    @else
                                    <td class="text-danger" title="Email Not Verified" nowrap="nowrap"><i class="material-icons">&#xE8AE;</i> {{ $profile->email }}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Cell Phone</td>
                                    <th>@if($profile->phone != "") {{ $profile->cou_code }} {{  $profile->phone }} @else -  @endif</th>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <th>{{ $profile->gender ? ucfirst($profile->gender) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Date of Birth (MM/DD/YYYY)</td>
                                    <?php 
                                    $dob = "-";
                                    if($profile->dob != null || $profile->dob != "")
                                    {
                                        $dob    = Carbon\Carbon::parse($profile->dob)->format('Y-m-d');
                                        $dob    = Carbon\Carbon::createFromFormat('Y-m-d', $dob);
                                        $dob    = $dob->format('m/d/Y');
                                    }
                                    ?>
                                    <th>{{ $dob }}</th>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <th>{{ $profile->address ? $profile->address : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <th>{{ $profile->city ? ucwords($profile->city) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>State</td>
                                    <th>{{ $profile->state ? ucwords($profile->state) : '-' }}</th>
                                </tr>

                                <tr>
                                    <td>Country</td>
                                    <th>{{ $profile->counm ? ucwords($profile->counm) : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>ZIP</td>
                                    <th>{{ $profile->zip ? $profile->zip : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Bitcoin Wallet Address</td>
                                    <th>{{ $profile->bitcoin_id ? $profile->bitcoin_id : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Security Question</td>
                                    <th>{{ $profile->question ? $profile->question : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Security Question Answer</td>
                                    <th>{{ $profile->sec_answer ? $profile->sec_answer : '-' }}</th>
                                </tr>
                                <tr>
                                    <td>Founder</td>
                                    <th>@if($profile->founder == 1) Yes @else - @endif</th>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <th>
                                    @if($profile->status == 'active')
                                        <span class="text-success" title="{{ ucfirst($profile->status) }}"><i class="material-icons">&#xE877;</i> {{ ucfirst($profile->status) }}</span>
                                    @elseif($profile->status == 'locked')
                                        <span class="text-danger" title="{{ ucfirst($profile->status) }}"><i class="material-icons">&#xE897;</i> {{ ucfirst($profile->status) }}</span>
                                    @else
                                        <span class="text-info" title="{{ ucfirst($profile->status) }}"><i class="material-icons">&#xE88F;</i> {{ ucfirst($profile->status) }}</span>
                                    @endif
                                </th>
                                </tr>
                                @if(Auth::user()->hasRole('user'))
                                <tr>
                                    <td>Referral URL</td>
                                    <th><a href="{{ url('track/'.$profile->username) }}" class="text-info" target="_blank">{{ url('track/'.$profile->username) }}</a></th>
                                </tr>
                                @endif
                                <tr>
                                    <td>Created Date</td>
                                    <th>{{ dispayTimeStamp($profile->created_at)->toDayDateTimeString() }}</th>
                                </tr>
                                <tr>
                                    <td>Modified Date</td>
                                    <th>{{ dispayTimeStamp($profile->updated_at)->toDayDateTimeString() }}</th>
                                </tr>
                            </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="padding">
    <div class="box">
        <div class="row p-a">
            <div class="col-md-6 text-left row">
                <div class="box-header">
                    <h2>Last Login Details</h2>
                </div>
            </div>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="table-responsive">
            <table class="table table-striped b-t">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Device / OS / IP</th>
                        <th>Browser</th>
                        <th>Login Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($loginDetails) > 0)
                        <?php $a = 1; ?>
                        @foreach($loginDetails as $loginDetail)
                        <tr>
                            <td>{{ $a }}</td>
                            <td nowrap="nowrap">
                            @if($loginDetail->device == 'tablet')
                                <i class="material-icons">&#xE331;</i>&nbsp;&nbsp;{{ ucfirst($loginDetail->device) }}
                                @elseif($loginDetail->device == 'mobile')
                                <i class="material-icons">&#xE325;</i>&nbsp;&nbsp;{{ ucfirst($loginDetail->device) }}
                                @else
                                <i class="material-icons">&#xE31E;</i>&nbsp;&nbsp;{{ ucfirst($loginDetail->device) }}
                                @endif | {{ $loginDetail->os }} |
                                {{ $loginDetail->ip }} 
                            </td>
                            <td nowrap="nowrap">{{ $loginDetail->os }}</td>
                            <td nowrap="nowrap" title="{{ dispayTimeStamp($loginDetail->created_at)->diffForHumans() }}">{{ dispayTimeStamp($loginDetail->created_at)->toDayDateTimeString() }}</td>
                        </tr>
                        <?php $a++; ?>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="5">No Records !</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
