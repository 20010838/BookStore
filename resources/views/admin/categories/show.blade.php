@extends('layouts.admin')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chi tiết danh mục</h1>
        <div>
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">{{ $category->name }}</h5>
                    <p class="card-text">{{ $category->description ?? 'Không có mô tả.' }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Slug: {{ $category->slug }}</span>
                        <span class="badge bg-primary">{{ $category->books_count ?? 0 }} sách</span>
                    </div>
                    
                    @if($category->level > 1)
                    <div class="mt-3">
                        <h6>Phân cấp danh mục:</h6>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                @foreach($ancestors as $ancestor)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.categories.show', $ancestor->id) }}">{{ $ancestor->name }}</a>
                                </li>
                                @endforeach
                                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    @endif
                    
                    @if($category->level < 3 && $category->children->count() > 0)
                    <div class="mt-3">
                        <h6>Danh mục con:</h6>
                        <ul class="list-group">
                            @foreach($category->children as $child)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.categories.show', $child->id) }}">{{ $child->name }}</a>
                                <span class="badge bg-primary rounded-pill">{{ $child->books_count ?? $child->books->count() ?? 0 }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin danh mục</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 200px;">ID</th>
                                    <td>{{ $category->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên danh mục</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Slug</th>
                                    <td>{{ $category->slug }}</td>
                                </tr>
                                <tr>
                                    <th>Cấp danh mục</th>
                                    <td>
                                        @if($category->level == 1)
                                            <span class="badge bg-primary">Cấp 1 (Gốc)</span>
                                        @elseif($category->level == 2)
                                            <span class="badge bg-info">Cấp 2 (Con)</span>
                                        @elseif($category->level == 3)
                                            <span class="badge bg-secondary">Cấp 3 (Cháu)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Danh mục cha</th>
                                    <td>
                                        @if($category->parent)
                                            <a href="{{ route('admin.categories.show', $category->parent->id) }}">{{ $category->parent->name }}</a>
                                        @else
                                            <span class="text-muted">Không có (Danh mục gốc)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Đường dẫn phân cấp</th>
                                    <td><code>{{ $category->path }}</code></td>
                                </tr>
                                <tr>
                                    <th>Số lượng danh mục con</th>
                                    <td>{{ $category->children->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Số lượng sách</th>
                                    <td>{{ $category->books_count ?? $category->books->count() ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật</th>
                                    <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sách trong danh mục</h6>
        </div>
        <div class="card-body">
            @if(isset($category->books) && $category->books->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ảnh bìa</th>
                                <th>Tiêu đề</th>
                                <th>Tác giả</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->books as $book)
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
                                <td>{{ $book->author->name ?? 'N/A' }}</td>
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
                <p class="text-center">Không có sách nào trong danh mục này.</p>
            @endif
        </div>
    </div>
</div>
@endsection 