<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'UNAS Fest 2025') - Bio University, Technology, Health</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            background-color: #0e3e72; /* Warna biru tua untuk tema universitas */
        }
        .navbar-brand {
            font-weight: 700;
            color: #ffffff;
        }
        .navbar-light .navbar-nav .nav-link {
            color: rgba(255,255,255,0.85);
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: #ffffff;
        }
        .navbar-light .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 200px;
            object-fit: cover;
        }
        .btn-primary {
            background-color: #2e86de;
            border-color: #2e86de;
        }
        .btn-primary:hover {
            background-color: #1c71c7;
            border-color: #1c71c7;
        }
        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
            color: white;
        }
        .btn-warning:hover {
            background-color: #e67e22;
            border-color: #e67e22;
            color: white;
        }
        .footer {
            background-color: #0e3e72;
            color: white;
            padding: 40px 0;
            margin-top: 100px;
        }
        .competition-price {
            font-size: 24px;
            font-weight: 700;
            color: #f39c12;
        }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
        }
        .alert {
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        .competition-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px auto;
        }
        @yield('styles-internal')
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-university me-2"></i>UNAS Fest 2025
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('competitions*') || Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">Competitions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('cart') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            <i class="fas fa-clipboard-list"></i> Registration
                            @if(session('cart'))
                                <span class="badge bg-warning text-dark">{{ count(session('cart')) }}</span>
                            @endif
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if(Auth::user()->isKiw())
                                    <a class="dropdown-item" href="{{ route('kiw.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Super Admin Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @elseif(Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @elseif(Auth::user()->isJuri())
                                    <a class="dropdown-item" href="{{ route('judge.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Juri Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @else
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif

                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="fas fa-list-alt me-2"></i> My Registrations
                                </a>
                                
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container mt-4">
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
        </div>
            
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>UNAS Fest 2025</h5>
                    <p>"Bio University, Technology, Health"</p>
                    <p>Festival kompetisi tahunan Universitas Nasional untuk mahasiswa seluruh Indonesia.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white">Home</a></li>
                        <li><a href="{{ route('products.index') }}" class="text-white">Competitions</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-white">Registration</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address class="text-white">
                        <i class="fas fa-map-marker-alt"></i> Jl. Sawo Manila No.61, RT.14/RW.7, Pejaten Barat, Ps. Minggu, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12520<br>
                        <i class="fas fa-phone"></i> +62 812 345 6789<br>
                        <i class="fas fa-envelope"></i> unasfest@unas.ac.id
                    </address>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} UNAS Fest 2025. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>