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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
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
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
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
                                Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('books.index') ? 'active' : '' }}" href="{{ route('books.index') }}">
                                Sách
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                Đồ chơi & Học cụ
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Danh mục
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
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
                                        Xem tất cả danh mục
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                Giới thiệu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                Liên hệ
                            </a>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <form action="{{ route('books.index') }}" method="GET" class="d-flex me-3">
                            <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sách..." 
                                   aria-label="Search" value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            @auth
                                @php
                                    $cartCount = \App\Models\Cart::where('user_id', Auth::id())->sum('quantity');
                                @endphp
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            @endauth
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
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5 class="mb-4">Về chúng tôi</h5>
                    <p>Book Store là nhà sách trực tuyến cung cấp hàng ngàn đầu sách chất lượng với giá cả hợp lý và dịch vụ giao hàng nhanh chóng.</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="mb-4">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-white text-decoration-none">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('books.index') }}" class="text-white text-decoration-none">Sách</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-white text-decoration-none">Giới thiệu</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-white text-decoration-none">Liên hệ</a></li>
                        <li><a href="{{ route('faq') }}" class="text-white text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="mb-4">Chính sách</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-white text-decoration-none">Điều khoản sử dụng</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-white text-decoration-none">Chính sách bảo mật</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Chính sách đổi trả</a></li>
                        <li class="mb-2"><a href="#" class="text-white text-decoration-none">Chính sách vận chuyển</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Phương thức thanh toán</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3 mb-4">
                    <h5 class="mb-4">Liên hệ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Đường ABC, Quận XYZ, TP. HCM</li>
                        <li class="mb-2"><i class="fas fa-phone-alt me-2"></i> +84 123 456 789</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@bookstore.com</li>
                        <li><i class="fas fa-clock me-2"></i> Thứ 2 - Chủ nhật: 8:00 - 22:00</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-md-0">&copy; {{ date('Y') }} Book Store. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <img src="{{ asset('images/payment-methods.png') }}" alt="Payment Methods" height="30">
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional Scripts -->
    @yield('scripts')
</body>
</html> 