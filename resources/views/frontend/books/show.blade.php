@extends('layouts.frontend')

@section('title', $book->title)

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<style>
    .book-cover {
        max-height: 400px;
        object-fit: contain;
    }
    
    .book-details {
        position: sticky;
        top: 100px;
    }
    
    .book-info-table tr td:first-child {
        width: 150px;
        font-weight: 600;
    }
    
    .quantity-input {
        width: 70px;
    }
    
    /* Style cho phần điều chỉnh số lượng */
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
    
    .review-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .star-rating .fas {
        color: #ffc107;
    }
    
    .star-rating .far {
        color: #e4e5e9;
    }
    
    .rating-input:not(:checked) ~ label {
        color: #ddd;
    }
    
    .rating-input:checked ~ label {
        color: #ffc107;
    }
    
    .rating-input:hover ~ label {
        color: #ffc107;
    }
    
    .related-book-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .related-book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .related-book-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    
    /* Image Gallery Styles */
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
    
    .thumbnails-container {
        margin-top: 15px;
    }
    
    /* New gallery thumbnails style */
    .product-gallery-thumbs {
        margin-top: 15px;
    }
    
    .thumb-item {
        cursor: pointer;
        position: relative;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #eee;
        transition: all 0.3s ease;
    }
    
    .thumb-item.active {
        border-color: #0d6efd;
    }
    
    .thumb-item:hover {
        border-color: #0d6efd;
    }
    
    .thumb-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .thumb-more {
        cursor: pointer;
        position: relative;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #eee;
        background-color: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .thumb-more:hover {
        background-color: rgba(0,0,0,0.8);
    }
    
    .thumb-more span {
        color: white;
        font-size: 20px;
        font-weight: bold;
    }
    
    /* Modal gallery styles */
    #allImagesModal .thumb-item {
        height: 120px;
    }
    
    .thumbnail-swiper {
        position: relative;
        height: 80px;
    }
    
    .thumbnail-item {
        height: 80px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .thumbnail-item.active {
        border-color: #0d6efd;
    }
    
    .thumbnail-item img {
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
    
    .swiper-button-next, .swiper-button-prev {
        color: #0d6efd;
        font-weight: bold;
    }
    
    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 20px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Sách</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('books.by_category', $book->category->slug) }}">{{ $book->category->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Chi tiết sách -->
        <div class="col-lg-4 col-md-5">
            <div class="book-gallery mb-4">
                <!-- Main Image with Lightbox -->
                <div class="main-image-container mb-3">
                    <a href="{{ Storage::url($book->primary_image_path ?? 'images/book-placeholder.jpg') }}" data-lightbox="book-gallery" data-title="{{ $book->title }}">
                        <img src="{{ Storage::url($book->primary_image_path ?? 'images/book-placeholder.jpg') }}" class="main-image" alt="{{ $book->title }}">
                        <div class="zoom-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </a>
                </div>

                <!-- Thumbnails Gallery -->
                @if($book->images->count() > 0 || $book->cover_image)
                <div class="product-gallery-thumbs">
                    <div class="row g-2">
                        <!-- Ảnh chính -->
                        @if($book->cover_image)
                        <div class="col-3">
                            <div class="thumb-item active" onclick="document.querySelector('.main-image').src='{{ Storage::url($book->cover_image) }}'; document.querySelector('.main-image-container > a').href='{{ Storage::url($book->cover_image) }}'; document.querySelectorAll('.thumb-item').forEach(e => e.classList.remove('active')); this.classList.add('active');">
                                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}">
                            </div>
                        </div>
                        @endif
                        
                        <!-- Các ảnh phụ - hiển thị tối đa 3 ảnh -->
                        @php $remainingImagesCount = 0; @endphp
                        @foreach($book->images as $key => $image)
                            @if($key < 3)
                            <div class="col-3">
                                <div class="thumb-item" onclick="document.querySelector('.main-image').src='{{ Storage::url($image->image_path) }}'; document.querySelector('.main-image-container > a').href='{{ Storage::url($image->image_path) }}'; document.querySelectorAll('.thumb-item').forEach(e => e.classList.remove('active')); this.classList.add('active');">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? $book->title }}">
                                </div>
                            </div>
                            @else
                                @php $remainingImagesCount++; @endphp
                            @endif
                        @endforeach
                        
                        <!-- Nếu có nhiều hơn 3 ảnh thì hiển thị nút +n -->
                        @if($remainingImagesCount > 0)
                        <div class="col-3">
                            <div class="thumb-more" onclick="document.getElementById('allImagesModal').classList.add('show'); document.getElementById('allImagesModal').style.display = 'block'; document.querySelector('body').classList.add('modal-open');">
                                <span>+{{ $remainingImagesCount }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Modal hiển thị tất cả ảnh -->
            <div class="modal fade" id="allImagesModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tất cả hình ảnh</h5>
                            <button type="button" class="btn-close" onclick="document.getElementById('allImagesModal').classList.remove('show'); document.getElementById('allImagesModal').style.display = 'none'; document.querySelector('body').classList.remove('modal-open');" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                @if($book->cover_image)
                                <div class="col-4 mb-3">
                                    <div class="thumb-item" onclick="document.querySelector('.main-image').src='{{ Storage::url($book->cover_image) }}'; document.querySelector('.main-image-container > a').href='{{ Storage::url($book->cover_image) }}'; document.querySelectorAll('.thumb-item').forEach(e => e.classList.remove('active')); this.classList.add('active'); document.getElementById('allImagesModal').classList.remove('show'); document.getElementById('allImagesModal').style.display = 'none'; document.querySelector('body').classList.remove('modal-open');">
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid">
                                    </div>
                                </div>
                                @endif
                                @foreach($book->images as $image)
                                <div class="col-4 mb-3">
                                    <div class="thumb-item" onclick="document.querySelector('.main-image').src='{{ Storage::url($image->image_path) }}'; document.querySelector('.main-image-container > a').href='{{ Storage::url($image->image_path) }}'; document.querySelectorAll('.thumb-item').forEach(e => e.classList.remove('active')); this.classList.add('active'); document.getElementById('allImagesModal').classList.remove('show'); document.getElementById('allImagesModal').style.display = 'none'; document.querySelector('body').classList.remove('modal-open');">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? $book->title }}" class="img-fluid">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($book->stock > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="input-group flex-nowrap">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="decreaseQuantity">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $book->stock }}" class="form-control text-center">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="increaseQuantity">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-1"></i> Sách tạm hết hàng
                    </div>
                </div>
            </div>
            @endif
            
            <div class="card mt-4 border-0 shadow-sm">
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
        
        <div class="col-lg-8 col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex gap-2 mb-2">
                        @if($book->publication_date && $book->publication_date->diffInDays(now()) < 30)
                            <span class="badge bg-danger">Mới</span>
                        @endif
                        <span class="badge bg-primary">{{ $book->category->name }}</span>
                    </div>
                    
                    <h2 class="book-detail-title">{{ $book->title }}</h2>
                    
                    <div class="d-flex align-items-center mb-3">
                        <span class="me-3">Tác giả: <a href="{{ route('books.by_author', $book->author->id) }}" class="text-decoration-none">{{ $book->author->name }}</a></span>
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($book->ratings_avg_rating ?? 0))
                                    <i class="fas fa-star text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                            <span class="ms-1 text-muted">({{ $book->ratings_count ?? 0 }} đánh giá)</span>
                        </div>
                    </div>
                    
                    <div class="book-detail-price mb-4">{{ number_format($book->price, 0, ',', '.') }}đ</div>
                    
                    @if($book->discount > 0)
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-tags me-2"></i>
                        <div>Giảm giá {{ $book->discount }}% - Tiết kiệm {{ number_format($book->price * $book->discount / 100, 0, ',', '.') }}đ</div>
                    </div>
                    @endif
                    
                    <div class="book-description mb-4">
                        <h5 class="mb-3">Giới thiệu sách1</h5>
                        <p>{{ $book->description }}</p>
                    </div>
                    
                    <div class="book-detail-info">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{ $book->isbn }}</td>
                                </tr>
                                <tr>
                                    <th>Số trang</th>
                                    <td>{{ $book->pages }}</td>
                                </tr>
                                <tr>
                                    <th>Năm xuất bản</th>
                                    <td>{{ $book->publication_date ? $book->publication_date->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngôn ngữ</th>
                                    <td>{{ $book->language ?? 'Tiếng Việt' }}</td>
                                </tr>
                                <tr>
                                    <th>Nhà xuất bản</th>
                                    <td>{{ $book->publisher }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Phần đánh giá -->
            <div class="card mt-4 border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Đánh giá sách</h5>
                </div>
                <div class="card-body">
                    @if($book->ratings && $book->ratings->count() > 0)
                        @foreach($book->ratings as $rating)
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('images/user-placeholder.jpg') }}" alt="User" class="review-avatar me-3">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="mb-0">{{ $rating->user->name }}</h5>
                                    <small class="text-muted">{{ $rating->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div class="star-rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <p class="mb-0">{{ $rating->comment }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-center">Chưa có đánh giá nào cho cuốn sách này.</p>
                    @endif
                    
                    @auth
                        <hr class="my-4">
                        <h5 class="mb-3">Viết đánh giá</h5>
                        <form action="{{ route('reviews.store', $book->id) }}" method="POST">
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
    
    <!-- Sách liên quan -->
    <div class="related-books mt-5">
        <h3 class="section-heading">Sách liên quan</h3>
        <div class="row">
            @foreach($relatedBooks as $relatedBook)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card">
                    @if($relatedBook->publication_date && $relatedBook->publication_date->diffInDays(now()) < 30)
                        <span class="badge bg-danger">Mới</span>
                    @endif
                    
                    <div class="card-img-container">
                        <a href="{{ route('books.show', $relatedBook->slug) }}">
                            <img src="{{ $relatedBook->cover_image ? asset('storage/' . $relatedBook->cover_image) : asset('images/book-placeholder.jpg') }}" 
                                 class="card-img-top" alt="{{ $relatedBook->title }}">
                        </a>
                        
                        <div class="action-buttons">
                            <button class="action-btn add-to-wishlist" data-bs-toggle="tooltip" title="Thêm vào danh sách yêu thích">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $relatedBook->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="action-btn add-to-cart" data-bs-toggle="tooltip" title="Thêm vào giỏ hàng">
                                    <i class="fa-solid fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="product-title">
                            <a href="{{ route('books.show', $relatedBook->slug) }}">{{ $relatedBook->title }}</a>
                        </h5>
                        
                        <div class="product-author">
                            <a href="{{ route('books.by_author', $relatedBook->author->id) }}">{{ $relatedBook->author->name }}</a>
                        </div>
                        
                        <div class="rating">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($relatedBook->ratings_avg_rating ?? 0))
                                    <i class="fas fa-star"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        
                        <div class="product-price">{{ number_format($relatedBook->price, 0, ',', '.') }}đ</div>
                        
                        <a href="{{ route('books.show', $relatedBook->slug) }}" class="btn btn-details">Chi tiết</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Hình ảnh %1 / %2"
    });
    
    // Handle quantity buttons
    $('#increaseQuantity').click(function() {
        const quantityInput = $('#quantity');
        const currentVal = parseInt(quantityInput.val());
        const maxVal = parseInt(quantityInput.attr('max'));
        
        if(currentVal < maxVal) {
            quantityInput.val(currentVal + 1);
        }
    });
    
    $('#decreaseQuantity').click(function() {
        const quantityInput = $('#quantity');
        const currentVal = parseInt(quantityInput.val());
        
        if(currentVal > 1) {
            quantityInput.val(currentVal - 1);
        }
    });
});
</script>
@endpush 