<style>
    .navbar{
        /* background-color: #3c8dbc; */
        background: linear-gradient(to top left, #2d79a3 0%, #223373 150%);
    }
    #navSelect:hover, #navbarDropdown:hover{
        color:white;
        /* background-color:red; */
    }
    #dropdownSettings a:hover{
        background: linear-gradient(to top left, #2d79a3 10%, #223373 150%) !important;
        color:white !important;

    }
</style>

<nav class="navbar navbar-expand-md navbar-dark navbar-laravel">
    <a class="navbar-brand" href="{{ url('/') }}">
        {{ config('app.name', 'Laravel ') }}
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <!-- @php
                $time_rec = ""; 
                $overtime_rec = "";   
                $leave_rec = ""; 
                $worksched = "";   
                $teamstatus = ""; 
                $payslips = "";   
            @endphp

            @if(Request::segment(1) == 'timerecords')
                @php
                    $time_rec = "nav-link active";
                    $overtime_rec = "nav-link";   
                    $leave_rec = "nav-link"; 
                    $worksched = "nav-link";   
                    $teamstatus = "nav-link"; 
                    $payslips = "nav-link";
                @endphp
            @elseif(Request::segment(1) == 'overtimerecords')
                @php
                    $overtime_rec = "nav-link active";   
                    $time_rec = "nav-link"; 
                    $leave_rec = "nav-link"; 
                    $worksched = "nav-link";   
                    $teamstatus = "nav-link"; 
                    $payslips = "nav-link";   
                @endphp
            @elseif(Request::segment(1) == 'leaverecords')
                @php
                    $leave_rec = "nav-link active";   
                    $overtime_rec = "nav-link"; 
                    $time_rec = "nav-link"; 
                    $worksched = "nav-link";   
                    $teamstatus = "nav-link"; 
                    $payslips = "nav-link";   
                @endphp
            @elseif(Request::segment(1) == 'workschedules')
                @php
                    $worksched = "nav-link active";   
                    $overtime_rec = "nav-link"; 
                    $time_rec = "nav-link"; 
                    $leave_rec = "nav-link"; 
                    $teamstatus = "nav-link"; 
                    $payslips = "nav-link";   
                @endphp
            @elseif(Request::segment(1) == 'teamstatus')
                @php
                    $teamstatus = "nav-link active";   
                    $overtime_rec = "nav-link"; 
                    $time_rec = "nav-link"; 
                    $leave_rec = "nav-link"; 
                    $worksched = "nav-link";   
                    $payslips = "nav-link";   
                @endphp
            @elseif(Request::segment(1) == 'payslips')
                @php
                    $payslips = "nav-link active"; 
                    $overtime_rec = "nav-link"; 
                    $time_rec = "nav-link"; 
                    $leave_rec = "nav-link"; 
                    $worksched = "nav-link";   
                    $teamstatus = "nav-link";   
                @endphp
            @else
                @php
                    $overtime_rec = "nav-link"; 
                    $time_rec = "nav-link"; 
                    $leave_rec = "nav-link"; 
                    $worksched = "nav-link";   
                    $teamstatus = "nav-link"; 
                    $payslips = "nav-link";   
                @endphp
            @endif -->

            <!-- @if(Session::get('time_records') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$time_rec}}" href="/timerecords">Time Records</a>
            </li>
            @endif

            @if(Session::get('overtime_records') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$overtime_rec}}" href="/overtimerecords">Overtime Records</a>
            </li>
            @endif

            @if(Session::get('leave_records') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$leave_rec}}" href="/leaverecords">Leave Records</a>
            </li>
            @endif

            @if(Session::get('work_schedules') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$worksched}}" href="/workschedules">Work Schedules</a>
            </li>
            @endif

            @if(Session::get('team_status') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$teamstatus}}" href="/teamstatus">Team Status</a>
            </li>
            @endif

            @if(Session::get('payslips') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$payslips}}" href="/payslips">Payslips</a>
            </li>
            @endif

            @if(Session::get('approvals') != 'none')
            <li class="nav-item">
                <a id="navSelect" class="{{$payslips}}" href="/approvals">Approvals</a>
            </li>
            @endif -->

            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Employee Forms<span class="caret"></span></a>
                <div id="dropdownSettings" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    @if(Session::get('time_records') != 'none')
                        <a class="dropdown-item" href="/timerecords">Time Records</a>
                    @endif
                    @if(Session::get('overtime_records') != 'none')
                        <a class="dropdown-item" href="/overtimerecords">Overtime Records</a>
                    @endif
                    @if(Session::get('leave_records') != 'none')
                        <a class="dropdown-item" href="/">Leave Records</a>
                    @endif
                    @if(Session::get('work_schedules') != 'none')
                        <a class="dropdown-item" href="/">Work Schedules</a>
                    @endif
                    @if(Session::get('team_status') != 'none')
                        <a class="dropdown-item" href="/">Team Status</a>
                    @endif
                    @if(Session::get('payslips') != 'none')
                        <a class="dropdown-item" href="/">Payslips</a>
                    @endif
                <div>
            </li>

            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Approvals<span class="caret"></span></a>
                <div id="dropdownSettings" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/">Pending Approvals</a>
                    <a class="dropdown-item" href="/">Alteration History</a>
                    <a class="dropdown-item" href="/">Overtime History</a>
                    <a class="dropdown-item" href="/">Leave History</a>
                    <a class="dropdown-item" href="/">Work Schedules History</a>
                    <a class="dropdown-item" href="/">Tardiness History</a>
                <div>
            </li>
            
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>Admin Settings<span class="caret"></span></a>
                <div id="dropdownSettings" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/register">Create User</a>
                    <a class="dropdown-item" href="/createusertype">Create User Type</a> 
                    <a class="dropdown-item" href="/addalteration">Add Alteration</a>
                    <a class="dropdown-item" href="/addovertime">Add Overtime</a>
                    <a class="dropdown-item" href="/addleave">Add Leave</a>
                    <a class="dropdown-item" href="/addworkschedule">Add Work Schedule</a>
                    <a class="dropdown-item" href="/importbiometrics">Import Biometrics</a>
                <div>
            </li>
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><img class="navbar-img" src="/images/2x2.jpg" alt="User Image"></i>{{ Auth::user()->name }} <span class="caret"></span></a>
                    <div id="dropdownSettings" class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/myprofile"><i class="icon-right fa fa-user-circle" aria-hidden="true"></i>My Profile</a>
                        <a class="dropdown-item" href="/myschedule"><i class="icon-right fa fa-calendar-check-o" aria-hidden="true"></i>My Schedule</a>
                        <a class="dropdown-item" href="/myleaves"><i class="icon-right fa fa-calendar-times-o" aria-hidden="true"></i>My Leaves</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out" aria-hidden="true" style="margin-right:5px"></i>{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
                <li class="nav-item">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre><i class="icon-right fa fa-bell"></i>Notifications</a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Notifications</a>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>