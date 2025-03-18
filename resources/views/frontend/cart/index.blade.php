@extends('layouts.frontend')

@section('title', 'Giỏ hàng')

@section('styles')
<style>
    .cart-item-img {
        width: 80px;
        height: 100px;
        object-fit: cover;
    }
    
    .quantity-input {
        width: 70px;
    }
    
    .cart-summary {
        position: sticky;
        top: 100px;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Giỏ hàng của bạn</h1>
    
    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="row fw-bold">
                            <div class="col-md-6">Sản phẩm</div>
                            <div class="col-md-2 text-center">Giá</div>
                            <div class="col-md-2 text-center">Số lượng</div>
                            <div class="col-md-2 text-center">Thành tiền</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="row align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <!-- Product Info -->
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        @if($item->book_id)
                                            <img src="{{ $item->book->cover_image ? asset('storage/' . $item->book->cover_image) : asset('images/book-placeholder.jpg') }}" 
                                                 class="cart-item-img rounded me-3" alt="{{ $item->book->title }}">
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="{{ route('books.show', $item->book->slug) }}" class="text-decoration-none">
                                                        {{ $item->book->title }}
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-0">{{ $item->book->author->name }}</p>
                                        @elseif($item->product_id)
                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/product-placeholder.jpg') }}" 
                                                 class="cart-item-img rounded me-3" alt="{{ $item->product->name }}">
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h5>
                                                <p class="text-muted mb-0">
                                                    @if($item->product->category == 'toys')
                                                        Đồ chơi
                                                    @else
                                                        Dụng cụ học tập
                                                    @endif
                                                </p>
                                        @endif
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="mt-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash-alt me-1"></i> Xóa
                                                    </button>
                                                </form>
                                            </div>
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="col-md-2 text-center">
                                    <span class="fw-bold">
                                        @if($item->book_id)
                                            {{ number_format($item->book->price) }} đ
                                        @elseif($item->product_id)
                                            {{ number_format($item->product->price) }} đ
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Quantity -->
                                <div class="col-md-2 text-center">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group quantity-input mx-auto">
                                            <input type="number" name="quantity" class="form-control" 
                                                   value="{{ $item->quantity }}" min="1" 
                                                   max="{{ $item->book_id ? $item->book->stock : $item->product->stock }}"
                                                   data-item-id="{{ $item->id }}" 
                                                   data-stock="{{ $item->book_id ? $item->book->stock : $item->product->stock }}">
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Subtotal -->
                                <div class="col-md-2 text-center">
                                    <span class="fw-bold text-primary">
                                        @if($item->book_id)
                                            {{ number_format($item->quantity * $item->book->price) }} đ
                                        @elseif($item->product_id)
                                            {{ number_format($item->quantity * $item->product->price) }} đ
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('books.index') }}" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-book me-2"></i> Tiếp tục mua sách
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-puzzle-piece me-2"></i> Xem đồ chơi & học cụ
                                </a>
                            </div>
                            <form action="{{ route('cart.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm trong giỏ hàng?')">
                                    <i class="fas fa-trash-alt me-2"></i> Xóa giỏ hàng
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card cart-summary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Tổng giỏ hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tạm tính:</span>
                            <span class="fw-bold">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển:</span>
                            <span class="fw-bold">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Tổng cộng:</span>
                            <span class="fw-bold text-primary fs-5">{{ number_format($total) }} đ</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                            <i class="fas fa-credit-card me-2"></i> Tiến hành thanh toán
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card p-5 text-center">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-5x text-muted"></i>
            </div>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ hàng để tiến hành mua sắm</p>
            <div>
                <a href="{{ route('books.index') }}" class="btn btn-primary me-2">
                    <i class="fas fa-book me-2"></i> Khám phá sách
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-puzzle-piece me-2"></i> Xem đồ chơi & học cụ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-update quantity when changed
        const quantityInputs = document.querySelectorAll('.quantity-input input');
        
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const itemId = this.dataset.itemId;
                const maxStock = parseInt(this.dataset.stock);
                
                // Validate quantity
                if (parseInt(this.value) < 1) {
                    this.value = 1;
                } else if (parseInt(this.value) > maxStock) {
                    this.value = maxStock;
                    alert('Số lượng không thể vượt quá số lượng trong kho (' + maxStock + ').');
                }
                
                // Submit the form
                this.closest('form').submit();
            });
        });
    });
</script>
@endsection 