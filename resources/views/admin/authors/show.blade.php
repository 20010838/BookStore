@extends('layouts.admin')

@section('title', 'Chi tiết tác giả')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chi tiết tác giả</h1>
        <div>
            <a href="{{ route('admin.authors.edit', $author->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.authors.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    @if($author->photo)
                        <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="img-fluid rounded-circle mb-3" style="max-height: 200px; max-width: 200px;">
                    @else
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center mb-3 mx-auto rounded-circle" style="height: 200px; width: 200px;">
                            <i class="fas fa-user fa-5x"></i>
                        </div>
                    @endif
                    <h4 class="card-title">{{ $author->name }}</h4>
                    <p class="text-muted">{{ $author->books_count ?? 0 }} sách</p>
                    @if($author->website)
                        <a href="{{ $author->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-globe"></i> Website
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin tác giả</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $author->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên tác giả</th>
                                    <td>{{ $author->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $author->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Số lượng sách</th>
                                    <td>{{ $author->books_count ?? $author->books->count() ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Website</th>
                                    <td>
                                        @if($author->website)
                                            <a href="{{ $author->website }}" target="_blank">{{ $author->website }}</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $author->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật</th>
                                    <td>{{ $author->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tiểu sử</h6>
                </div>
                <div class="card-body">
                    {!! nl2br(e($author->bio ?? 'Không có thông tin tiểu sử.')) !!}
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sách của tác giả</h6>
        </div>
        <div class="card-body">
            @if(isset($author->books) && $author->books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ảnh bìa</th>
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($author->books as $book)
                            <tr>
                                <td>{{ $book->id }}</td>
                                <td>
                                    @if($book->cover_image)
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="img-thumbnail" style="width: 50px; height: 70px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary" style="width: 50px; height: 70px;"></div>
                                    @endif
                                </td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->category->name ?? 'N/A' }}</td>
                                <td>{{ number_format($book->price, 0, ',', '.') }} VNĐ</td>
                                <td>
                                    @if($book->stock == 0)
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @elseif($book->stock < 10)
                                        <span class="badge bg-warning">{{ $book->stock }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $book->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.books.show', $book->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Không có sách nào của tác giả này.</p>
            @endif
        </div>
    </div>
</div>
@endsection 