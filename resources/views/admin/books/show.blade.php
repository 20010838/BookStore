@extends('layouts.admin')

@section('title', 'Chi tiết sách')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chi tiết sách</h1>
        <div>
            <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid mb-3" style="max-height: 300px;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-3" style="height: 300px;">
                            <i class="fas fa-book fa-5x"></i>
                        </div>
                    @endif
                    <h4 class="card-title">{{ $book->title }}</h4>
                    <p class="text-muted">{{ $book->author->name ?? 'N/A' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 text-primary mb-0">{{ number_format($book->price, 0, ',', '.') }} VNĐ</span>
                        @if($book->stock > 0)
                            <span class="badge bg-success">Còn hàng ({{ $book->stock }})</span>
                        @else
                            <span class="badge bg-danger">Hết hàng</span>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($book->images && $book->images->count() > 0)
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thư viện ảnh</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($book->images as $image)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="{{ $image->caption }}" style="height: 150px; object-fit: cover;">
                                <div class="card-body p-2">
                                    <p class="card-text small">{{ $image->caption }}</p>
                                    @if($image->is_primary)
                                        <span class="badge bg-primary">Ảnh chính</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin sách</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $book->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <td>{{ $book->title }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $book->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Tác giả</th>
                                    <td>
                                        @if($book->author)
                                            <a href="{{ route('admin.authors.show', $book->author->id) }}">{{ $book->author->name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Danh mục</th>
                                    <td>
                                        @if($book->category)
                                            <a href="{{ route('admin.categories.show', $book->category->id) }}">{{ $book->category->name }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Giá</th>
                                    <td>{{ number_format($book->price, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Tồn kho</th>
                                    <td>{{ $book->stock }}</td>
                                </tr>
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{ $book->isbn ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Số trang</th>
                                    <td>{{ $book->pages ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Nhà xuất bản</th>
                                    <td>{{ $book->publisher ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày xuất bản</th>
                                    <td>{{ $book->publication_date ? $book->publication_date->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngôn ngữ</th>
                                    <td>{{ $book->language ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $book->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật</th>
                                    <td>{{ $book->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mô tả</h6>
                </div>
                <div class="card-body">
                    {!! nl2br(e($book->description ?? 'Không có mô tả.')) !!}
                </div>
            </div>
        </div>
    </div>
    
    @if(isset($book->reviews) && $book->reviews->count() > 0)
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Đánh giá ({{ $book->reviews->count() }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Người dùng</th>
                            <th>Đánh giá</th>
                            <th>Nội dung</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book->reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->user->name ?? 'N/A' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </td>
                            <td>{{ Str::limit($review->content, 50) }}</td>
                            <td>
                                @if($review->status == 'pending')
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                @elseif($review->status == 'approved')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif($review->status == 'rejected')
                                    <span class="badge bg-danger">Đã từ chối</span>
                                @else
                                    <span class="badge bg-secondary">{{ $review->status }}</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 