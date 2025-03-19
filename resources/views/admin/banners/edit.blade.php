@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa Banner</h1>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin Banner</h6>
            <span class="badge badge-{{ $banner->is_active ? 'success' : 'secondary' }}">
                {{ $banner->is_active ? 'Đang hiển thị' : 'Đã ẩn' }}
            </span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="title-required text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $banner->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted title-note d-none">
                                Tiêu đề không bắt buộc đối với banner chính (slider).
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Loại banner <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" {{ old('type', $banner->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Chọn vị trí hiển thị của banner.<br>
                                - <strong>Banner chính (Slider)</strong>: Banner lớn hiển thị trong slider chính (có thể thêm nhiều ảnh)<br>
                                - <strong>Banner phải bên trên</strong>: Banner nhỏ hiển thị ở góc phải trên cùng<br>
                                - <strong>Banner phải bên dưới</strong>: Banner nhỏ hiển thị ở góc phải dưới cùng<br>
                                - <strong>Banner dưới</strong>: Banner nhỏ hiển thị ở hàng dưới (4 banner)
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="size">Kích thước đề xuất</label>
                            <input type="text" class="form-control @error('size') is-invalid @enderror" id="size" name="size" value="{{ old('size', $banner->size) }}">
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Tùy theo loại banner, kích thước đề xuất:<br>
                                - Banner chính: 1200x400 pixels<br>
                                - Banner phải: 400x200 pixels<br>
                                - Banner dưới: 300x250 pixels
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="subtitle">Tiêu đề phụ</label>
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="link_url">Liên kết URL</label>
                            <input type="url" class="form-control @error('link_url') is-invalid @enderror" id="link_url" name="link_url" value="{{ old('link_url', $banner->link_url) }}" placeholder="https://example.com">
                            @error('link_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="button_text">Nội dung nút</label>
                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" placeholder="Mua ngay">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="position">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $banner->position) }}" min="1">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Hiển thị Banner</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Hình ảnh Banner</label>
                            <div class="mb-3">
                                <img src="{{ Storage::url($banner->image_path) }}" alt="{{ $banner->title }}" class="img-fluid img-thumbnail" style="max-height: 200px;">
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Chọn file ảnh mới (nếu muốn thay đổi)</label>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Định dạng cho phép: jpg, jpeg, png, gif. Kích thước tối đa: 2MB.<br>
                                Kích thước khuyến nghị: 1200x400 pixels.
                            </small>
                            <div class="mt-3">
                                <img id="preview" src="#" alt="Preview" style="max-width: 100%; max-height: 300px; display: none;">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $banner->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật Banner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Hiển thị tên file đã chọn
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            
            // Preview ảnh
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Cập nhật yêu cầu tiêu đề dựa trên loại banner
        function updateTitleRequirement() {
            const bannerType = $('#type').val();
            if (bannerType === 'main_slider') {
                $('.title-required').addClass('d-none');
                $('.title-note').removeClass('d-none');
                $('#title').removeAttr('required');
            } else {
                $('.title-required').removeClass('d-none');
                $('.title-note').addClass('d-none');
                $('#title').attr('required', 'required');
            }
        }
        
        // Chạy một lần khi trang tải
        updateTitleRequirement();
        
        // Chạy khi thay đổi loại banner
        $('#type').on('change', updateTitleRequirement);
    });
</script>
@endsection 