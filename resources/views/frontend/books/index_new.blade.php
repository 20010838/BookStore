@extends('layouts.frontend')

@section('title', 'Danh sách sách')

@section('styles')
<style>
    .filter-card {
        position: sticky;
        top: 100px;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .filter-card .card-header {
        background-color: var(--primary-color);
        color: white;
        border: none;
        padding: 15px 20px;
    }
    
    .filter-card .card-body {
        padding: 20px;
    }
    
    .book-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        border-radius: 10px;
        overflow: hidden;
        border: none;
    }
    
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .book-card .card-img-top {
        height: 250px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .book-card:hover .card-img-top {
        transform: scale(1.05);
    }
    
    .book-card .card-body {
        padding: 20px;
    }
    
    .book-card .card-title {
        font-weight: 600;
        margin-bottom: 10px;
        font-family: 'Playfair Display', serif;
        height: 48px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .book-card .author-name {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }
    
    .book-card .price {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .category-accordion .accordion-button {
        padding: 0.7rem 1rem;
        font-weight: 500;
        background-color: transparent;
        box-shadow: none;
    }
    
    .category-accordion .accordion-button:not(.collapsed) {
        color: var(--primary-color);
        background-color: transparent;
    }
    
    .category-accordion .accordion-button:focus {
        box-shadow: none;
    }
    
    .category-accordion .accordion-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .subcategory-item {
        padding-left: 1.5rem;
        border: none !important;
    }
    
    .subcategory-item .form-check-label {
        font-size: 0.9rem;
    }
    
    .grandchild-category {
        padding-left: 3rem;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    
    .grandchild-category .form-check-label {
        font-size: 0.85rem;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .form-select {
        border-radius: 5px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
    }
    
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }
    
    .breadcrumb {
        padding: 10px 0;
        background-color: transparent;
    }
    
    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .btn-filter {
        border-radius: 5px;
        padding: 10px 20px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
    }
    
    .pagination {
        justify-content: center;
        margin-top: 30px;
    }
    
    .pagination .page-item .page-link {
        color: var(--primary-color);
        border: none;
        margin: 0 5px;
        border-radius: 5px;
        padding: 8px 16px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        color: white;
    }
    
    .book-img-container {
        position: relative;
        overflow: hidden;
    }
    
    .book-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .book-card:hover .book-overlay {
        opacity: 1;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        background: var(--primary-color);
        color: white;
    }
    
    .price-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--primary-color);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 600;
    }
    
    @media (max-width: 991.98px) {
        .filter-card {
            position: static;
            margin-bottom: 30px;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Filters - Fahasa Style -->
        <div class="col-lg-3 mb-4">
            <div class="card filter-card animate__animated animate__fadeIn">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc</h5>
                </div>
                <div class="card-body py-2">
                    <form action="{{ route('books.index') }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <!-- Categories Filter -->
                        <div class="mb-3">
                            <h6 class="mb-2 text-uppercase">Danh mục</h6>
                            
                            <div class="accordion category-accordion" id="categoryAccordion">
                                <!-- Tất cả sách -->
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header p-0">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check ms-1">
                                                <input class="form-check-input" type="radio" name="category" 
                                                    id="categoryAll" value=""
                                                    {{ !request('category') ? 'checked' : '' }}
                                                    onchange="this.form.submit()">
                                                <label class="form-check-label" for="categoryAll">
                                                    Tất cả sách
                                                </label>
                                            </div>
                                        </div>
                                    </h2>
                                </div>
                                
                                @foreach($rootCategories as $rootCategory)
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header p-0">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="form-check ms-1">
                                                    <input class="form-check-input" type="radio" name="category" 
                                                        id="category{{ $rootCategory->id }}" value="{{ $rootCategory->id }}"
                                                        {{ request('category') == $rootCategory->id ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                    <label class="form-check-label" for="category{{ $rootCategory->id }}">
                                                        {{ $rootCategory->name }}
                                                    </label>
                                                </div>
                                                @if($rootCategory->children && $rootCategory->children->count() > 0)
                                                    <button class="btn btn-sm p-0 text-primary" type="button" 
                                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $rootCategory->id }}" 
                                                        aria-expanded="false" aria-controls="collapse{{ $rootCategory->id }}">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </h2>
                                        
                                        @if($rootCategory->children && $rootCategory->children->count() > 0)
                                            <div id="collapse{{ $rootCategory->id }}" class="accordion-collapse collapse" 
                                                aria-labelledby="heading{{ $rootCategory->id }}" data-bs-parent="#categoryAccordion">
                                                <div class="accordion-body p-0 ps-3">
                                                    <ul class="list-group list-group-flush small">
                                                        @foreach($rootCategory->children as $childCategory)
                                                            <li class="list-group-item subcategory-item border-0 py-1">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check mb-0">
                                                                        <input class="form-check-input" type="radio" name="category" 
                                                                            id="category{{ $childCategory->id }}" value="{{ $childCategory->id }}"
                                                                            {{ request('category') == $childCategory->id ? 'checked' : '' }}
                                                                            onchange="this.form.submit()">
                                                                        <label class="form-check-label" for="category{{ $childCategory->id }}">
                                                                            {{ $childCategory->name }}
                                                                        </label>
                                                                    </div>
                                                                    
                                                                    @if($childCategory->children && $childCategory->children->count() > 0)
                                                                        <button class="btn btn-sm btn-link p-0 ms-2" type="button" 
                                                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $childCategory->id }}" 
                                                                            aria-expanded="false" aria-controls="collapse{{ $childCategory->id }}">
                                                                            <i class="fas fa-chevron-down"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                                
                                                                @if($childCategory->children && $childCategory->children->count() > 0)
                                                                    <div id="collapse{{ $childCategory->id }}" class="collapse mt-1">
                                                                        <ul class="list-group list-group-flush ps-2 small">
                                                                            @foreach($childCategory->children as $grandchildCategory)
                                                                                <li class="list-group-item border-0 py-1 ps-1">
                                                                                    <div class="form-check mb-0">
                                                                                        <input class="form-check-input" type="radio" name="category" 
                                                                                            id="category{{ $grandchildCategory->id }}" value="{{ $grandchildCategory->id }}"
                                                                                            {{ request('category') == $grandchildCategory->id ? 'checked' : '' }}
                                                                                            onchange="this.form.submit()">
                                                                                        <label class="form-check-label small" for="category{{ $grandchildCategory->id }}">
                                                                                            {{ $grandchildCategory->name }}
                                                                                        </label>
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Authors Filter -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2 text-uppercase small">Tác giả</h6>
                            <select class="form-select form-select-sm" name="author" onchange="this.form.submit()">
                                <option value="">Tất cả tác giả</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div class="mb-3 price-range-slider">
                            <h6 class="fw-bold mb-2 text-uppercase small">Khoảng giá</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label for="min_price" class="form-label small">Từ:</label>
                                        <input type="number" class="form-control form-control-sm" id="min_price" name="min_price" 
                                               value="{{ request('min_price', 0) }}" min="0" step="10000">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label for="max_price" class="form-label small">Đến:</label>
                                        <input type="number" class="form-control form-control-sm" id="max_price" name="max_price" 
                                               value="{{ request('max_price', 1000000) }}" min="0" step="10000">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2 text-uppercase small">Sắp xếp theo</h6>
                            <select class="form-select form-select-sm" name="sort" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-filter me-1"></i>Áp dụng bộ lọc
                            </button>
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-redo me-1"></i>Xóa bộ lọc
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Book Listing - Fahasa Style -->
        <div class="col-lg-9">
            <!-- Search Results Header -->
            <div class="bg-white p-3 rounded shadow-sm mb-4 animate__animated animate__fadeIn">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if(request('search'))
                            <h4 class="mb-2 fw-bold text-primary"><i class="fas fa-search me-2"></i>Kết quả tìm kiếm: "{{ request('search') }}"</h4>
                        @elseif(isset($category))
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-2">
                                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">Sách</a></li>
                                    
                                    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                                        @foreach($breadcrumbs as $ancestor)
                                            <li class="breadcrumb-item">
                                                <a href="{{ route('books.by_category', $ancestor->slug) }}" class="text-decoration-none">{{ $ancestor->name }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                    
                                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                                </ol>
                            </nav>
                            <h4 class="mb-2 fw-bold text-primary"><i class="fas fa-tag me-2"></i>{{ $category->name }}</h4>
                        @else
                            <h4 class="mb-2 fw-bold text-primary"><i class="fas fa-book me-2"></i>Tất cả sách</h4>
                        @endif
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="me-3 text-muted small">
                            <span>{{ $books->total() }} sản phẩm</span>
                        </div>
                        
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-sort me-1"></i> Sắp xếp
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                                <li><a class="dropdown-item {{ request('sort') == 'newest' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Mới nhất</a></li>
                                <li><a class="dropdown-item {{ request('sort') == 'price_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Giá tăng dần</a></li>
                                <li><a class="dropdown-item {{ request('sort') == 'price_desc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Giá giảm dần</a></li>
                                <li><a class="dropdown-item {{ request('sort') == 'title_asc' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['sort' => 'title_asc']) }}">Tên A-Z</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Books Grid - Fahasa Style -->
            <div class="row books-container">
                @forelse($books as $book)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="product-card">
                            @if($book->is_new)
                                <span class="badge bg-primary position-absolute">Mới</span>
                            @endif
                            
                            <div class="card-img-container">
                                <a href="{{ route('books.show', $book->slug) }}">
                                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/book-placeholder.jpg') }}" 
                                         class="card-img-top" alt="{{ $book->title }}">
                                </a>
                                
                                <div class="action-buttons">
                                    <button class="action-btn add-to-wishlist" data-bs-toggle="tooltip" title="Thêm vào danh sách yêu thích">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="action-btn add-to-cart" data-bs-toggle="tooltip" title="Thêm vào giỏ hàng">
                                            <i class="fa-solid fa-cart-plus"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="product-title">
                                    <a href="{{ route('books.show', $book->slug) }}">{{ $book->title }}</a>
                                </h5>
                                
                                <div class="product-author">
                                    <a href="{{ route('books.by_author', $book->author->id) }}">{{ $book->author->name }}</a>
                                </div>
                                
                                <div class="rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($book->ratings_avg_rating ?? 0))
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                
                                <div class="product-price">{{ number_format($book->price, 0, ',', '.') }}đ</div>
                                
                                <a href="{{ route('books.show', $book->slug) }}" class="btn btn-details">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center p-5">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <h5>Không tìm thấy sách nào phù hợp với tiêu chí tìm kiếm.</h5>
                            <p>Vui lòng thử lại với bộ lọc khác hoặc xem tất cả sách của chúng tôi.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-book me-2"></i>Xem tất cả sách
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Update price range display
    document.addEventListener('DOMContentLoaded', function() {
        const minPriceInput = document.getElementById('min_price');
        const maxPriceInput = document.getElementById('max_price');
        
        // Ensure max price is always greater than min price
        minPriceInput.addEventListener('change', function() {
            if (parseInt(minPriceInput.value) > parseInt(maxPriceInput.value)) {
                maxPriceInput.value = minPriceInput.value;
            }
        });
        
        maxPriceInput.addEventListener('change', function() {
            if (parseInt(maxPriceInput.value) < parseInt(minPriceInput.value)) {
                minPriceInput.value = maxPriceInput.value;
            }
        });
        
        // Đảm bảo các radio button danh mục tự động submit form khi chọn
        const categoryRadios = document.querySelectorAll('input[name="category"]');
        categoryRadios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                this.closest('form').submit();
            });
        });
        
        // Đảm bảo dropdown tác giả tự động submit form khi thay đổi
        const authorSelect = document.querySelector('select[name="author"]');
        if (authorSelect) {
            authorSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
        
        // Đảm bảo dropdown sắp xếp tự động submit form khi thay đổi
        const sortSelect = document.querySelector('select[name="sort"]');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                this.closest('form').submit();
            });
        }
    });
</script>
@endsection
