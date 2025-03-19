@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>{{ __('Thư viện ảnh') }}: {{ $book->title }}</div>
                        <a href="{{ route('gallery.index') }}" class="btn btn-sm btn-secondary">
                            {{ __('Quay lại danh sách') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $book->title }}</h5>
                                    <p class="card-text">
                                        <strong>{{ __('Tác giả') }}:</strong> {{ $book->author->name }}<br>
                                        <strong>{{ __('Thể loại') }}:</strong> {{ $book->category->name }}<br>
                                        <strong>{{ __('Số trang') }}:</strong> {{ $book->pages }}<br>
                                        <strong>{{ __('Nhà xuất bản') }}:</strong> {{ $book->publisher }}<br>
                                        <strong>{{ __('Giá') }}:</strong> {{ number_format($book->price, 0) }} VND
                                    </p>
                                    <a href="{{ route('books.show', $book->slug) }}" class="btn btn-primary">
                                        {{ __('Xem chi tiết sách') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4>{{ __('Mô tả') }}</h4>
                            <p>{{ $book->description }}</p>
                        </div>
                    </div>

                    <h4 class="mb-3">{{ __('Bộ sưu tập ảnh') }} ({{ $book->images->count() }} {{ __('ảnh') }})</h4>

                    <div class="row">
                        @forelse ($book->images as $image)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        class="card-img-top" alt="{{ $image->caption }}" 
                                        style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        @if ($image->is_primary)
                                            <span class="badge bg-primary mb-2">{{ __('Ảnh chính') }}</span>
                                        @endif
                                        <p class="card-text">{{ $image->caption }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    {{ __('Không có ảnh nào trong thư viện này.') }}
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 