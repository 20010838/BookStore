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
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Sách</a></li>
            <li class="breadcrumb-item"><a href="{{ route('books.by_category', $book->category->slug) }}">{{ $book->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
        </ol>
    </nav>
    
    <!-- Book Details -->
    <div class="row mb-5">
        <!-- Book Cover -->
        <div class="col-md-4 mb-4 mb-md-0">
            <img src="{{ $book->cover_image ? asset($book->cover_image) : asset('images/book-placeholder.png') }}" 
                 class="img-fluid rounded shadow book-cover" alt="{{ $book->title }}">
        </div>
        
        <!-- Book Info -->
        <div class="col-md-8">
            <div class="book-details">
                <h1 class="mb-2">{{ $book->title }}</h1>
                <p class="text-muted mb-3">
                    Tác giả: <a href="{{ route('books.by_author', $book->author->slug) }}" class="text-decoration-none">{{ $book->author->name }}</a>
                </p>
                
                <!-- Rating Summary -->
                <div class="d-flex align-items-center mb-3">
                    <div class="star-rating me-2">
                        @php
                            $avgRating = $book->reviews->avg('rating') ?? 0;
                            $fullStars = floor($avgRating);
                            $halfStar = $avgRating - $fullStars > 0.5;
                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                        @endphp
                        
                        @for($i = 0; $i < $fullStars; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        
                        @if($halfStar)
                            <i class="fas fa-star-half-alt"></i>
                        @endif
                        
                        @for($i = 0; $i < $emptyStars; $i++)
                            <i class="far fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-muted">{{ number_format($avgRating, 1) }} ({{ $book->reviews->count() }} đánh giá)</span>
                </div>
                
                <!-- Price and Stock -->
                <div class="mb-4">
                    <h3 class="text-primary mb-2">{{ number_format($book->price) }} đ</h3>
                    <span class="badge bg-{{ $book->stock > 0 ? 'success' : 'danger' }} mb-3">
                        {{ $book->stock > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </span>
                    @if($book->stock > 0)
                        <p class="text-muted">Còn {{ $book->stock }} sản phẩm</p>
                    @endif
                </div>
                
                <!-- Add to Cart Form -->
                @if($book->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        <div class="d-flex align-items-center mb-3">
                            <label for="quantity" class="me-3">Số lượng:</label>
                            <input type="number" name="quantity" id="quantity" class="form-control quantity-input me-3" 
                                   value="1" min="1" max="{{ $book->stock }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i> Thêm vào giỏ hàng
                            </button>
                        </div>
                    </form>
                @endif
                
                <!-- Short Description -->
                <div class="mb-4">
                    <h5>Mô tả ngắn:</h5>
                    <p>{{ Str::limit($book->description, 300) }}</p>
                </div>
                
                <!-- Book Details Table -->
                <table class="table book-info-table">
                    <tbody>
                        <tr>
                            <td>Danh mục:</td>
                            <td><a href="{{ route('books.by_category', $book->category->slug) }}" class="text-decoration-none">{{ $book->category->name }}</a></td>
                        </tr>
                        <tr>
                            <td>ISBN:</td>
                            <td>{{ $book->isbn }}</td>
                        </tr>
                        @if($book->pages)
                            <tr>
                                <td>Số trang:</td>
                                <td>{{ $book->pages }}</td>
                            </tr>
                        @endif
                        @if($book->publisher)
                            <tr>
                                <td>Nhà xuất bản:</td>
                                <td>{{ $book->publisher }}</td>
                            </tr>
                        @endif
                        @if($book->publication_date)
                            <tr>
                                <td>Ngày xuất bản:</td>
                                <td>{{ $book->publication_date->format('d/m/Y') }}</td>
                            </tr>
                        @endif
                        @if($book->language)
                            <tr>
                                <td>Ngôn ngữ:</td>
                                <td>{{ $book->language }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
                <!-- Social Share -->
                <div class="mt-4">
                    <h5>Chia sẻ:</h5>
                    <div class="d-flex">
                        <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-info me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn btn-outline-success me-2"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="btn btn-outline-danger"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Book Description and Reviews Tabs -->
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" id="bookTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button" role="tab" 
                            aria-controls="description" aria-selected="true">
                        Mô tả chi tiết
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button" role="tab" 
                            aria-controls="reviews" aria-selected="false">
                        Đánh giá ({{ $book->reviews->count() }})
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="bookTabContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <div class="p-4 bg-light rounded">
                        {!! nl2br(e($book->description)) !!}
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="p-4 bg-light rounded">
                        <!-- Review Form -->
                        @auth
                            @php
                                $userReview = $book->reviews->where('user_id', Auth::id())->first();
                            @endphp
                            
                            @if(!$userReview)
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0">Viết đánh giá của bạn</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('reviews.store', $book->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Đánh giá:</label>
                                                <div class="star-rating">
                                                    <div class="rating">
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="rating-input" {{ old('rating') == $i ? 'checked' : '' }}>
                                                            <label for="star{{ $i }}" class="fas fa-star"></label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Nhận xét:</label>
                                                <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Đánh giá của bạn</h5>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#editReviewModal">
                                                <i class="fas fa-edit"></i> Sửa
                                            </button>
                                            <form action="{{ route('reviews.destroy', $userReview->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex mb-3">
                                            <div class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $userReview->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ms-2 text-muted">{{ $userReview->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <p>{{ $userReview->comment }}</p>
                                    </div>
                                </div>
                                
                                <!-- Edit Review Modal -->
                                <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editReviewModalLabel">Chỉnh sửa đánh giá</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('reviews.update', $userReview->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Đánh giá:</label>
                                                        <div class="star-rating">
                                                            <div class="rating">
                                                                @for($i = 5; $i >= 1; $i--)
                                                                    <input type="radio" id="edit_star{{ $i }}" name="rating" value="{{ $i }}" class="rating-input" {{ $userReview->rating == $i ? 'checked' : '' }}>
                                                                    <label for="edit_star{{ $i }}" class="fas fa-star"></label>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="edit_comment" class="form-label">Nhận xét:</label>
                                                        <textarea class="form-control" id="edit_comment" name="comment" rows="3" required>{{ $userReview->comment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i> Vui lòng <a href="{{ route('login') }}" class="alert-link">đăng nhập</a> để viết đánh giá.
                            </div>
                        @endauth
                        
                        <!-- Reviews List -->
                        <h4 class="mb-4">{{ $book->reviews->count() }} đánh giá</h4>
                        
                        @if($book->reviews->count() > 0)
                            @foreach($book->reviews->sortByDesc('created_at') as $review)
                                @if(!$review->user_id == Auth::id())
                                    <div class="d-flex mb-4">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="https://via.placeholder.com/60" class="review-avatar" alt="{{ $review->user->name }}">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <h5 class="mb-0">{{ $review->user->name }}</h5>
                                                <span class="text-muted small">{{ $review->created_at->format('d/m/Y') }}</span>
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
                                            <p>{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-light">
                                <i class="fas fa-info-circle me-2"></i> Chưa có đánh giá nào cho sách này.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Books -->
    @if($relatedBooks->count() > 0)
        <div class="mt-5">
            <h3 class="mb-4">Sách liên quan</h3>
            <div class="row">
                @foreach($relatedBooks as $relatedBook)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card related-book-card h-100">
                            <img src="{{ $relatedBook->cover_image ? asset($relatedBook->cover_image) : asset('images/book-placeholder.png') }}" 
                                 class="card-img-top" alt="{{ $relatedBook->title }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($relatedBook->title, 40) }}</h5>
                                <p class="card-text text-muted mb-2">{{ $relatedBook->author->name }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary">{{ number_format($relatedBook->price) }} đ</span>
                                    <a href="{{ route('books.show', $relatedBook->slug) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Quantity input validation
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const maxStock = {{ $book->stock }};
        
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                } else if (parseInt(this.value) > maxStock) {
                    this.value = maxStock;
                }
            });
        }
    });
</script>
@endsection 