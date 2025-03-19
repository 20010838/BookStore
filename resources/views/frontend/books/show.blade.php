@extends('layouts.frontend')

@section('title', $book->title)

@section('styles')
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
            <div class="card border-0 shadow-sm">
                <div class="card-img-container p-4 bg-white d-flex align-items-center justify-content-center">
                    <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/book-placeholder.jpg') }}" 
                         class="img-fluid book-detail-img" alt="{{ $book->title }}">
                </div>
                @if($book->stock > 0)
                <div class="card-footer bg-white border-0 pb-3 d-flex justify-content-between">
                    <form action="{{ route('cart.add') }}" method="POST" class="d-flex w-100 gap-2">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="decreaseQuantity">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $book->stock }}" class="form-control text-center">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="increaseQuantity">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                        </button>
                    </form>
                </div>
                @else
                <div class="card-footer bg-white border-0 pb-3">
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-1"></i> Sách tạm hết hàng
                    </div>
                </div>
                @endif
            </div>
            
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
                        <h5 class="mb-3">Giới thiệu sách</h5>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nút tăng/giảm số lượng
        const quantityInput = document.getElementById('quantity');
        const decreaseBtn = document.getElementById('decreaseQuantity');
        const increaseBtn = document.getElementById('increaseQuantity');
        const max = parseInt(quantityInput.getAttribute('max'));
        
        decreaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < max) {
                quantityInput.value = currentValue + 1;
            }
        });
        
        // Xử lý đánh giá sao
        const ratingInputs = document.querySelectorAll('.rating-input input');
        const ratingLabels = document.querySelectorAll('.rating-input label');
        
        ratingInputs.forEach((input, index) => {
            input.addEventListener('change', function() {
                for (let i = 0; i < ratingLabels.length; i++) {
                    if (i >= ratingLabels.length - index - 1) {
                        ratingLabels[i].innerHTML = '<i class="fas fa-star"></i>';
                    } else {
                        ratingLabels[i].innerHTML = '<i class="far fa-star"></i>';
                    }
                }
            });
        });
        
        // Khởi tạo tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection 