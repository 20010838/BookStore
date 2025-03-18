@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Quản lý danh mục</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm danh mục mới
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Cấp</th>
                            <th>Danh mục cha</th>
                            <th>Số lượng sách</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories ?? [] as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>
                                @if($category->level > 1)
                                    @for($i = 1; $i < $category->level; $i++)
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                    @endfor
                                    @if($category->level == 2)
                                        ├─
                                    @else
                                        └─
                                    @endif
                                @endif
                                {{ $category->name }}
                            </td>
                            <td>
                                @if($category->level == 1)
                                    <span class="badge bg-primary">Cấp 1 (Gốc)</span>
                                @elseif($category->level == 2)
                                    <span class="badge bg-info">Cấp 2 (Con)</span>
                                @elseif($category->level == 3)
                                    <span class="badge bg-secondary">Cấp 3 (Cháu)</span>
                                @endif
                            </td>
                            <td>
                                @if($category->parent)
                                    <a href="{{ route('admin.categories.show', $category->parent->id) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>{{ $category->books_count ?? 0 }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Không có danh mục nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($categories) && $categories->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 