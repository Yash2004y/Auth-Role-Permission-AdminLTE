        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{route('home')}}" class="brand-link">
                <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="{{ env('APP_NAME') }} Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{ auth()?->user()?->image ?? asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-1"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()?->name ?? '' }}</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link {{ isActiveRoute('home') }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>

                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        @hasAnyPermission('user-list','permission-list','role-list')
                        <li class="nav-header">User Management</li>
                        @can('user-list')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ isActiveRoute('users.index') }}">
                                <i class="nav-icon fas fa-solid fa-users"></i>
                                <p>
                                    Users
                                    {{-- <span class="badge badge-info right">2</span> --}}
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('role-list')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ isActiveRoute('roles.index') }}">
                                <i class="nav-icon fas fa-user-tag"></i>
                                <p>
                                    Roles
                                    {{-- <span class="badge badge-info right">2</span> --}}
                                </p>
                            </a>
                        </li>
                        @endcan
                        @can('permission-list')
                        <li class="nav-item">
                            <a href="{{ route('permissions.index') }}" class="nav-link {{ isActiveRoute('permissions.index') }}">
                                <i class="fa-solid fa-key nav-icon"></i>
                                <p>
                                    Permission
                                    {{-- <span class="badge badge-info right">2</span> --}}
                                </p>
                            </a>
                        </li>
                        @endcan
                        @endhasAnyPermission


                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
