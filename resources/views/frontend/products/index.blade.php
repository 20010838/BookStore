@extends('layouts.frontend')

@section('styles')
<style>
    /* Tùy chỉnh giao diện bộ lọc */
    .filter-card {
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
        border: none;
    }
    
    .filter-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #eee;
        border-radius: 10px 10px 0 0;
        padding: 15px 20px;
    }
    
    .category-accordion .accordion-button {
        padding: 10px 0;
        background: none;
        box-shadow: none;
        width: 30px;
        height: 30px;
    }
    
    .category-accordion .accordion-button:not(.collapsed) {
        background: none;
        color: #0d6efd;
    }
    
    .category-accordion .accordion-button:focus {
        box-shadow: none;
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .category-item {
        border-radius: 5px;
        transition: background-color 0.2s;
        padding: 8px 10px;
        margin-bottom: 5px;
        border: 1px solid transparent;
    }
    
    .category-item:hover {
        background-color: #f8f9fa;
        border-color: #eee;
    }
    
    .category-item.active {
        background-color: #e9f0ff;
        border-color: #cfe2ff;
    }
    
    .subcategory-container {
        border-left: 1px solid #eee;
        margin-left: 15px;
        padding-left: 15px;
    }
    
    .apply-btn {
        border-radius: 5px;
        font-weight: 500;
    }
    
    /* Tùy chỉnh giao diện sản phẩm */
    .product-card {
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .product-img {
        height: 200px;
        object-fit: cover;
    }
    
    .category-badge {
        background-color: #e9f0ff;
        color: #0d6efd;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        display: inline-block;
    }
    
    .price-tag {
        font-weight: 600;
        color: #212529;
    }
    
    .detail-btn {
        border-radius: 5px;
    }
    
    .breadcrumb-container {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Tiêu đề danh mục nếu đang lọc theo danh mục -->
    @if(isset($category))
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $category->name }}</h1>
        <div class="breadcrumb-container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Sản phẩm</a></li>
                    @if($category->parent)
                        @if($category->parent->parent)
                            <li class="breadcrumb-item"><a href="{{ route('products.by_category', $category->parent->parent->slug) }}" class="text-decoration-none">{{ $category->parent->parent->name }}</a></li>
                        @endif
                        <li class="breadcrumb-item"><a href="{{ route('products.by_category', $category->parent->slug) }}" class="text-decoration-none">{{ $category->parent->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
        </div>
        @if($category->description)
        <p class="lead">{{ $category->description }}</p>
        @endif
    </div>
    @else
    <h1 class="h2 fw-bold mb-4">Tất cả sản phẩm</h1>
    @endif

    <div class="row g-4">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 col-md-4">
            <div class="card filter-card sticky-md-top" style="top: 20px; z-index: 1000;">
                <div class="card-header filter-header">
                    <h5 class="mb-0 fw-bold">Bộ lọc1</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Danh mục</h6>
                            
                            <!-- Danh mục chính (cấp 1) -->
                            <div class="accordion accordion-flush category-accordion" id="categoryAccordion">
                                <!-- Tất cả sản phẩm -->
                                <div class="category-item {{ !request('category') ? 'active' : '' }}">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" 
                                               id="categoryAll" value=""
                                               {{ !request('category') ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                        <label class="form-check-label fw-medium" for="categoryAll">
                                            Tất cả sản phẩm
                                        </label>
                                    </div>
                                </div>
                                
                                @foreach($productCategories as $rootCategory)
                                    <div class="category-item {{ request('category') == $rootCategory->id ? 'active' : '' }}">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="category" 
                                                       id="category{{ $rootCategory->id }}" value="{{ $rootCategory->id }}"
                                                       {{ request('category') == $rootCategory->id ? 'checked' : '' }}
                                                       onchange="this.form.submit()">
                                                <label class="form-check-label fw-medium" for="category{{ $rootCategory->id }}">
                                                    {{ $rootCategory->name }}
                                                </label>
                                            </div>
                                            
                                            @if($rootCategory->children && $rootCategory->children->count() > 0)
                                                <button class="accordion-button collapsed p-0" type="button" 
                                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $rootCategory->id }}" 
                                                        aria-expanded="false" aria-controls="collapse{{ $rootCategory->id }}">
                                                </button>
                                            @endif
                                        </div>
                                        
                                        @if($rootCategory->children && $rootCategory->children->count() > 0)
                                            <div id="collapse{{ $rootCategory->id }}" class="accordion-collapse collapse" 
                                                 aria-labelledby="heading{{ $rootCategory->id }}">
                                                <div class="subcategory-container mt-2">
                                                    <!-- Danh mục cấp 2 -->
                                                    @foreach($rootCategory->children as $childCategory)
                                                        <div class="category-item {{ request('category') == $childCategory->id ? 'active' : '' }}">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="category" 
                                                                           id="category{{ $childCategory->id }}" value="{{ $childCategory->id }}"
                                                                           {{ request('category') == $childCategory->id ? 'checked' : '' }}
                                                                           onchange="this.form.submit()">
                                                                    <label class="form-check-label" for="category{{ $childCategory->id }}">
                                                                        {{ $childCategory->name }}
                                                                    </label>
                                                                </div>
                                                                
                                                                @if($childCategory->children && $childCategory->children->count() > 0)
                                                                    <button class="accordion-button collapsed p-0" type="button" 
                                                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $childCategory->id }}" 
                                                                            aria-expanded="false" aria-controls="collapse{{ $childCategory->id }}">
                                                                    </button>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Danh mục cấp 3 -->
                                                            @if($childCategory->children && $childCategory->children->count() > 0)
                                                                <div id="collapse{{ $childCategory->id }}" class="collapse mt-2">
                                                                    <div class="subcategory-container">
                                                                        @foreach($childCategory->children as $grandChildCategory)
                                                                            <div class="category-item {{ request('category') == $grandChildCategory->id ? 'active' : '' }}">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" name="category" 
                                                                                           id="category{{ $grandChildCategory->id }}" value="{{ $grandChildCategory->id }}"
                                                                                           {{ request('category') == $grandChildCategory->id ? 'checked' : '' }}
                                                                                           onchange="this.form.submit()">
                                                                                    <label class="form-check-label" for="category{{ $grandChildCategory->id }}">
                                                                                        {{ $grandChildCategory->name }}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold border-bottom pb-2 mb-3">Sắp xếp theo</h6>
                            <select name="sort" class="form-select shadow-none" onchange="this.form.submit()">
                                <option value="">Mặc định</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 apply-btn">Áp dụng bộ lọc</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9 col-md-8">
            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        @if($product->created_at && $product->created_at->diffInDays(now()) < 30)
                            <span class="badge bg-danger">Mới</span>
                        @endif
                        
                        <div class="card-img-container">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            </a>
                            
                            <div class="action-buttons">
                                <button class="action-btn add-to-wishlist" data-bs-toggle="tooltip" title="Thêm vào danh sách yêu thích">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="product-title">
                                <a href="{{ route('products.show', $product->slug) }}">{{ Str::limit($product->name, 40) }}</a>
                            </h5>
                            
                            <div class="product-author">
                                {{ $product->brand ?? 'Thương hiệu tốt' }}
                            </div>
                            
                            <div class="rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($product->ratings_avg_rating ?? 0))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            
                            <div class="product-price">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                            
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-details">Chi tiết</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info rounded-3 p-4 shadow-sm">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">Không tìm thấy sản phẩm</h5>
                                <p class="mb-0">Không có sản phẩm nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 