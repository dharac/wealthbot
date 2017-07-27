<div id="aside" class="app-aside modal fade sm nav-dropdown">
<div class="left navside black dk" layout="column">
<div class="navbar no-radius" style="padding: 5px !important ;">
    <a href="{{ url('dashboard') }}" title="Dashboard"><img src="{{ URL::asset('local/assets/images/logo.jpg') }}" width="100%"></a>
</div>
<div flex class="hide-scroll">
    <nav class="scroll nav-border b-primary">
        <ul class="nav" id="aside_admin_panel" ui-nav>
            <li class="nav-header hidden-folded"><small class="text-muted">Main</small></li>
            <li>
                <a href="{{ url('/dashboard') }}" ><span class="nav-icon"><i class="material-icons">&#xE88A;</i></span><span class="nav-text">Dashboard</span></a>
            </li>
            <li>
                <a href="{{ url('/admin/user/profile') }}" ><span class="nav-icon"><i class="material-icons">&#xE87C;</svg></i></span><span class="nav-text">Profile</span></a>
            </li>
            @if(Auth::user()->hasRole('user'))
            <li>
                <a href="{{ url('/wallet') }}" ><span class="nav-label"><b class="label label-sm danger">NEW</b></span><span class="nav-icon"><i class="material-icons">&#xE850;</i></span><span class="nav-text">Wallet</span></a>
            </li>
            <li>
                <a title="Deposit">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE862;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Deposit</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/deposit') }}"><span class="nav-text">Deposit History</span></a>
                        <a href="{{ url('user/deposit/new') }}"><span class="nav-text">Make Deposit</span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a title="Financials">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE3CA;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Financials</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/interest-payment') }}" ><span class="nav-text">Interest Payments</span></a>
                        <a href="{{ url('user/level-commision') }}" ><span class="nav-text">Commissions Earned</span></a>
                        <a href="{{ url('user/available-payout') }}" ><span class="nav-text">Available Payouts</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="Reports">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE14F;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Reports</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/ledger') }}" ><span class="nav-text">Ledger Summary</span></a>
                        <a href="{{ url('user/referral-report') }}"><span class="nav-text">Referrals Report</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8D4;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Withdrawal</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/withdraw') }}"><span class="nav-text">Withdrawal History</span></a>
                        <a href="{{ url('user/withdraw/new') }}"><span class="nav-text">Withdrawal</span></a>
                    </li>
                </ul>
            </li>
            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE335;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">My Referrals</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/referral') }}"><span class="nav-text">Referral</span></a>
                    </li>
                </ul>
            </li>


            <li>
                  <a>
                    <span class="nav-caret">
                      <i class="fa fa-caret-down"></i>
                    </span>
                    <span class="nav-label hidden-folded ticket-response-count">
                    </span>
                    <span class="nav-icon">
                     <i class="material-icons">&#xE625;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i>
                    </span>
                    <span class="nav-text">Support</span>
                  </a>
                  <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/ticket') }}"><span class="nav-text">Tickets History</span></a>
                        <a href="{{ url('user/ticket/new') }}"><span class="nav-text">Add Ticket</span></a>
                        <a href="{{ url('user/deposit/change/plan') }}"><span class="nav-text">Change Plan</span></a>
                        <a href="{{ url('user/deposit/history/plan') }}"><span class="nav-text">Change Plan History</span></a>
                    </li>
                </ul>
                </li>
            
            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE151;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Tell a Friend</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('user/invite-friend') }}"><span class="nav-text">Invite Your Friends</span></a>
                    </li>
                </ul>
            </li>
            @else
            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8F9;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Master</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/country') }}"><span class="nav-text">Country</span></a>
                        <a href="{{ url('admin/country/new') }}"><span class="nav-text">Add Country</span></a>
                        <a href="{{ url('/admin/question') }}"><span class="nav-text">Sec. Questions</span></a>
                        <a href="{{ url('admin/question/new') }}" ><span class="nav-text">Add Sec. Questions</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8B1;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Plan</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/plan') }}" ><span class="nav-text">Plans</span></a>
                        <a href="{{ url('/admin/plan/new') }}"><span class="nav-text">Add Plan</span>
                        <a href="{{ url('/admin/interest-variable') }}"><span class="nav-text">Add Interest</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="Private Loans">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8E5;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Loans</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/loan') }}" ><span class="nav-text">Private Loans</span></a>
                        <a href="{{ url('admin/ipn') }}"><span class="nav-text">IPN Request</span></a>
                        <a href="{{ url('admin/deposit/change/plan') }}"><span class="nav-text">Plan Change Request</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="Financials">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE3CA;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Financials</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/interest-payment') }}"><span class="nav-text">Interest Payments</span></a>
                        <a href="{{ url('admin/interest-payment/pending') }}"><span class="nav-text">Pending Interest Payments</span></a>
                        <a href="{{ url('admin/level-commision') }}"><span class="nav-text">Available Commissions</span></a>
                        <a href="{{ url('admin/level-commision/pending') }}"><span class="nav-text">Pending Commissions</span></a>
                        <a href="{{ url('admin/wallet-transaction') }}"><span class="nav-text">Wallet Transactions</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="Reports">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE14F;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Reports</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/ledger') }}"><span class="nav-text">Ledger Summary</span></a>
                        <a href="{{ url('admin/referral-report') }}"><span class="nav-text">Referrals Report</span></a>
                        <a href="{{ url('admin/payout-report') }}"><span class="nav-text">Payout Report</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8D4;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Withdrawal</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/withdraw') }}"><span class="nav-text">Withdrawal Request</span></a>
                    </li>
                </ul>
            </li>

            <!-- <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE89C;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">CMS</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/page') }}" ><span class="nav-text">Pages</span></a>
                        <a href="{{ url('/admin/page/new') }}" ><span class="nav-text">Add Page</span></a>
                        <a href="{{ url('/admin/menu') }}" ><span class="nav-text">Menu</span></a>
                        <a href="{{ url('/admin/menu/new') }}" ><span class="nav-text">Add Menu</span></a>
                    </li>
                </ul>
            </li> -->

            <li>
                <a title="Users">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE853;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Users</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/user') }}" ><span class="nav-text">Users</span></a>
                        <a href="{{ url('admin/user/new') }}" ><span class="nav-text">Add User</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="News">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8F0;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">News</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/news') }}"><span class="nav-text">News</span></a>
                        <a href="{{ url('admin/news/new') }}"><span class="nav-text">Add News</span></a>
                        <a href="{{ url('admin/newsletter') }}" class="hide"><span class="nav-text">Newsletter</span></a>
                        <a href="{{ url('admin/testimonial') }}" class="hide"><span class="nav-text">Testimonial</span></a>
                    </li>
                </ul>
            </li>

            <li class="hide">
                <a title="Sub Admin">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE851;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Sub Admin</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/sub-admin') }}"><span class="nav-text">Sub Admin</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="Settings">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE8A4;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Settings</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/setting') }}"><span class="nav-text">Site Settings</span></a>
                        <a href="{{ url('admin/coinpayment') }}"><span class="nav-text">Coinpayment</span></a>
                        <!-- <a href="{{ url('admin/getresponse') }}"><span class="nav-text">Getresponse</span></a> -->
                        <a href="{{ url('admin/google-capcha') }}"><span class="nav-text">Google Captcha</span></a>
                        <a href="{{ url('admin/mail-management') }}"><span class="nav-text">Mail Management</span></a>
                        <a href="{{ url('admin/sms-management') }}"><span class="nav-text">Sms Management</span></a>
                        <a href="{{ url('admin/database-backup') }}"><span class="nav-text">Database Backup</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a title="Settings">
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE616;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Logs</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/login-detail') }}" ><span class="nav-text">Login Details</span></a>
                        <a href="{{ url('admin/cron-job') }}" ><span class="nav-text">Cron Job</span></a>
                        <a href="{{ url('admin/sms') }}" ><span class="nav-text">SMS Log</span></a>
                    </li>
                </ul>
            </li>

            <li>
                <a>
                    <span class="nav-caret"><i class="fa fa-caret-down"></i></span>
                    <span class="nav-label hidden-folded"></span>
                    <span class="nav-icon"><i class="material-icons">&#xE625;<img src="{{ URL::asset('local/assets/images/admin/i_5.svg') }}" /></i></span>
                    <span class="nav-text">Support</span>
                </a>
                <ul class="nav-sub">
                    <li>
                        <a href="{{ url('admin/ticket') }}"><span class="nav-text">Tickets</span></a>
                        <a href="{{ url('admin/mass-email') }}"><span class="nav-text">Email</span></a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</nav>
</div>

    <div flex-no-shrink>
        <nav ui-nav>
            <ul class="nav">
                <li><div class="b-b b m-t-sm"></div></li>
                <li class="no-bg"><a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="nav-icon"><i class="material-icons">&#xe8ac;</i></span> <span class="nav-text">Logout</span></a></li>
            </ul>
        </nav>
    </div>
</div>
</div>
