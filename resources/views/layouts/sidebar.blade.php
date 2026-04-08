<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex align-items-center" href="{{ url('/') }}">
            <div class="bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fas fa-solid fa-timeline text-dark text-xl" aria-hidden="true"></i>
            </div>
            <span class="ms-1 font-weight-bold text-lg">Timecard</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md {{ Route::is('dashboard') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-home {{ Route::is('dashboard') ? 'text-white' : 'text-dark' }}"
                            aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('profile.index') ? 'active' : '' }}"
                    href="{{ route('profile.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md {{ Route::is('profile.index') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-user {{ Route::is('profile.index') ? 'text-white' : 'text-dark' }}"
                            aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">My Profile</span>
                </a>
            </li>

            @hasanyrole('superadmin|admin')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Users</h6>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#usersDropdown"
                        class="nav-link {{ Request::is('users/*') ? 'active' : '' }}" aria-controls="usersDropdown"
                        role="button" aria-expanded="false">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('users/*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-users {{ Request::is('users/*') ? 'text-white' : 'text-dark' }}"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Manage Users</span>
                        {{-- <i class="fas fa-chevron-down ms-auto text-xs opacity-6" aria-hidden="true"></i> --}}
                    </a>
                    <div class="collapse {{ Request::is('users/*') ? 'show' : '' }}" id="usersDropdown">
                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('users/admin*') ? 'active' : '' }}"
                                    href="{{ route('admin.index') }}">
                                    <span class="sidenav-normal"> Admin </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::is('users/employee*') ? 'active' : '' }}"
                                    href="{{ route('employee.index') }}">
                                    <span class="sidenav-normal"> Employees </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endhasanyrole

            @hasanyrole('superadmin|admin')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Client</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('client*') ? 'active' : '' }}" href="{{ route('client.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('client*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-briefcase {{ Request::is('client*') ? 'text-white' : 'text-dark' }}"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Clients List</span>
                    </a>
                </li>
            @endhasanyrole

            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Management</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('projects*') ? 'active' : '' }}"
                    href="{{ route('projects.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('projects*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-folder {{ Request::is('projects*') ? 'text-white' : 'text-dark' }}"
                            aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Projects</span>
                </a>
            </li>

            @hasanyrole('superadmin|admin|employee')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tasks*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('tasks*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-tasks {{ Request::is('tasks*') ? 'text-white' : 'text-dark' }}"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Tasks</span>
                    </a>
                </li>
            @endhasanyrole

            <li class="nav-item">
                <a class="nav-link {{ Request::is('daily-updates*') ? 'active' : '' }}"
                    href="{{ route('daily-updates.index') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('daily-updates*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="fas fa-calendar-alt {{ Request::is('daily-updates*') ? 'text-white' : 'text-dark' }}"
                            aria-hidden="true"></i>
                    </div>
                    <span class="nav-link-text ms-1">Daily Updates</span>
                </a>
            </li>
            @hasanyrole('superadmin|admin')
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Reports</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('reports/employees*') ? 'active' : '' }}"
                        href="{{ route('reports.employees') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('reports/employees*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line {{ Request::is('reports/employees*') ? 'text-white' : 'text-dark' }}"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Employee Reports</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ Request::is('reports/tasks*') ? 'active' : '' }}"
                        href="{{ route('reports.tasks') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md {{ Request::is('reports/tasks*') ? 'bg-gradient-primary' : 'bg-white' }} text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-clipboard-list {{ Request::is('reports/tasks*') ? 'text-white' : 'text-dark' }}"
                                aria-hidden="true"></i>
                        </div>
                        <span class="nav-link-text ms-1">Employee Tasks</span>
                    </a>
                </li> --}}
            @endhasanyrole
        </ul>
    </div>
</aside>
