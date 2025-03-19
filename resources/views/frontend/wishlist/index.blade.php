@extends('layouts.frontend')

@section('title', 'Danh sách yêu thích')

@section('styles')
<style>
    .wishlist-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .img-container {
        height: 200px;
        overflow: hidden;
    }
    
    .img-container img {
        object-fit: cover;
        height: 100%;
        width: 100%;
        transition: transform 0.5s ease;
    }
    
    .wishlist-card:hover .img-container img {
        transform: scale(1.05);
    }
    
    .action-buttons .btn {
        margin-right: 5px;
        border-radius: 50px;
        padding: 8px 15px;
    }
    
    .empty-wishlist {
        text-align: center;
        padding: 50px 0;
    }
    
    .empty-wishlist .icon {
        font-size: 5rem;
        color: #e0e0e0;
        margin-bottom: 20px;
    }
    
    .price {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.2rem;
    }
    
    .badge-added {
        background-color: var(--light-gray);
        color: #6c757d;
        font-weight: normal;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="bg-white p-4 rounded shadow-custom mb-4 animate__animated animate__fadeIn">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-heart text-danger me-2"></i>Danh sách yêu thích</h2>
                    
                    @if(count($books) > 0)
                        <form action="{{ route('wishlist.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa tất cả sách khỏi danh sách yêu thích?')">
                                <i class="fas fa-trash me-2"></i>Xóa tất cả
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if(count($books) > 0)
        <div class="row">
            @foreach($books as $book)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card wishlist-card animate__animated animate__fadeIn">
                        <div class="img-container">
                            <img src="{{ $book->cover_image ? asset('storage/' . $book->cover_image) : asset('images/book-placeholder.png') }}" alt="{{ $book->title }}">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $book->title }}</h5>
                            <p class="card-text text-muted mb-2">
                                <i class="fas fa-user-edit me-1"></i> {{ $book->author->name }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price">{{ number_format($book->price) }}đ</span>
                                <span class="badge-added">
                                    <i class="fas fa-clock me-1"></i> Thêm: {{ \Carbon\Carbon::parse($wishlist[$book->id]['added_at'])->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('books.show', $book->slug) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                                <div class="action-buttons">
                                    @if($book->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('wishlist.remove', $book->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="empty-wishlist animate__animated animate__fadeIn">
                    <div class="icon">
                        <i class="far fa-heart"></i>
                    </div>
                    <h3 class="mb-3">Danh sách yêu thích trống</h3>
                    <p class="text-muted mb-4">Bạn chưa thêm sách nào vào danh sách yêu thích.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-primary">
                        <i class="fas fa-book me-2"></i>Khám phá sách ngay
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 