<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Book Store') - Nhà sách trực tuyến</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    
    <!-- Custom Frontend CSS -->
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    
    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --text-color: #333;
            --light-gray: #f8f9fa;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            background-color: #f9f9f9;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
        }
        
        p, span, a, li, button, input, select, textarea {
            font-family: 'Open Sans', sans-serif;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .navbar {
            padding: 0.8rem 1rem;
            transition: all 0.3s ease;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.8rem 1rem;
            position: relative;
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 50%;
        }
        
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
        }
        
        .dropdown-item:hover {
            background-color: var(--light-gray);
        }
        
        .shadow-custom {
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        footer {
            background-color: #222;
            color: #fff;
        }
        
        .top-bar {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .search-form {
            position: relative;
        }
        
        .search-form .form-control {
            border-radius: 50px;
            padding-left: 15px;
            padding-right: 50px;
            background-color: var(--light-gray);
            border: none;
        }
        
        .search-form .btn {
            position: absolute;
            right: 5px;
            top: 5px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cart-icon {
            position: relative;
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            border-radius: 50%;
            background-color: var(--accent-color);
            color: white;
            font-size: 0.7rem;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    <!-- Additional CSS -->
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="sticky-top">
        <!-- Top Bar -->
        <div class="top-bar bg-primary text-white py-2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item me-3">
                                <i class="fas fa-phone-alt me-2"></i> +84 123 456 789
                            </li>
                            <li class="list-inline-item">
                                <i class="fas fa-envelope me-2"></i> info@bookstore.com
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 text-end">
                        <ul class="list-inline mb-0">
                            @guest
                                <li class="list-inline-item me-3">
                                    <a href="{{ route('login') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="{{ route('register') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-user-plus me-2"></i> Đăng ký
                                    </a>
                                </li>
                            @else
                                <li class="list-inline-item me-3">
                                    <a href="{{ route('user.profile') }}" class="text-white text-decoration-none">
                                        <i class="fas fa-user me-2"></i> {{ Auth::user()->name }}
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="{{ route('logout') }}" class="text-white text-decoration-none" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-custom">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Book Store Logo" height="50">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('books.index') || request()->routeIs('books.show') || request()->routeIs('books.by_category') || request()->routeIs('books.by_author') ? 'active' : '' }}" href="{{ route('books.index') }}">
                                <i class="fas fa-book me-1"></i> Sách
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="fas fa-puzzle-piece me-1"></i> Đồ chơi & Học cụ
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-th-list me-1"></i> Danh mục
                            </a>
                            <ul class="dropdown-menu animate__animated animate__fadeIn" aria-labelledby="categoriesDropdown">
                                @php
                                    $categories = \App\Models\Category::take(10)->get();
                                @endphp
                                @foreach($categories as $category)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('books.by_category', $category->slug) }}">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('books.index') }}">
                                        <i class="fas fa-list me-1"></i> Xem tất cả danh mục
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                <i class="fas fa-info-circle me-1"></i> Giới thiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                <i class="fas fa-envelope me-1"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <form action="{{ route('books.index') }}" method="GET" class="search-form me-3">
                            <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sách..." 
                                   aria-label="Search" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary cart-icon me-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge">
                                @php
                                    $cartCount = 0;
                                    if (Auth::check()) {
                                        $cartItems = \App\Models\Cart::where('user_id', Auth::id())->get();
                                        foreach ($cartItems as $item) {
                                            $cartCount += $item->quantity;
                                        }
                                    }
                                @endphp
                                {{ $cartCount }}
                            </span>
                        </a>
                        
                        <a href="{{ route('wishlist.index') }}" class="btn btn-outline-danger me-2">
                            <i class="fas fa-heart"></i>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Main Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success animate__animated animate__fadeIn">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger animate__animated animate__fadeIn">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="container">
                <div class="alert alert-danger animate__animated animate__fadeIn">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Newsletter -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h3 class="mb-4">Đăng ký nhận thông tin</h3>
                    <p class="text-muted mb-4">Nhận thông báo về sách mới và ưu đãi đặc biệt từ chúng tôi.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="row g-3 justify-content-center">
                        @csrf
                        <div class="col-md-8">
                            <div class="search-form">
                                <input type="email" class="form-control" name="email" placeholder="Địa chỉ email của bạn" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white mb-4">Về chúng tôi</h5>
                    <p class="text-white-50">
                        Book Store là hệ thống nhà sách trực tuyến, cung cấp đa dạng sách hay và chất lượng 
                        với giá cả hợp lý nhất thị trường.
                    </p>
                    <div class="mt-4">
                        <a href="#" class="me-3 text-white"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="me-3 text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="me-3 text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="me-3 text-white"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white mb-4">Thông tin</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">Giới thiệu</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Liên hệ</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-white-50 text-decoration-none">Điều khoản</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-white-50 text-decoration-none">Chính sách</a></li>
                        <li class="mb-2"><a href="{{ route('faq') }}" class="text-white-50 text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="text-white mb-4">Tài khoản</h5>
                    <ul class="list-unstyled">
                        @guest
                            <li class="mb-2"><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Đăng nhập</a></li>
                            <li class="mb-2"><a href="{{ route('register') }}" class="text-white-50 text-decoration-none">Đăng ký</a></li>
                        @else
                            <li class="mb-2"><a href="{{ route('user.profile') }}" class="text-white-50 text-decoration-none">Tài khoản</a></li>
                            <li class="mb-2"><a href="{{ route('orders.history') }}" class="text-white-50 text-decoration-none">Đơn hàng</a></li>
                            <li class="mb-2"><a href="{{ route('wishlist.index') }}" class="text-white-50 text-decoration-none">Danh sách yêu thích</a></li>
                        @endguest
                        <li class="mb-2"><a href="{{ route('cart.index') }}" class="text-white-50 text-decoration-none">Giỏ hàng</a></li>
                        <li class="mb-2"><a href="{{ route('checkout') }}" class="text-white-50 text-decoration-none">Thanh toán</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white mb-4">Liên hệ</h5>
                    <div class="d-flex mb-3">
                        <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                        <p class="text-white-50">123 Đường Sách, Quận 1, TP. Hồ Chí Minh</p>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-phone-alt text-primary me-3 mt-1"></i>
                        <p class="text-white-50">+84 123 456 789</p>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-envelope text-primary me-3 mt-1"></i>
                        <p class="text-white-50">info@bookstore.com</p>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="fas fa-clock text-primary me-3 mt-1"></i>
                        <p class="text-white-50">Thứ 2 - Chủ nhật: 8:00 - 22:00</p>
                    </div>
                </div>
            </div>
            
            <hr class="border-secondary">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0">&copy; {{ date('Y') }} Book Store. Bản quyền thuộc về Nhà sách trực tuyến.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="{{ asset('images/payments.png') }}" alt="Payment Methods" height="30">
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary back-to-top" style="position: fixed; bottom: 20px; right: 20px; display: none; z-index: 1000; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-chevron-up"></i>
    </a>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Owl Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    
    <script>
        // Back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.back-to-top').fadeIn();
            } else {
                $('.back-to-top').fadeOut();
            }
        });
        
        $('.back-to-top').click(function() {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
        
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert:not(.alert-permanent)').fadeOut('slow');
        }, 5000);
        
        // Initialize all tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    
    <!-- Additional JS -->
    @yield('scripts')
    
    <!-- Stacked Scripts -->
    @stack('scripts')
</body>
</html> 