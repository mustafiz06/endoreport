<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link d-flex align-items-center px-3 py-2">
        <div class="brand-image d-flex align-items-center justify-content-center bg-primary rounded mr-2" style="width: 34px; height: 34px;">
            <i class="fas fa-stethoscope text-white"></i>
        </div>
        <span class="brand-text font-weight-bold text-white text-truncate" title="EndoCapture Pro">
            EndoCapture <span class="text-info font-weight-normal">Pro</span>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line text-info"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- PATIENT MANAGEMENT -->
                <li class="nav-header">PATIENT MANAGEMENT</li>
                @can('patient.view')
                    <li class="nav-item">
                        <a href="{{ route('patients.index') }}" class="nav-link {{ request()->routeIs('patients.*') && !request()->routeIs('patient-history.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-injured"></i>
                            <p>Patients</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('patient-history.index') }}" class="nav-link {{ request()->routeIs('patient-history.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history text-cyan"></i>
                            <p>Patient History</p>
                        </a>
                    </li>
                @endcan

                <!-- MEDICAL MANAGEMENT -->
                <li class="nav-header">MEDICAL MANAGEMENT</li>
                @can('doctor.view')
                    <li class="nav-item">
                        <a href="{{ route('doctors.index') }}" class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-md"></i>
                            <p>Doctors</p>
                        </a>
                    </li>
                @endcan
                @can('referring_doctor.view')
                    <li class="nav-item">
                        <a href="{{ route('referring-doctors.index') }}" class="nav-link {{ request()->routeIs('referring-doctors.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tag"></i>
                            <p>Referring Doctors</p>
                        </a>
                    </li>
                @endcan
                @can('procedure.view')
                    <li class="nav-item">
                        <a href="{{ route('procedures.index') }}" class="nav-link {{ request()->routeIs('procedures.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-procedures"></i>
                            <p>Procedures</p>
                        </a>
                    </li>
                @endcan
                @can('instrument.view')
                    <li class="nav-item">
                        <a href="{{ route('instruments.index') }}" class="nav-link {{ request()->routeIs('instruments.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-microscope"></i>
                            <p>Instruments</p>
                        </a>
                    </li>
                @endcan

                <!-- EXAMINATION -->
                <li class="nav-header">EXAMINATION</li>
                @can('examination.view')
                    <li class="nav-item">
                        <a href="{{ route('examinations.index') }}" class="nav-link {{ request()->routeIs('examinations.index') || request()->routeIs('examinations.show') || request()->routeIs('examinations.edit') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-notes-medical"></i>
                            <p>Examinations</p>
                        </a>
                    </li>
                @endcan
                @can('examination.create')
                    <li class="nav-item">
                        <a href="{{ route('examinations.create') }}" class="nav-link {{ request()->routeIs('examinations.create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-plus-circle text-success"></i>
                            <p>New Examination</p>
                        </a>
                    </li>
                @endcan

                <!-- REPORTING -->
                <li class="nav-header">REPORTING</li>
                @can('report.view')
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-medical-alt"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('report-templates.index') }}" class="nav-link {{ request()->routeIs('report-templates.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>Report Templates</p>
                        </a>
                    </li>
                @endcan

                <!-- ADMINISTRATION -->
                @if(Auth::user()->isSuperAdmin() || Auth::user()->hasAnyPermission(['user.view', 'role.view', 'setting.view', 'audit.view']))
                    <li class="nav-header">ADMINISTRATION</li>
                    @can('user.view')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    @endcan
                    @can('role.view')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shield-alt"></i>
                                <p>Roles & Permissions</p>
                            </a>
                        </li>
                    @endcan
                    @can('setting.view')
                        <li class="nav-item">
                            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hospital-user"></i>
                                <p>Organization Settings</p>
                            </a>
                        </li>
                    @endcan
                    @can('audit.view')
                        <li class="nav-item">
                            <a href="{{ route('audit.index') }}" class="nav-link {{ request()->routeIs('audit.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-list text-warning"></i>
                                <p>Audit Log</p>
                            </a>
                        </li>
                    @endcan
                @endif

            </ul>
        </nav>
    </div>
</aside>
