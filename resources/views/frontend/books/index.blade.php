@extends('layouts.frontend')

@section('title', 'Danh sách sách')

@section('styles')
<style>
    .filter-card {
        position: sticky;
        top: 100px;
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
    
    .price-range-slider .form-range::-webkit-slider-thumb {
        background: #0d6efd;
    }
    
    .price-range-slider .form-range::-moz-range-thumb {
        background: #0d6efd;
    }
    
    .price-range-slider .form-range::-ms-thumb {
        background: #0d6efd;
    }
    
    .category-accordion .accordion-button {
        padding: 0.5rem 1rem;
    }
    
    .category-accordion .accordion-button::after {
        margin-left: 0;
    }
    
    .subcategory-item {
        padding-left: 1.5rem;
    }
    
    .subcategory-item .form-check-label {
        font-size: 0.9rem;
    }
    
    .grandchild-category {
        padding-left: 3rem;
    }
    
    .grandchild-category .form-check-label {
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card filter-card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Bộ lọc</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('books.index') }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        
                        <!-- Categories Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Danh mục</h6>
                            <div class="accordion category-accordion" id="categoryAccordion">
                                @foreach($rootCategories as $rootCategory)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $rootCategory->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="form-check ms-2">
                                                    <input class="form-check-input" type="radio" name="category" 
                                                        id="category{{ $rootCategory->id }}" value="{{ $rootCategory->id }}"
                                                        {{ request('category') == $rootCategory->id ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category{{ $rootCategory->id }}">
                                                        {{ $rootCategory->name }}
                                                    </label>
                                                </div>
                                                @if($rootCategory->children && $rootCategory->children->count() > 0)
                                                    <button class="accordion-button collapsed p-2" type="button" 
                                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $rootCategory->id }}" 
                                                        aria-expanded="false" aria-controls="collapse{{ $rootCategory->id }}">
                                                    </button>
                                                @endif
                                            </div>
                                        </h2>
                                        
                                        @if($rootCategory->children && $rootCategory->children->count() > 0)
                                            <div id="collapse{{ $rootCategory->id }}" class="accordion-collapse collapse" 
                                                aria-labelledby="heading{{ $rootCategory->id }}" data-bs-parent="#categoryAccordion">
                                                <div class="accordion-body p-0">
                                                    <ul class="list-group list-group-flush">
                                                        @foreach($rootCategory->children as $childCategory)
                                                            <li class="list-group-item subcategory-item">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="radio" name="category" 
                                                                            id="category{{ $childCategory->id }}" value="{{ $childCategory->id }}"
                                                                            {{ request('category') == $childCategory->id ? 'checked' : '' }}>
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
                                                                    <div id="collapse{{ $childCategory->id }}" class="collapse mt-2">
                                                                        <ul class="list-group list-group-flush">
                                                                            @foreach($childCategory->children as $grandchildCategory)
                                                                                <li class="list-group-item border-0 grandchild-category">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="category" 
                                                                                            id="category{{ $grandchildCategory->id }}" value="{{ $grandchildCategory->id }}"
                                                                                            {{ request('category') == $grandchildCategory->id ? 'checked' : '' }}>
                                                                                        <label class="form-check-label" for="category{{ $grandchildCategory->id }}">
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
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Tác giả</h6>
                            <select class="form-select" name="author">
                                <option value="">Tất cả tác giả</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Price Range Filter -->
                        <div class="mb-4 price-range-slider">
                            <h6 class="fw-bold mb-3">Khoảng giá</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label for="min_price" class="form-label">Từ:</label>
                                        <input type="number" class="form-control" id="min_price" name="min_price" 
                                               value="{{ request('min_price', 0) }}" min="0" step="10000">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <label for="max_price" class="form-label">Đến:</label>
                                        <input type="number" class="form-control" id="max_price" name="max_price" 
                                               value="{{ request('max_price', 1000000) }}" min="0" step="10000">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sort Options -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Sắp xếp theo</h6>
                            <select class="form-select" name="sort">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary w-100 mt-2">Xóa bộ lọc</a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Book Listing -->
        <div class="col-lg-9">
            <!-- Search Results Info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    @if(request('search'))
                        <h4>Kết quả tìm kiếm: "{{ request('search') }}"</h4>
                    @elseif(isset($category))
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Sách</a></li>
                                
                                @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                                    @foreach($breadcrumbs as $ancestor)
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('books.by_category', $ancestor->slug) }}">{{ $ancestor->name }}</a>
                                        </li>
                                    @endforeach
                                @endif
                                
                                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                            </ol>
                        </nav>
                        <h4>Danh mục: {{ $category->name }}</h4>
                    @elseif(request('category'))
                        @php
                            $categoryId = request('category');
                            $selectedCategory = $rootCategories->first(function($cat) use ($categoryId) {
                                return $cat->id == $categoryId;
                            });
                            
                            if (!$selectedCategory) {
                                // Check in children
                                foreach ($rootCategories as $rootCat) {
                                    $selectedCategory = $rootCat->children->first(function($cat) use ($categoryId) {
                                        return $cat->id == $categoryId;
                                    });
                                    
                                    if ($selectedCategory) break;
                                    
                                    // Check in grandchildren
                                    foreach ($rootCat->children as $childCat) {
                                        $selectedCategory = $childCat->children->first(function($cat) use ($categoryId) {
                                            return $cat->id == $categoryId;
                                        });
                                        
                                        if ($selectedCategory) break;
                                    }
                                    
                                    if ($selectedCategory) break;
                                }
                            }
                            
                            $categoryName = $selectedCategory ? $selectedCategory->name : '';
                        @endphp
                        <h4>Danh mục: {{ $categoryName }}</h4>
                    @elseif(request('author'))
                        @php
                            $authorName = $authors->where('id', request('author'))->first()->name ?? '';
                        @endphp
                        <h4>Tác giả: {{ $authorName }}</h4>
                    @else
                        <h4>Tất cả sách</h4>
                    @endif
                    <p>Hiển thị {{ $books->count() }} / {{ $books->total() }} sách</p>
                </div>
                <div class="d-none d-md-block">
                    {{ $books->appends(request()->query())->links() }}
                </div>
            </div>
            
            <!-- Book Grid -->
            <div class="row">
                @forelse($books as $book)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card book-card h-100">
                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/book-placeholder.png') }}" 
                                 class="card-img-top" alt="{{ $book->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($book->title, 40) }}</h5>
                                <p class="card-text text-muted mb-2">{{ $book->author->name }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-primary">{{ number_format($book->price) }} đ</span>
                                    <span class="badge bg-{{ $book->stock > 0 ? 'success' : 'danger' }}">
                                        {{ $book->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                    @if($book->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-shopping-cart me-1"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> Không tìm thấy sách nào phù hợp với tiêu chí tìm kiếm.
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
    });
</script>
@endsection 