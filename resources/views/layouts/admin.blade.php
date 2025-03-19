<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - BookStore Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #343a40;
            color: #fff;
            transition: all 0.3s;
            z-index: 1000;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background-color: #212529;
        }
        
        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #4b545c;
        }
        
        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }
        
        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 1.1em;
            display: block;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        #sidebar ul li a:hover,
        #sidebar ul li a.active {
            color: #fff;
            background-color: #495057;
        }
        
        #sidebar ul li a i {
            margin-right: 10px;
        }
        
        #sidebar ul li.active > a {
            color: #fff;
            background-color: #495057;
        }
        
        #content {
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            position: absolute;
            right: 0;
            transition: all 0.3s;
        }
        
        .navbar {
            background-color: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            padding: 1rem 1.25rem;
        }
        
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            #content {
                width: 100%;
            }
            
            #content.active {
                width: calc(100% - 250px);
            }
            
            #sidebarCollapse span {
                display: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3 class="mb-0">BookStore Admin</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.books.index') }}">
                        <i class="fas fa-book"></i> Quản lý sách
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.index', ['category' => 'toys']) }}" class="nav-link {{ request()->routeIs('admin.products.*') && request('category') == 'toys' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gamepad"></i>
                        Quản lý đồ chơi & dụng cụ học tập
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">
                        <i class="fas fa-list"></i> Quản lý danh mục
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.authors.index') }}">
                        <i class="fas fa-user-edit"></i> Quản lý tác giả
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> Quản lý đơn hàng
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i> Quản lý người dùng
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reviews.index') }}">
                        <i class="fas fa-star"></i> Quản lý đánh giá
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <a href="#reportsSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fas fa-chart-bar"></i> Báo cáo
                    </a>
                    <ul class="collapse list-unstyled {{ request()->routeIs('admin.reports.*') ? 'show' : '' }}" id="reportsSubmenu">
                        <li>
                            <a href="{{ route('admin.reports.sales') }}" class="{{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                                <i class="fas fa-chart-line"></i> Báo cáo doanh thu
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.inventory') }}" class="{{ request()->routeIs('admin.reports.inventory') ? 'active' : '' }}">
                                <i class="fas fa-boxes"></i> Báo cáo tồn kho
                            </a>
                        </li>
                    </ul>
                </li>
            
              
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('home') }}" target="_blank">
                                            <i class="fas fa-store me-2"></i> Xem cửa hàng
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html> 