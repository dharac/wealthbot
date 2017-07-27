<!-- content -->
<div id="content" class="app-content box-shadow-z2 box-radius-1x" role="main">
<div class="app-header white box-shadow">
   <div class="navbar">
       <!-- Open side - Naviation on mobile -->
       <a data-toggle="modal" data-target="#aside" class="navbar-item pull-left hidden-lg-up">
         <i class="material-icons">&#xe5d2;</i>
       </a>
       <div class="navbar-item pull-left h5" ng-bind="$state.current.data.title" id="pageTitle"></div>
       <ul class="nav navbar-nav pull-right">
         <li class="nav-item dropdown pos-stc-xs">
           <a class="nav-link" href="javascript:void(0);" href data-toggle="dropdown" id="info-message-notification">
             <i class="material-icons">&#xe7f5;</i>
             <span class="label label-sm up danger info-number-notification hide"></span>
           </a>
           <div class="dropdown-menu pull-right w-xl animated fadeInUp no-bg no-border no-shadow">
               <div class="scrollable" style="max-height: 300px">
                   <ul class="list-group list-group-gap m-a-0" id="info-message-notification-area">
                   </ul>
               </div>
           </div>
         </li>
         <li class="nav-item dropdown">
           <a class="nav-link clear" href="javascript:void(0)" data-toggle="dropdown" title="{{ Auth::user()->username }} | {{ Auth::user()->email }}">
             <span class="avatar w-32">
               <img src="{{ profilePicture() }}" alt="Profile Image" class="user-profile-picture">
               <i class="on b-white bottom"></i>
             </span>
           </a>
           <div class="dropdown-menu pull-right dropdown-menu-scale">
               <a class="dropdown-item" href="{{ url('admin/user/profile') }}"><i class="material-icons">&#xE851;</i> <span>{{ ucfirst(Auth::user()->first_name) }} | Edit Profile</span></a>
               <a class="dropdown-item" href="{{ url('admin/user/password') }}"><i class="material-icons">&#xE899;</i> <span> Change Password</span></a> 
               <a class="dropdown-item" href="{{ url('home') }}" target="_blank"><i class="material-icons">&#xE30C;</i> <span>View Site</span></a>
               <a class="dropdown-item change_profile_image"><i class="material-icons">&#xE3AF;</i> <span>Profile Picture</span></a>
               <div class="dropdown-divider"></div>
               <a class="dropdown-item" ui-sref="access.signin" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">&#xE8AC;</i> Sign out</a>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
                </form>
           </div>
         </li>
         <li class="nav-item hidden-md-up">
           <a class="nav-link" data-toggle="collapse" data-target="#collapse">
             <i class="material-icons">&#xe5d4;</i>
           </a>
         </li>
       </ul>
       
       <div class="collapse navbar-toggleable-sm" id="collapse">
          @if(!Auth::user()->hasRole('user'))
          {!! Form::open(array('url' => 'admin/user/search' , 'class' => 'navbar-form form-inline navbar-item m-l v-m pull-right','id' => 'searchUser', 'method' => 'GET')) !!}
              <div class="form-group l-h m-a-0">
                  <div class="input-group"><input type="text" class="form-control b-a form-control-sm" placeholder="Search Users..." name="q" id="topNavSearch" value="@if(isset($searchValue)) {{ $searchValue }} @endif" autocomplete="off">
                  <span class="input-group-btn"><button type="submit" class="btn btn-sm btn-default b-a no-shadow"><i class="fa fa-search"></i></button></span>
                  </div>
              </div>
          {!! Form::close() !!}
          @endif
          <div class="navbar-form form-inline navbar-item m-l v-m pull-right">
            <div id="google_translate_element" style="line-height: 0;padding-top: 18px;"></div>
          </div>
         <!-- link and dropdown -->
         <ul class="nav navbar-nav hide">
           <li class="nav-item dropdown">
             <a class="nav-link" href data-toggle="dropdown">
               <i class="fa fa-fw fa-plus text-muted"></i>
               <span>New</span>
             </a>
             <div class="dropdown-menu dropdown-menu-scale">
                <a class="dropdown-item" ui-sref="app.inbox.compose"><span>Inbox</span></a> <a class="dropdown-item" ui-sref="app.todo"><span>Todo</span></a> <a class="dropdown-item" ui-sref="app.note.list"><span>Note</span> <span class="label primary m-l-xs">3</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" ui-sref="app.contact">Contact</a>
            </div>
           </li>
         </ul>
       </div>
   </div>
</div>