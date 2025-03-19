@extends('layouts.frontend')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div class="product-gallery">
                <!-- Main Image with Lightbox -->
                <div class="main-image-container mb-3">
                    <a href="{{ Storage::url($product->image) }}" data-lightbox="product-gallery" data-title="{{ $product->name }}">
                        <img src="{{ Storage::url($product->image) }}" class="img-fluid main-image" alt="{{ $product->name }}">
                        <div class="zoom-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </a>
                </div>

                <!-- Thumbnails Gallery -->
                <div class="product-gallery-thumbs">
                    <div class="row g-2">
                        <!-- Ảnh chính -->
                        <div class="col-3">
                            <div class="thumb-item active" onclick="changeMainImage('{{ Storage::url($product->image) }}')">
                                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                            </div>
                        </div>
                        
                        <!-- Các ảnh phụ - hiển thị tối đa 3 ảnh -->
                        @php $remainingImagesCount = 0; @endphp
                        @foreach($product->images as $key => $image)
                            @if($key < 3)
                            <div class="col-3">
                                <div class="thumb-item" onclick="changeMainImage('{{ Storage::url($image->image_path) }}')">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? $product->name }}">
                                </div>
                            </div>
                            @else
                                @php $remainingImagesCount++; @endphp
                            @endif
                        @endforeach
                        
                        <!-- Nếu có nhiều hơn 3 ảnh thì hiển thị nút +n -->
                        @if($remainingImagesCount > 0)
                        <div class="col-3">
                            <div class="thumb-more" onclick="showAllImages()">
                                <span>+{{ $remainingImagesCount }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal hiển thị tất cả ảnh -->
            <div class="modal fade" id="allImagesModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tất cả hình ảnh</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-4 mb-3">
                                    <div class="thumb-item" onclick="changeMainImageAndClose('{{ Storage::url($product->image) }}')">
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                                    </div>
                                </div>
                                @foreach($product->images as $image)
                                <div class="col-4 mb-3">
                                    <div class="thumb-item" onclick="changeMainImageAndClose('{{ Storage::url($image->image_path) }}')">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? $product->name }}" class="img-fluid">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title h2 mb-3">{{ $product->name }}</h1>
                    
                    <div class="mb-3">
                        <span class="badge bg-primary">
                            @if($product->category)
                                {{ $product->category->name }}
                            @else
                                Sản phẩm
                            @endif
                        </span>
                    </div>

                    <div class="mb-4">
                        <h2 class="text-primary mb-0">{{ number_format($product->price) }} VNĐ</h2>
                        @if($product->stock <= 0)
                        <span class="text-danger">Hết hàng</span>
                        @else
                        <span class="text-success">Còn {{ $product->stock }} sản phẩm</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5>Mô tả sản phẩm</h5>
                        <p class="card-text">{{ $product->description }}</p>
                    </div>

                    @if($product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(-1)">-</button>
                                    <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary w-100">Thêm vào giỏ hàng</button>
                            </div>
                        </div>
                    </form>
                    @endif

                    <!-- Product Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    @if($product->supplier)
                                    <tr>
                                        <th>Nhà cung cấp:</th>
                                        <td>{{ $product->supplier }}</td>
                                    </tr>
                                    @endif
                                    @if($product->brand)
                                    <tr>
                                        <th>Thương hiệu:</th>
                                        <td>{{ $product->brand }}</td>
                                    </tr>
                                    @endif
                                    @if($product->brand_origin)
                                    <tr>
                                        <th>Xuất xứ thương hiệu:</th>
                                        <td>{{ $product->brand_origin }}</td>
                                    </tr>
                                    @endif
                                    @if($product->manufacturing_place)
                                    <tr>
                                        <th>Nơi sản xuất:</th>
                                        <td>{{ $product->manufacturing_place }}</td>
                                    </tr>
                                    @endif
                                    @if($product->color)
                                    <tr>
                                        <th>Màu sắc:</th>
                                        <td>{{ $product->color }}</td>
                                    </tr>
                                    @endif
                                    @if($product->material)
                                    <tr>
                                        <th>Chất liệu:</th>
                                        <td>{{ $product->material }}</td>
                                    </tr>
                                    @endif
                                    @if($product->weight)
                                    <tr>
                                        <th>Trọng lượng:</th>
                                        <td>{{ $product->weight }} gr</td>
                                    </tr>
                                    @endif
                                    @if($product->dimensions)
                                    <tr>
                                        <th>Kích thước bao bì:</th>
                                        <td>{{ $product->dimensions }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Category-specific Information -->
                    @if($product->category && $product->category->name == 'Đồ chơi')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin đồ chơi</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    @if($product->age_recommendation)
                                    <tr>
                                        <th>Độ tuổi:</th>
                                        <td>{{ $product->age_recommendation }}</td>
                                    </tr>
                                    @endif
                                    @if($product->publish_year)
                                    <tr>
                                        <th>Năm xuất bản:</th>
                                        <td>{{ $product->publish_year }}</td>
                                    </tr>
                                    @endif
                                    @if($product->technical_specs)
                                    <tr>
                                        <th>Thông số kỹ thuật:</th>
                                        <td>{{ $product->technical_specs }}</td>
                                    </tr>
                                    @endif
                                    @if($product->warnings)
                                    <tr>
                                        <th>Thông tin cảnh báo:</th>
                                        <td>{{ $product->warnings }}</td>
                                    </tr>
                                    @endif
                                    @if($product->usage_instructions)
                                    <tr>
                                        <th>Hướng dẫn sử dụng:</th>
                                        <td>{{ $product->usage_instructions }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @elseif($product->category && $product->category->name == 'Dụng cụ học tập')
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin dụng cụ học tập</h5>
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    @if($product->ink_color)
                                    <tr>
                                        <th>Màu mực:</th>
                                        <td>{{ $product->ink_color }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="mb-4">Sản phẩm liên quan</h2>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            <img src="{{ Storage::url($relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                            @if($relatedProduct->stock <= 0)
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-50 d-flex align-items-center justify-content-center">
                                <span class="text-white fw-bold">Hết hàng</span>
                            </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            <p class="card-text">{{ Str::limit($relatedProduct->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">{{ number_format($relatedProduct->price) }} VNĐ</span>
                                @if($relatedProduct->stock > 0)
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="btn btn-primary">Chi tiết</a>
                                @else
                                <button class="btn btn-secondary" disabled>Hết hàng</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css">
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
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox.min.js"></script>
<script>
function updateQuantity(change) {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.max);
    const newValue = currentValue + change;
    
    if (newValue >= 1 && newValue <= maxValue) {
        input.value = newValue;
    }
}

function changeMainImage(imageSrc) {
    const mainImage = document.querySelector('.main-image');
    const mainImageLink = document.querySelector('.main-image-container a');
    
    // Cập nhật đường dẫn ảnh
    mainImage.src = imageSrc;
    mainImageLink.href = imageSrc;
    
    // Cập nhật trạng thái active
    const thumbItems = document.querySelectorAll('.thumb-item');
    thumbItems.forEach(item => {
        if (item.querySelector('img').src === imageSrc) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

function changeMainImageAndClose(imageSrc) {
    changeMainImage(imageSrc);
    
    // Đóng modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('allImagesModal'));
    if (modal) {
        modal.hide();
    }
}

function showAllImages() {
    const modal = new bootstrap.Modal(document.getElementById('allImagesModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lightbox
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'disableScrolling': true,
        'fitImagesInViewport': true
    });
});
</script>
@endpush

@endsection 