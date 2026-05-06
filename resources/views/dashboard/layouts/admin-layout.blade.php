<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dashboard/css/style.css') }}">
    @stack('styles')

</head>

<body>
    <!-- Overlay for Mobile -->
    <div id="overlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar app-sidebar" id="sidebar" aria-label="Main navigation">
        <div class="logo">
            <span class="brand-mark">D</span>
            <span class="brand-text">DigiTrack</span>
        </div>
        <form action="{{ route('dashboard.search') }}" method="POST" class="search-form">
            @csrf
            <input type="text" name="query" placeholder="Central ID..." required>
            <button type="submit" aria-label="Search"><i class="fas fa-search"></i></button>
        </form>
        <ul class="sidebar-nav">
            @can('Admin Dashboard')
            <li><a class="nav-link" href="{{ route('dashboard.index') }}"><i class="fas fa-tachometer-alt"></i><span class="nav-text">Dashboard</span></a></li>
            @endcan
        
            @can('View Districts')
            <li class="has-submenu">
                <a class="nav-link submenu-toggle" href="#" aria-expanded="false"><i class="fas fa-cogs"></i><span class="nav-text">General Settings</span><i class="fas fa-chevron-down menu-chevron"></i></a>
                <ul class="submenu">
                    <li><a class="nav-link nav-sublink" href="{{ route('dashboard.districts') }}"><i class="fas fa-map-marker-alt"></i><span class="nav-text">District Management</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('dashboard.pngos') }}"><i class="fas fa-handshake"></i><span class="nav-text">PNGOs Management</span></a></li>
                </ul>
            </li>
            @endcan
        
            @can('View Categories')
            <li class="has-submenu">
                <a class="nav-link submenu-toggle" href="#" aria-expanded="false"><i class="fas fa-user-shield"></i><span class="nav-text">Roles & Permissions</span><i class="fas fa-chevron-down menu-chevron"></i></a>
                <ul class="submenu">
                    <li><a class="nav-link nav-sublink" href="{{ route('dashboard.categories') }}"><i class="fas fa-tags"></i><span class="nav-text">Manage Category</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('dashboard.roles') }}"><i class="fas fa-user-tag"></i><span class="nav-text">Manage Roles</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('permissions.list') }}"><i class="fas fa-key"></i><span class="nav-text">Manage Permissions</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('roles.permissions') }}"><i class="fas fa-users-cog"></i><span class="nav-text">Assign Roles & Permissions</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('users.index') }}"><i class="fas fa-user"></i><span class="nav-text">Users</span></a></li>
                </ul>
            </li>
            @endcan

            <li class="has-submenu">
                <a class="nav-link submenu-toggle" href="#" aria-expanded="false"><i class="fas fa-cogs"></i><span class="nav-text">Manage Data Entry</span><i class="fas fa-chevron-down menu-chevron"></i></a>
                <ul class="submenu">
                    <li><a class="nav-link nav-sublink" href="{{ route('form.index') }}"><i class="fas fa-database"></i><span class="nav-text">Data Entry Forms</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('import.view') }}"><i class="fas fa-database"></i><span class="nav-text">Bulk Data Entry</span></a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a class="nav-link submenu-toggle" href="#" aria-expanded="false"><i class="fas fa-file-alt"></i><span class="nav-text">Reports & Analytics</span><i class="fas fa-chevron-down menu-chevron"></i></a>
                <ul class="submenu">
                    <li><a class="nav-link nav-sublink" href="{{ route('case_list') }}"><i class="fas fa-chart-bar"></i><span class="nav-text">Case List</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('customReport') }}"><i class="fas fa-chart-bar"></i><span class="nav-text">Intervention Report</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('district.summery') }}"><i class="fas fa-user-clock"></i><span class="nav-text">District Summery</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('pngo.summery') }}"><i class="fas fa-lock"></i><span class="nav-text">PNGO Summery</span></a></li>
                    <li><a class="nav-link nav-sublink" href="{{ route('formal.cases.export') }}"><i class="fa fa-download"></i><span class="nav-text">Download Excel</span></a></li>

                </ul>
            </li>
        
            <!-- <li><a href="#"><i class="fas fa-bell"></i> Notifications</a></li>
        
            <li class="has-submenu">
                <a href="#"><i class="fas fa-wrench"></i> System Settings</a>
                <ul class="submenu">
                    <li><a href="#"><i class="fas fa-cog"></i> Application Settings</a></li>
                    <li><a href="#"><i class="fas fa-user-lock"></i> Security Settings</a></li>
                </ul>
            </li> -->
    
            <!-- Logout Menu Option for Authenticated Users -->
            @auth
            <li>
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i><span class="nav-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @endauth
        </ul>
    </aside>
    
    

    <header class="header app-header">
        <div class="header-left">
            <button id="sidebarToggle" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
            <div class="page-title">@yield('title')</div>
        </div>
        <div class="profile-menu">
            <button class="profile-button">
                <i class="fas fa-user"></i> {{ Auth::user()->name }}
            </button>
            <div class="dropdown-menu">
                <a href="{{ route('users.my-profile') }}"><i class="fas fa-user"></i> My Profile</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </header>
    
    

    <!-- Content -->
    <main class="content app-content" id="content">

        @yield('content')
        <div id="loader-overlay">
            <div id="loader"></div>
        </div>

    </main>
    <!-- Bootstrap JS & jQuery (optional) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap Bundle --> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <script src="{{ asset('dashboard/js/custom.js') }}"></script> <!-- Your custom JS file -->

    @stack('scripts')
    <script>
        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'Pending', 'In Progress'],
                datasets: [{
                    data: [152, 104, 45],
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8'],
                }]
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May'],
                datasets: [{
                    label: 'Interventions',
                    data: [30, 45, 60, 50, 80],
                    backgroundColor: '#007bff',
                }]
            }
        });
    </script>
    
</body>

</html>
