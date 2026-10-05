<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link font-weight-bold text-dark">
                <i class="fas fa-hospital-alt text-primary mr-1"></i>
                {{ $currentOrg?->name ?? 'Apex Endoscopy & Diagnostic Center' }}
            </span>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Quick Action: New Patient -->
        @can('patient.create')
            <li class="nav-item mr-2 d-none d-md-inline-block">
                <a href="{{ route('patients.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-user-plus mr-1"></i> New Patient
                </a>
            </li>
        @endcan

        <!-- Quick Action: New Examination -->
        @can('examination.create')
            <li class="nav-item mr-3 d-none d-md-inline-block">
                <a href="{{ route('examinations.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-procedures mr-1"></i> New Examination
                </a>
            </li>
        @endcan

        <!-- Current User Dropdown -->
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                <div class="user-avatar-sm mr-2 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: bold; font-size: 13px;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="d-none d-md-inline font-weight-semibold text-dark">
                    {{ Auth::user()->name ?? 'Clinical User' }}
                </span>
                <i class="fas fa-angle-down ml-2 text-muted" style="font-size: 12px;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow border-0">
                <!-- User image -->
                <li class="user-header bg-dark text-white text-center p-3">
                    <div class="rounded-circle bg-primary text-white mx-auto d-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <p class="mb-0 font-weight-bold">
                        {{ Auth::user()->name }}
                    </p>
                    <small class="text-light">
                        {{ Auth::user()->designation ?? 'Medical Staff' }}
                    </small>
                    <div class="mt-1">
                        @foreach(Auth::user()->roles as $r)
                            <span class="badge badge-info">{{ $r->name }}</span>
                        @endforeach
                    </div>
                </li>
                <!-- Menu Body -->
                <li class="user-body p-2 border-bottom">
                    <a href="{{ route('profile.show') }}" class="dropdown-item py-2">
                        <i class="fas fa-user-circle mr-2 text-primary"></i> My Profile
                    </a>
                    <a href="{{ route('profile.password') }}" class="dropdown-item py-2">
                        <i class="fas fa-key mr-2 text-warning"></i> Change Password
                    </a>
                    @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('settings.index') }}" class="dropdown-item py-2">
                            <i class="fas fa-cogs mr-2 text-secondary"></i> Organization Settings
                        </a>
                    @endif
                </li>
                <!-- Menu Footer-->
                <li class="user-footer p-2 text-right">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-block btn-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
