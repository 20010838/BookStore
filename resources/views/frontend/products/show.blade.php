@extends('layouts.frontend')

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
                <div class="thumbnails-container">
                    <div class="swiper-container thumbnail-swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide thumbnail-item active" data-index="0">
                                <img src="{{ Storage::url($product->image) }}" class="img-thumbnail" 
                                     alt="{{ $product->name }}" data-src="{{ Storage::url($product->image) }}">
                            </div>
                            @foreach($product->images as $key => $image)
                            <div class="swiper-slide thumbnail-item" data-index="{{ $key + 1 }}">
                                <img src="{{ Storage::url($image->image_path) }}" class="img-thumbnail" 
                                     alt="{{ $image->caption ?? $product->name }}" data-src="{{ Storage::url($image->image_path) }}">
                            </div>
                            @endforeach
                        </div>
                        <!-- Navigation buttons -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css">
<style>
    .product-gallery {
        position: relative;
    }
    
    .main-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background-color: #f8f9fa;
        transition: transform 0.3s ease;
    }
    
    .main-image-container:hover .main-image {
        transform: scale(1.03);
    }
    
    .zoom-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: rgba(255,255,255,0.8);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .main-image-container:hover .zoom-overlay {
        opacity: 1;
    }
    
    .thumbnail-swiper {
        height: 100px;
        padding: 0 30px;
    }
    
    .thumbnail-item {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }
    
    .thumbnail-item.active {
        opacity: 1;
        border: 2px solid #007bff;
    }
    
    .thumbnail-item:hover {
        opacity: 1;
    }
    
    .thumbnail-item img {
        height: 80px;
        width: 100%;
        object-fit: cover;
    }
    
    .swiper-button-next, .swiper-button-prev {
        color: #007bff;
        width: 30px;
        height: 30px;
    }
    
    .swiper-button-next:after, .swiper-button-prev:after {
        font-size: 20px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
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

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Swiper
    const swiper = new Swiper('.thumbnail-swiper', {
        slidesPerView: 4,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 3,
                spaceBetween: 5
            },
            // when window width is >= 480px
            480: {
                slidesPerView: 3,
                spaceBetween: 10
            },
            // when window width is >= 640px
            640: {
                slidesPerView: 4,
                spaceBetween: 10
            }
        }
    });

    // Thumbnail click handler
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.querySelector('.main-image');
    const mainImageLink = document.querySelector('.main-image-container a');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Update active state
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update main image
            const imgSrc = this.querySelector('img').getAttribute('data-src');
            mainImage.src = imgSrc;
            mainImageLink.href = imgSrc;
        });
    });

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