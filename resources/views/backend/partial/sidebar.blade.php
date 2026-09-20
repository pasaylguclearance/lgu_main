<nav id="sidebar" class="sidebar">
        <a class="sidebar-brand" style="text-align: center;">
            <img src="/img/logo.png" alt="" height="75px" style="margin-right: 20px;" onclick = "pnplogo()"/>
            <img src="/img/pasay-police.png" alt="" height="75px" onclick = "pasaylogo()"/>
            <span></span>
        </a>
        <div class="sidebar-content">
            <div class="sidebar-user">
                <img src="{{ asset('img/default.png') }}" class="img-fluid rounded-circle mb-2" alt="Linda Miller" />
                <div class="font-weight-bold">PO1 Jerome Mark Padilla</div>
                <small>Super Admin</small>
            </div>
            <ul class="sidebar-nav">
                @if (Auth::user()->status == 1)
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ url('dashboard')}}">
                            <i class="align-middle mr-2 fas fa-fw fa-user-secret"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>
                @else
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ url('dashboard/record')}}">
                            <i class="align-middle mr-2 fas fa-fw fa-user-secret"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>
                @endif
                
                <li class="sidebar-item">
                    <a href="#registration" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle mr-2 fas fa-fw fa-file-alt"></i> <span class="align-middle">Registrations</span>
                    </a>
                    <ul id="registration" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('new_application') }}">Add Applicant</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('application/detail') }}">Applicant Other Detail</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('application/printable-form') }}">Printable Form</a></li>
                        {{-- <li class="sidebar-item"><a class="sidebar-link" href="{{ url('application') }}">View Applications</a></li> --}}
                        @if (Auth::user()->status == 1)
                            <li class="sidebar-item"><a class="sidebar-link" href="{{ url('application/completed') }}">Printing Applications</a></li>
                        @else
                            <li class="sidebar-item"><a class="sidebar-link" href="{{ url('application/completed/record') }}">Printing Applications</a></li>
                        @endif
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('payment/process') }}">
                        <i class="align-middle mr-2 fas fa-fw fa-user-secret"></i> <span class="align-middle">Payment</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{'hit-verification'}}">
                        <i class="align-middle mr-2 fas fa-fw fa-user-secret"></i> <span class="align-middle">Hit Verification</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ url('user')}}">
                        <i class="align-middle mr-2 fas fa-fw fa-users"></i> <span class="align-middle">Users</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#maintenance" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle mr-2 fas fa-fw fa-cog"></i> <span class="align-middle">Maintenance</span>
                    </a>
                    <ul id="maintenance" class="sidebar-dropdown list-unstyled collapse " data-parent="#sidebar">
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('purpose') }}">Clearance Purpose</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('municipality') }}">Municipality</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('nationality') }}">Nationality</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ url('religion') }}">Religion</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
