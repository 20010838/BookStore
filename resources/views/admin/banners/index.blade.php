@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Banner</h1>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Thêm mới Banner
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Banner</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="bannerTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="10%">Hình ảnh</th>
                            <th width="15%">Tiêu đề</th>
                            <th width="15%">Loại banner</th>
                            <th width="10%">Liên kết</th>
                            <th width="10%">Vị trí</th>
                            <th width="10%">Trạng thái</th>
                            <th width="15%">Chức năng</th>
                        </tr>
                    </thead>
                    <tbody id="banner-list">
                        @forelse($banners as $banner)
                        <tr data-id="{{ $banner->id }}" data-type="{{ $banner->type }}">
                            <td>{{ $banner->id }}</td>
                            <td>
                                <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" class="img-thumbnail" style="max-height: 80px;">
                            </td>
                            <td>
                                @if($banner->title)
                                <strong>{{ $banner->title }}</strong>
                                @else
                                <span class="text-muted">(Không có tiêu đề)</span>
                                @endif
                                @if($banner->subtitle)
                                <br><small class="text-muted">{{ $banner->subtitle }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $types[$banner->type] ?? $banner->type }}</span>
                                @if($banner->size)
                                <br><small class="text-muted">{{ $banner->size }}</small>
                                @endif
                            </td>
                            <td>
                                @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank" class="small">{{ Str::limit($banner->link_url, 30) }}</a>
                                @else
                                <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-light move-up mr-1" title="Di chuyển lên"><i class="fas fa-arrow-up"></i></button>
                                    <span class="mx-2">{{ $banner->position }}</span>
                                    <button class="btn btn-sm btn-light move-down" title="Di chuyển xuống"><i class="fas fa-arrow-down"></i></button>
                                </div>
                            </td>
                            <td>
                                <form action="{{ route('admin.banners.toggle-active', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $banner->is_active ? 'Đang hiển thị' : 'Đã ẩn' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có banner nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Di chuyển banner lên
        $('.move-up').on('click', function() {
            const row = $(this).closest('tr');
            const type = row.data('type');
            const prevRow = row.prevAll(`tr[data-type="${type}"]`).first();
            
            if (prevRow.length > 0) {
                // Swap rows in UI
                prevRow.before(row);
                
                // Update positions in database
                updatePositions(type);
            }
        });
        
        // Di chuyển banner xuống
        $('.move-down').on('click', function() {
            const row = $(this).closest('tr');
            const type = row.data('type');
            const nextRow = row.nextAll(`tr[data-type="${type}"]`).first();
            
            if (nextRow.length > 0) {
                // Swap rows in UI
                nextRow.after(row);
                
                // Update positions in database
                updatePositions(type);
            }
        });
        
        // Cập nhật vị trí
        function updatePositions(type) {
            const positions = {};
            
            // Lấy vị trí mới của từng banner theo type
            $(`tr[data-type="${type}"]`).each(function(index) {
                const id = $(this).data('id');
                positions[id] = index + 1;
                
                // Cập nhật vị trí trong giao diện
                $(this).find('td:nth-child(6) span').text(index + 1);
            });
            
            // Gửi AJAX request để cập nhật vị trí trong database
            $.ajax({
                url: "{{ route('admin.banners.update-positions') }}",
                method: 'POST',
                data: {
                    positions: positions,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        // Thông báo thành công (nếu cần)
                    }
                },
                error: function(xhr) {
                    console.error('Error updating positions:', xhr.responseText);
                    // Hiển thị thông báo lỗi (nếu cần)
                }
            });
        }
    });
</script>
@endsection 