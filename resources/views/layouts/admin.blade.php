<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - UNAS Fest 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <style>
        :root {
            --primary-color: #0e3e72;
            --secondary-color: #2e86de;
            --accent-color: #f39c12;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 280px;
            background: var(--primary-color);
            color: white;
            position: fixed;
            height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .sidebar-collapsed {
            width: 70px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-radius: 5px;
            margin: 5px 10px;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--secondary-color);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .sidebar .nav-link i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .nav-heading {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 20px 10px;
            color: rgba(255,255,255,0.6);
        }
        
        .logo {
            display: flex;
            align-items: center;
            padding: 20px;
            color: white;
            font-weight: 700;
            font-size: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
        }
        
        .logo i {
            margin-right: 10px;
            font-size: 28px;
        }
        
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .main-content-expanded {
            margin-left: 70px;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            border: none;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #e67e22;
            border-color: #e67e22;
            color: white;
        }
        
        .badge-role-kiw {
            background-color: #e74c3c;
            color: white;
        }
        
        .badge-role-admin {
            background-color: #3498db;
            color: white;
        }
        
        .badge-role-juri {
            background-color: #2ecc71;
            color: white;
        }
        
        .badge-role-peserta {
            background-color: #f39c12;
            color: white;
        }
        
        .toggle-sidebar {
            cursor: pointer;
            font-size: 20px;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .profile-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .profile-dropdown .dropdown-menu {
            right: 0;
            left: auto;
            min-width: 200px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            border: none;
            padding: 10px;
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .dashboard-card {
            border-left: 4px solid;
            transition: transform 0.3s;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .dashboard-card .card-body {
            padding: 20px;
        }
        
        .dashboard-card .card-title {
            font-size: 1rem;
            margin-bottom: 5px;
            color: #6c757d;
        }
        
        .dashboard-card .card-value {
            font-size: 2rem;
            font-weight: 600;
        }
        
        .dashboard-card .card-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }
        
        /* Table styles */
        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .dataTables_wrapper .dataTables_length, 
        .dataTables_wrapper .dataTables_filter, 
        .dataTables_wrapper .dataTables_info, 
        .dataTables_wrapper .dataTables_processing, 
        .dataTables_wrapper .dataTables_paginate {
            margin-bottom: 10px;
        }
        
        .select2-container--bootstrap-5 .select2-selection {
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
            }
            
            .sidebar .nav-link span, 
            .sidebar .nav-heading, 
            .logo span {
                display: none;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 576px) {
            .logo {
                justify-content: center;
            }
            
            .logo i {
                margin-right: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="logo">
            <i class="fas fa-university"></i>
            <span>UNAS Fest 2025</span>
        </div>
        
        <div class="nav-heading">MENU UTAMA</div>
        
        @if(auth()->user()->isKiw())
        <a href="{{ route('kiw.dashboard') }}" class="nav-link {{ request()->routeIs('kiw.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('kiw.users.index') }}" class="nav-link {{ request()->routeIs('kiw.users*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Kelola Pengguna</span>
        </a>
        <a href="{{ route('kiw.roles.index') }}" class="nav-link {{ request()->routeIs('kiw.roles*') ? 'active' : '' }}">
            <i class="fas fa-user-tag"></i>
            <span>Kelola Role</span>
        </a>
        <a href="{{ route('kiw.competitions.index') }}" class="nav-link {{ request()->routeIs('kiw.competitions*') ? 'active' : '' }}">
            <i class="fas fa-trophy"></i>
            <span>Kelola Kompetisi</span>
        </a>
        <a href="{{ route('kiw.evaluations.index') }}" class="nav-link {{ request()->routeIs('kiw.evaluations*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Penilaian</span>
        </a>
        <a href="{{ route('kiw.submissions.index') }}" class="nav-link {{ request()->routeIs('kiw.submissions*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Karya Peserta</span>
        </a>
        <a href="{{ route('kiw.payments.index') }}" class="nav-link {{ request()->routeIs('kiw.payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Pembayaran</span>
        </a>
        <a href="{{ route('kiw.reports.index') }}" class="nav-link {{ request()->routeIs('kiw.reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        <a href="{{ route('kiw.settings.index') }}" class="nav-link {{ request()->routeIs('kiw.settings*') ? 'active' : '' }}">
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </a>
        @endif
        
        @if(auth()->user()->isAdmin() && !auth()->user()->isKiw())
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.competitions.index') }}" class="nav-link {{ request()->routeIs('admin.competitions*') ? 'active' : '' }}">
            <i class="fas fa-trophy"></i>
            <span>Kelola Kompetisi</span>
        </a>
        <a href="{{ route('admin.participants.index') }}" class="nav-link {{ request()->routeIs('admin.participants*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Peserta</span>
        </a>
        <a href="{{ route('admin.judges.index') }}" class="nav-link {{ request()->routeIs('admin.judges*') ? 'active' : '' }}">
            <i class="fas fa-gavel"></i>
            <span>Juri</span>
        </a>
        <a href="{{ route('admin.registrations.index') }}" class="nav-link {{ request()->routeIs('admin.registrations*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i>
            <span>Pendaftaran</span>
        </a>
        <a href="{{ route('admin.submissions.index') }}" class="nav-link {{ request()->routeIs('admin.submissions*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Karya Peserta</span>
        </a>
        <a href="{{ route('admin.evaluations.index') }}" class="nav-link {{ request()->routeIs('admin.evaluations*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Penilaian</span>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i>
            <span>Pembayaran</span>
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Laporan</span>
        </a>
        @endif
        
        @if(auth()->user()->isJuri() && !auth()->user()->isAdmin())
        <a href="{{ route('judge.dashboard') }}" class="nav-link {{ request()->routeIs('judge.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('judge.competitions.index') }}" class="nav-link {{ request()->routeIs('judge.competitions*') ? 'active' : '' }}">
            <i class="fas fa-trophy"></i>
            <span>Kompetisi</span>
        </a>
        <a href="{{ route('judge.submissions.index') }}" class="nav-link {{ request()->routeIs('judge.submissions*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Karya Peserta</span>
        </a>
        <a href="{{ route('judge.evaluations.index') }}" class="nav-link {{ request()->routeIs('judge.evaluations*') ? 'active' : '' }}">
            <i class="fas fa-star"></i>
            <span>Penilaian Saya</span>
        </a>
        @endif
        
        <div class="nav-heading">Akun</div>
        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user-circle"></i>
            <span>Profil</span>
        </a>
        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <div class="navbar">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div>
                    <span class="toggle-sidebar" id="toggle-sidebar">
                        <i class="fas fa-bars"></i>
                    </span>
                </div>
                
                <div class="dropdown profile-dropdown">
                    <a class="dropdown-toggle d-flex align-items-center" href="#" role="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-pic me-2">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span>{{ auth()->user()->name }}</span>
                    </a>
                    
                    <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                        <li><div class="dropdown-item-text">{{ auth()->user()->email }}</div></li>
                        <li><div class="dropdown-item-text">
                            @if(auth()->user()->isKiw())
                                <span class="badge bg-danger">Super Admin</span>
                            @elseif(auth()->user()->isAdmin())
                                <span class="badge bg-primary">Admin</span>
                            @elseif(auth()->user()->isJuri())
                                <span class="badge bg-success">Juri</span>
                            @elseif(auth()->user()->isPeserta())
                                <span class="badge bg-warning text-dark">Peserta</span>
                            @endif
                        </div></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-circle me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-home me-2"></i> Home</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-2').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                            <form id="logout-form-2" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (toggleSidebar) {
                toggleSidebar.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-collapsed');
                    mainContent.classList.toggle('main-content-expanded');
                });
            }
            
            // Initialize DataTables
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                }
            });
            
            // Initialize Flatpickr
            $('.datepicker').flatpickr({
                dateFormat: 'Y-m-d',
                locale: 'id'
            });
            
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });
        });
    </script>
    @yield('scripts')
</body>
</html>