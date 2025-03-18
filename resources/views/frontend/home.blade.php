@extends('layouts.frontend')

@section('title', 'Trang chủ')

@section('styles')
<style>
    .hero-section {
        background-image: url('{{ asset('images/hero-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 500px;
    }
    
    .book-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .book-card .card-img-top {
        height: 250px;
        object-fit: cover;
    }
    
    .category-card {
        height: 150px;
        overflow: hidden;
        position: relative;
        border-radius: 8px;
    }
    
    .category-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .category-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .category-name {
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }
    
    .section-title {
        position: relative;
        margin-bottom: 40px;
        text-align: center;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background-color: #0d6efd;
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class="col-md-6 hero-content text-white">
                <h1 class="display-4 fw-bold mb-4">Khám phá thế giới qua từng trang sách</h1>
                <p class="lead mb-4">Hàng ngàn đầu sách chất lượng với giá cả hợp lý và dịch vụ giao hàng nhanh chóng.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg">Khám phá ngay</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="section-title">Danh mục nổi bật</h2>
                <p class="text-muted">Khám phá sách theo danh mục yêu thích của bạn</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('books.index') }}" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
        </div>
        
        <div class="row">
            @foreach($rootCategories as $rootCategory)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <a href="{{ route('books.by_category', $rootCategory->slug) }}" class="text-white text-decoration-none">
                                    {{ $rootCategory->name }}
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($rootCategory->children && $rootCategory->children->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($rootCategory->children->take(4) as $childCategory)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="{{ route('books.by_category', $childCategory->slug) }}" class="text-decoration-none">
                                                {{ $childCategory->name }}
                                            </a>
                                            @if($childCategory->children && $childCategory->children->count() > 0)
                                                <span class="badge bg-primary rounded-pill">{{ $childCategory->children->count() }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @if($rootCategory->children->count() > 4)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('books.by_category', $rootCategory->slug) }}" class="btn btn-sm btn-outline-primary">
                                            Xem thêm
                                        </a>
                                    </div>
                                @endif
                            @else
                                <p class="card-text text-muted">Chưa có danh mục con</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- New Releases Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="section-title">Sản phẩm mới</h2>
                <p class="text-muted">Sách, đồ chơi và dụng cụ học tập mới nhất</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('books.index') }}?sort=newest" class="btn btn-outline-primary">Sách mới</a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Đồ chơi & Học cụ</a>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Hiển thị sách mới -->
            @foreach($newReleases->take(4) as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card book-card h-100 shadow-sm">
                        <a href="{{ route('books.show', $book->slug) }}">
                            <img src="{{ $book->cover_image ?  asset('storage/' . $book->cover_image)  : asset('images/book-placeholder.jpg') }}" 
                                 class="card-img-top book-cover" alt="{{ $book->title }}">
                        </a>
                        <div class="card-body">
                            <span class="badge bg-primary mb-2">Sách</span>
                            <h5 class="book-title">
                                <a href="{{ route('books.show', $book->slug) }}" class="text-decoration-none text-dark">
                                    {{ $book->title }}
                                </a>
                            </h5>
                            <p class="book-author">
                                <a href="{{ route('books.by_author', $book->author->slug) }}" class="text-decoration-none text-muted">
                                    {{ $book->author->name }}
                                </a>
                            </p>
                            <p class="book-price">{{ number_format($book->price) }} đ</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-outline-primary">
                                    Chi tiết
                                </a>
                                @if($book->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Hiển thị sản phẩm mới -->
            @if(isset($products) && $products->count() > 0)
                @foreach($products->take(4) as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card book-card h-100 shadow-sm">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/product-placeholder.jpg') }}" 
                                     class="card-img-top book-cover" alt="{{ $product->name }}">
                            </a>
                            <div class="card-body">
                                <span class="badge bg-success mb-2">
                                    @if($product->category)
                                        {{ $product->category->name }}
                                    @else
                                        Sản phẩm
                                    @endif
                                </span>
                                <h5 class="book-title">
                                    <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark">
                                        {{ $product->name }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">
                                    {{ $product->brand ?? 'Thương hiệu tốt' }}
                                </p>
                                <p class="book-price">{{ number_format($product->price) }} đ</p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                        Chi tiết
                                    </a>
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Popular Authors Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="section-title">Tác giả nổi bật</h2>
                <p class="text-muted">Những tác giả được yêu thích nhất</p>
            </div>
        </div>
        
        <div class="row">
            @foreach($authors as $author)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4 text-center">
                    <a href="{{ route('books.by_author', $author->slug) }}" class="text-decoration-none">
                        <div class="card border-0 bg-transparent">
                            <img src="{{ $author->photo ? asset($author->photo) : asset('images/author-placeholder.jpg') }}" 
                                 class="rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; object-fit: cover;" 
                                 alt="{{ $author->name }}">
                            <div class="card-body p-0">
                                <h5 class="card-title">{{ $author->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h4>Giao hàng nhanh chóng</h4>
                <p class="text-muted">Giao hàng trong vòng 24h tại nội thành</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h4>Đổi trả dễ dàng</h4>
                <p class="text-muted">Đổi trả sản phẩm trong vòng 7 ngày</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h4>Thanh toán an toàn</h4>
                <p class="text-muted">Nhiều phương thức thanh toán</p>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h4>Hỗ trợ 24/7</h4>
                <p class="text-muted">Luôn sẵn sàng hỗ trợ bạn mọi lúc</p>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-5 bg-light newsletter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="mb-4">Đăng ký nhận thông tin</h2>
                <p class="text-muted mb-4">Nhận thông tin về sách mới và khuyến mãi hấp dẫn</p>
                <form class="row g-3 justify-content-center">
                    <div class="col-md-8">
                        <input type="email" class="form-control form-control-lg" placeholder="Nhập email của bạn">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection 