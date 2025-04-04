<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Midtrans Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #343a40;
            transition: all 0.3s;
            width: 250px;
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #3490dc;
        }
        
        /* Main content */
        main {
            transition: all 0.3s;
            margin-left: 250px; /* Add this to ensure main content is offset */
        }
        
        .navbar-brand {
            padding-top: .75rem;
            padding-bottom: .75rem;
            font-size: 1rem;
            background-color: rgba(0, 0, 0, .25);
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
        }
        
        /* Collapsed sidebar styles */
        .sidebar-collapsed .sidebar {
            width: 60px;
        }
        
        .sidebar-collapsed .sidebar .nav-link span {
            display: none;
        }
        
        .sidebar-collapsed .sidebar .nav-link {
            justify-content: center;
            padding: 10px;
        }
        
        .sidebar-collapsed .sidebar .nav-link i {
            margin-right: 0;
        }
        
        .sidebar-collapsed main {
            margin-left: 60px !important;
        }
        
        /* Mobile view */
        @media (max-width: 767.98px) {
            main {
                margin-left: 0 !important;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px; /* Start off-screen */
                height: 100vh;
                box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
                transition: left 0.3s ease;
            }
            
            .sidebar.show {
                left: 0;
            }
        }
        
        /* Sidebar toggle button */
        .sidebar-toggle {
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top p-0 shadow">
        <div class="d-flex align-items-center">
            <div class="sidebar-toggle d-md-block" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </div>
            <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="{{ route('admin.dashboard') }}">
                <span>Midtrans Shop Admin</span>
            </a>
        </div>
        <button class="navbar-toggler d-md-none" type="button" id="mobileToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex justify-content-end">
            <ul class="navbar-nav px-3 d-flex flex-row">
                <li class="nav-item text-nowrap me-3">
                    <a class="nav-link" href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i> <span class="d-none d-md-inline">Lihat Toko</span>
                    </a>
                </li>
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="sidebar">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                                <i class="fas fa-box"></i> <span>Produk</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-shopping-cart"></i> <span>Pesanan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-users"></i> <span>Pengguna</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('header', 'Dashboard')</h1>
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

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar on click
            $('#sidebarToggle').click(function() {
                $('body').toggleClass('sidebar-collapsed');
                
                // Store collapsed state in localStorage
                localStorage.setItem('sidebarCollapsed', $('body').hasClass('sidebar-collapsed'));
            });
            
            // Check localStorage for sidebar state
            if (localStorage.getItem('sidebarCollapsed') === 'true') {
                $('body').addClass('sidebar-collapsed');
            }
            
            // Mobile sidebar toggle
            $('#mobileToggle').click(function() {
                $('#sidebarMenu').toggleClass('show');
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).click(function(event) {
                if(
                    $('#sidebarMenu').hasClass('show') && 
                    !$(event.target).closest('#sidebarMenu').length && 
                    !$(event.target).closest('#mobileToggle').length
                ) {
                    $('#sidebarMenu').removeClass('show');
                }
            });
            
            // Adjust layout on window resize
            $(window).resize(function() {
                if ($(window).width() >= 768) {
                    $('#sidebarMenu').removeClass('show');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>