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
    <div class="sidebar" id="sidebar">
        <div class="logo">DigiTrack</div>
        <form action="{{ route('dashboard.search') }}" method="POST" class="search-form">
            @csrf
            <input type="text" name="query" placeholder="Central ID..." required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <ul>
            @can('Admin Dashboard')
            <li><a href="{{ route('dashboard.index') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            @endcan
        
            @can('View Districts')
            <li class="has-submenu">
                <a href="#"><i class="fas fa-cogs"></i> General Settings</a>
                <ul class="submenu">
                    <li><a href="{{ route('dashboard.districts') }}"><i class="fas fa-map-marker-alt"></i> District Management</a></li>
                    <li><a href="{{ route('dashboard.pngos') }}"><i class="fas fa-handshake"></i> PNGOs Management</a></li>
                </ul>
            </li>
            @endcan
        
            @can('View Categories')
            <li class="has-submenu">
                <a href="#"><i class="fas fa-user-shield"></i> Roles & Permissions</a>
                <ul class="submenu">
                    <li><a href="{{ route('dashboard.categories') }}"><i class="fas fa-tags"></i> Manage Category</a></li>
                    <li><a href="{{ route('dashboard.roles') }}"><i class="fas fa-user-tag"></i> Manage Roles</a></li>
                    <li><a href="{{ route('permissions.list') }}"><i class="fas fa-key"></i> Manage Permissions</a></li>
                    <li><a href="{{ route('roles.permissions') }}"><i class="fas fa-users-cog"></i> Assign Roles & Permissions</a></li>
                    <li><a href="{{ route('users.index') }}"><i class="fas fa-user"></i> Users</a></li>
                </ul>
            </li>
            @endcan

            <li class="has-submenu">
                <a href="#"><i class="fas fa-cogs"></i> Manage Data Entry</a>
                <ul class="submenu">
                    <li><a href="{{ route('form.index') }}"><i class="fas fa-database"></i> Data Entry Forms</a></li>
                    <li><a href="{{ route('import.view') }}"><i class="fas fa-database"></i> Bulk Data Entry</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i class="fas fa-file-alt"></i> Reports & Analytics</a>
                <ul class="submenu">
                    <li><a href="{{ route('case_list') }}"><i class="fas fa-chart-bar"></i> Case List</a></li>
                    <li><a href="{{ route('customReport') }}"><i class="fas fa-chart-bar"></i> Intervention Report</a></li>
                    <li><a href="{{ route('district.summery') }}"><i class="fas fa-user-clock"></i> District Summery</a></li>
                    <li><a href="{{ route('pngo.summery') }}"><i class="fas fa-lock"></i> PNGO Summery</a></li>
                    <li><a href="{{ route('formal.cases.export') }}"><i class="fa fa-download"></i> Download Excel</a></li>

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
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @endauth
        </ul>
    </div>
    
    

    <div class="header">
        <button id="sidebarToggle"><i class="fas fa-bars"></i></button>
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
    </div>
    
    

    <!-- Content -->
    <div class="content" id="content">

        @yield('content')
        <div id="loader-overlay">
            <div id="loader"></div>
        </div>

    </div>
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
