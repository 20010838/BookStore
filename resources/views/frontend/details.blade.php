@extends('layouts.frontend')

@section('title', $item->name ?? $item->title)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .main-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9f9f9;
    }
    
    .main-image {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .zoom-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .main-image-container:hover .zoom-overlay {
        opacity: 1;
    }
    
    /* Thumbnails Gallery Styles */
    .product-thumbnails-wrapper {
        width: 100%;
        max-width: 150px;
        padding-right: 10px;
    }
    
    .product-thumbnail {
        cursor: pointer;
        border-radius: 0;
    }
    
    .product-thumbnail img {
        width: 100%;
        height: auto;
        object-fit: cover;
        transition: all 0.2s;
    }
    
    .product-thumbnail img:hover {
        border-color: #0d6efd !important;
    }
    
    /* Modal gallery styles */
    #allImagesModal .thumb-item {
        height: 120px;
    }
    
    /* Quantity input styles */
    .input-group {
        max-width: 120px;
        flex-wrap: nowrap;
    }
    
    .input-group .btn-sm {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        z-index: 0;
    }
    
    #quantity {
        height: 30px;
        font-size: 14px;
        padding: 0 5px;
        width: 40px;
        min-width: 40px;
        border-left: 1px solid #dee2e6;
        border-right: 1px solid #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    @if($type == 'book')
                        <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Sách</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('books.by_category', $item->category->slug) }}">{{ $item->category->name }}</a></li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                        @if($item->category)
                            <li class="breadcrumb-item"><a href="{{ route('products.by_category', $item->category->slug) }}">{{ $item->category->name }}</a></li>
                        @endif
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $item->name ?? $item->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Gallery Images -->
        <div class="col-md-5">
            <div class="product-gallery d-flex">
                <!-- Khởi tạo biến mainImage -->
                @php
                    if ($type == 'book') {
                        $mainImage = $item->primary_image_path ?? $item->cover_image ?? 'images/book-placeholder.jpg';
                    } else {
                        $mainImage = $item->image;
                    }
                @endphp
                
                <!-- Thumbnails bên trái -->
                <div class="product-thumbnails-wrapper">
                    <div class="d-block">
                        <!-- Ảnh chính -->
                        <div class="product-thumbnail mb-2">
                            <img src="{{ Storage::url($mainImage) }}" alt="{{ $item->name ?? $item->title }}" class="img-fluid border" onclick="changeMainImage('{{ Storage::url($mainImage) }}')">
                        </div>
                        
                        <!-- Các ảnh phụ -->
                        @foreach($item->images as $image)
                        <div class="product-thumbnail mb-2">
                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? ($item->name ?? $item->title) }}" class="img-fluid border" onclick="changeMainImage('{{ Storage::url($image->image_path) }}')">
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Main Image bên phải -->
                <div class="main-image-container">
                    <a href="{{ Storage::url($mainImage) }}" data-lightbox="item-gallery" data-title="{{ $item->name ?? $item->title }}">
                        <img src="{{ Storage::url($mainImage) }}" class="img-fluid main-image" alt="{{ $item->name ?? $item->title }}">
                        <div class="zoom-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </a>
                </div>
                
                <style>
                    .product-gallery {
                        display: flex;
                        flex-direction: row;
                        gap: 15px;
                    }
                    
                    .product-thumbnails-wrapper {
                        width: 100px;
                        flex-shrink: 0;
                    }
                    
                    .product-thumbnail {
                        cursor: pointer;
                        border-radius: 0;
                        margin-bottom: 10px;
                    }
                    
                    .product-thumbnail img {
                        width: 100%;
                        height: auto;
                        object-fit: cover;
                        transition: all 0.2s;
                    }
                    
                    .product-thumbnail img:hover {
                        border-color: #0d6efd !important;
                    }
                    
                    .main-image-container {
                        flex-grow: 1;
                        height: 400px;
                    }
                </style>

                <!-- Modal hiển thị tất cả ảnh -->
                <div class="modal fade" id="allImagesModal" tabindex="-1" aria-labelledby="allImagesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="allImagesModalLabel">Tất cả hình ảnh</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-4 mb-3">
                                        <div class="thumb-item modal-thumb" data-image="{{ Storage::url($mainImage) }}">
                                            <img src="{{ Storage::url($mainImage) }}" alt="{{ $item->name ?? $item->title }}" class="img-fluid">
                                        </div>
                                    </div>
                                    @foreach($item->images as $image)
                                    <div class="col-4 mb-3">
                                        <div class="thumb-item modal-thumb" data-image="{{ Storage::url($image->image_path) }}">
                                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? ($item->name ?? $item->title) }}" class="img-fluid">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title h2 mb-3">{{ $item->name ?? $item->title }}</h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary">
                            @if($item->category)
                                {{ $item->category->name }}
                            @else
                                @if($type == 'book')
                                    Sách
                                @else
                                    Sản phẩm
                                @endif
                            @endif
                        </span>
                        
                        @if($type == 'book' && $item->publication_date && $item->publication_date->diffInDays(now()) < 30)
                            <span class="badge bg-danger">Mới</span>
                        @endif
                    </div>

                    <!-- Thông tin đặc thù cho sách -->
                    @if($type == 'book')
                    <div class="d-flex align-items-center mb-3">
                        <span class="me-3">Tác giả: <a href="{{ route('books.by_author', $item->author->slug) }}" class="text-decoration-none">{{ $item->author->name }}</a></span>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($item->ratings_avg_rating ?? 0))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="ms-1 text-muted">({{ $item->ratings_count ?? 0 }} đánh giá)</span>
                        </div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h2 class="text-primary mb-0">{{ number_format($item->price, 0, ',', '.') }} VNĐ</h2>
                        @if($item->stock <= 0)
                        <span class="text-danger">Hết hàng</span>
                        @else
                        <span class="text-success">Còn {{ $item->stock }} {{ $type == 'book' ? 'cuốn' : 'sản phẩm' }}</span>
                        @endif
                    </div>

                    <!-- Giảm giá cho sách -->
                    @if($type == 'book' && isset($item->discount) && $item->discount > 0)
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-tags me-2"></i>
                        <div>Giảm giá {{ $item->discount }}% - Tiết kiệm {{ number_format($item->price * $item->discount / 100, 0, ',', '.') }}đ</div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <h5>Mô tả {{ $type == 'book' ? 'sách' : 'sản phẩm' }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                    </div>

                    @if($item->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        @if($type == 'book')
                            <input type="hidden" name="book_id" value="{{ $item->id }}">
                        @else
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                        @endif
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-decrease">-</button>
                                    <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" max="{{ $item->stock }}">
                                    <button type="button" class="btn btn-outline-secondary btn-sm btn-increase">+</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ $type == 'book' ? 'Sách' : 'Sản phẩm' }} tạm hết hàng
                    </div>
                    @endif

                    <!-- Thông tin sản phẩm/sách -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin {{ $type == 'book' ? 'sách' : 'sản phẩm' }}</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    @if($type == 'book')
                                        <!-- Thông tin sách -->
                                        @if($item->isbn)
                                        <tr>
                                            <th>ISBN</th>
                                            <td>{{ $item->isbn }}</td>
                                        </tr>
                                        @endif
                                        @if($item->pages)
                                        <tr>
                                            <th>Số trang</th>
                                            <td>{{ $item->pages }}</td>
                                        </tr>
                                        @endif
                                        @if($item->publication_date)
                                        <tr>
                                            <th>Năm xuất bản</th>
                                            <td>{{ $item->publication_date ? $item->publication_date->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                        </tr>
                                        @endif
                                        @if($item->language)
                                        <tr>
                                            <th>Ngôn ngữ</th>
                                            <td>{{ $item->language ?? 'Tiếng Việt' }}</td>
                                        </tr>
                                        @endif
                                    @else
                                        <!-- Thông tin sản phẩm -->
                                        @if($item->supplier)
                                        <tr>
                                            <th>Nhà cung cấp:</th>
                                            <td>{{ $item->supplier }}</td>
                                        </tr>
                                        @endif
                                        @if($item->brand)
                                        <tr>
                                            <th>Thương hiệu:</th>
                                            <td>{{ $item->brand }}</td>
                                        </tr>
                                        @endif
                                        @if($item->brand_origin)
                                        <tr>
                                            <th>Xuất xứ thương hiệu:</th>
                                            <td>{{ $item->brand_origin }}</td>
                                        </tr>
                                        @endif
                                        @if($item->manufacturing_place)
                                        <tr>
                                            <th>Nơi sản xuất:</th>
                                            <td>{{ $item->manufacturing_place }}</td>
                                        </tr>
                                        @endif
                                        @if($item->color)
                                        <tr>
                                            <th>Màu sắc:</th>
                                            <td>{{ $item->color }}</td>
                                        </tr>
                                        @endif
                                        @if($item->material)
                                        <tr>
                                            <th>Chất liệu:</th>
                                            <td>{{ $item->material }}</td>
                                        </tr>
                                        @endif
                                        @if($item->weight)
                                        <tr>
                                            <th>Trọng lượng:</th>
                                            <td>{{ $item->weight }} gr</td>
                                        </tr>
                                        @endif
                                        @if($item->dimensions)
                                        <tr>
                                            <th>Kích thước bao bì:</th>
                                            <td>{{ $item->dimensions }}</td>
                                        </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Thông tin đồ chơi nếu là sản phẩm thuộc danh mục đồ chơi -->
                    @if($type == 'product' && $item->category && $item->category->name == 'Đồ chơi')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin đồ chơi</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    @if($item->age_recommendation)
                                    <tr>
                                        <th>Độ tuổi:</th>
                                        <td>{{ $item->age_recommendation }}</td>
                                    </tr>
                                    @endif
                                    @if($item->publish_year)
                                    <tr>
                                        <th>Năm xuất bản:</th>
                                        <td>{{ $item->publish_year }}</td>
                                    </tr>
                                    @endif
                                    @if($item->technical_specs)
                                    <tr>
                                        <th>Thông số kỹ thuật:</th>
                                        <td>{{ $item->technical_specs }}</td>
                                    </tr>
                                    @endif
                                    @if($item->warnings)
                                    <tr>
                                        <th>Thông tin cảnh báo:</th>
                                        <td>{{ $item->warnings }}</td>
                                    </tr>
                                    @endif
                                    @if($item->usage_instructions)
                                    <tr>
                                        <th>Hướng dẫn sử dụng:</th>
                                        <td>{{ $item->usage_instructions }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Thông tin dụng cụ học tập -->
                    @if($type == 'product' && $item->category && $item->category->name == 'Dụng cụ học tập')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin dụng cụ học tập</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    @if($item->ink_color)
                                    <tr>
                                        <th>Màu mực:</th>
                                        <td>{{ $item->ink_color }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Chia sẻ mạng xã hội -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Chia sẻ</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="#" class="btn btn-outline-primary btn-sm" title="Chia sẻ Facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="#" class="btn btn-outline-danger btn-sm" title="Chia sẻ Pinterest">
                                    <i class="fab fa-pinterest"></i> Pinterest
                                </a>
                                <a href="#" class="btn btn-outline-info btn-sm" title="Chia sẻ Twitter">
                                    <i class="fab fa-twitter"></i> Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Items -->
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">{{ $type == 'book' ? 'Sách' : 'Sản phẩm' }} liên quan</h3>
        </div>

        @if($type == 'book')
            @foreach($relatedBooks as $relatedBook)
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100 related-book-card">
                    <a href="{{ route('books.show', $relatedBook->slug) }}">
                        <img src="{{ Storage::url($relatedBook->primary_image_path ?? 'images/book-placeholder.jpg') }}" class="card-img-top" alt="{{ $relatedBook->title }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('books.show', $relatedBook->slug) }}" class="text-decoration-none">{{ $relatedBook->title }}</a>
                        </h5>
                        <p class="card-text text-muted">{{ $relatedBook->author->name }}</p>
                        <h6 class="card-subtitle mb-2 text-primary">{{ number_format($relatedBook->price, 0, ',', '.') }}đ</h6>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-md-3 col-6 mb-4">
                <div class="card h-100">
                    <a href="{{ route('products.show', $relatedProduct->slug) }}">
                        <img src="{{ Storage::url($relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-decoration-none">{{ $relatedProduct->name }}</a>
                        </h5>
                        <h6 class="card-subtitle mb-2 text-primary">{{ number_format($relatedProduct->price, 0, ',', '.') }}đ</h6>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- Ratings and Reviews for Books -->
    @if($type == 'book' && isset($reviews))
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Đánh giá sách</h5>
                </div>
                <div class="card-body">
                    @if($reviews && $reviews->count() > 0)
                        @foreach($reviews as $review)
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('images/user-placeholder.jpg') }}" alt="User" class="review-avatar me-3">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="mb-0">{{ $review->user->name }}</h5>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="star-rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center">Chưa có đánh giá nào cho cuốn sách này.</p>
                    @endif
                    
                    @auth
                        <hr class="my-4">
                        <h5 class="mb-3">Viết đánh giá</h5>
                        <form action="{{ route('reviews.store', $item->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rating" class="form-label">Đánh giá của bạn</label>
                                <div class="rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" />
                                    <label for="star{{ $i }}"><i class="far fa-star"></i></label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Nhận xét</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                        </form>
                    @else
                        <hr class="my-4">
                        <div class="alert alert-info">
                            <p class="mb-0">Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để viết đánh giá.</p>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox.min.js"></script>
<script>
$(document).ready(function() {
    // Xử lý đổi ảnh khi click vào thumbnail
    $('.thumb-item').click(function() {
        if (!$(this).hasClass('thumb-more') && !$(this).hasClass('modal-thumb')) {
            var imageSrc = $(this).data('image');
            changeMainImage(imageSrc);
        }
    });
    
    // Xử lý thumbnail trong modal
    $('.modal-thumb').click(function() {
        var imageSrc = $(this).data('image');
        changeMainImage(imageSrc);
        $('#allImagesModal').modal('hide');
    });
    
    // Mở modal khi click vào nút xem thêm
    $('#showMoreImages').click(function() {
        $('#allImagesModal').modal('show');
    });
    
    // Khởi tạo Lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'disableScrolling': true,
        'fitImagesInViewport': true
    });
    
    // Xử lý nút tăng/giảm số lượng
    $('.btn-decrease').click(function() {
        updateQuantity(-1);
    });
    
    $('.btn-increase').click(function() {
        updateQuantity(1);
    });
});

function changeMainImage(imageSrc) {
    // Cập nhật ảnh chính
    $('.main-image').attr('src', imageSrc);
    $('.main-image-container > a').attr('href', imageSrc);
    
    // Highlight thumbnail đang chọn
    $('.product-thumbnail img').removeClass('border-primary').addClass('border');
    $('.product-thumbnail img[src="'+imageSrc+'"]').removeClass('border').addClass('border-primary');
}

function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.max);
    const newValue = currentValue + change;
    
    if (newValue >= 1 && newValue <= maxValue) {
        input.value = newValue;
    }
}
</script>
@endpush 