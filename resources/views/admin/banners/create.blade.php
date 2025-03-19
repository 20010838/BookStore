@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm mới Banner</h1>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin Banner</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" id="banner-form">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Tiêu đề <span class="title-required text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
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
                                    <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
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
                            <input type="text" class="form-control @error('size') is-invalid @enderror" id="size" name="size" value="{{ old('size') }}">
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
                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                            @error('subtitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="link_url">Liên kết URL</label>
                            <input type="url" class="form-control @error('link_url') is-invalid @enderror" id="link_url" name="link_url" value="{{ old('link_url') }}" placeholder="https://example.com">
                            @error('link_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="button_text">Nội dung nút</label>
                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', 'MUA NGAY') }}" placeholder="Mua ngay">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="position">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" min="1">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Để trống để tự động sắp xếp ở vị trí cuối cùng</small>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Hiển thị Banner</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group" id="single-image-group">
                            <label for="image">Hình ảnh Banner <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Chọn file ảnh</label>
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
                        
                        <div class="form-group d-none" id="multi-image-group">
                            <label for="images">Hình ảnh Banner (Nhiều ảnh) <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                                    id="images" name="images[]" accept="image/*" multiple>
                                <label class="custom-file-label" for="images">Chọn nhiều file ảnh</label>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Bạn có thể chọn nhiều ảnh cùng lúc. Mỗi ảnh sẽ tạo thành một banner riêng biệt.<br>
                                Định dạng cho phép: jpg, jpeg, png, gif. Kích thước tối đa: 2MB mỗi ảnh.<br>
                                Kích thước khuyến nghị: 1200x400 pixels.
                            </small>
                            <div class="mt-3" id="multi-preview">
                                <!-- Preview hình ảnh sẽ hiển thị ở đây -->
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Lưu Banner
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Debug để kiểm tra JavaScript có hoạt động
        console.log('Banner form script loaded');
        
        // Hiển thị tên file đã chọn
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            console.log('File selected:', fileName, 'ID:', this.id); // Debug log
            
            // Preview ảnh
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if ($(this.element).attr('id') === 'image') {
                        $('#preview').attr('src', e.target.result).show();
                    }
                }.bind({element: this});
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Xử lý preview cho nhiều ảnh
        $('#images').on('change', function(e) {
            const files = this.files;
            const fileCount = files.length;
            
            // Debug chi tiết hơn
            console.log('Multi-images changed, count:', fileCount);
            console.log('Event target files:', e.target.files);
            
            // Cập nhật label hiển thị số lượng file đã chọn
            $(this).next('.custom-file-label').addClass("selected").html(fileCount + ' ảnh đã chọn');
            
            // Xóa tất cả preview cũ
            $('#multi-preview').empty();
            
            // Không chọn file nào
            if (fileCount === 0) {
                return;
            }
            
            // Tạo preview cho mỗi ảnh
            for (let i = 0; i < fileCount; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#multi-preview').append(`
                        <div class="mb-2">
                            <img src="${e.target.result}" alt="Preview ${i+1}" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    `);
                }
                reader.readAsDataURL(files[i]);
            }
        });
        
        // Cập nhật yêu cầu tiêu đề dựa trên loại banner
        function updateTitleRequirement() {
            const bannerType = $('#type').val();
            console.log('Banner type changed to:', bannerType);
            
            if (bannerType === 'main_slider') {
                $('.title-required').addClass('d-none');
                $('.title-note').removeClass('d-none');
                $('#title').removeAttr('required');
                
                // Hiển thị input nhiều ảnh và ẩn input một ảnh
                $('#multi-image-group').removeClass('d-none');
                $('#single-image-group').addClass('d-none');
                console.log('Switched to multi-image upload');
            } else {
                $('.title-required').removeClass('d-none');
                $('.title-note').addClass('d-none');
                $('#title').attr('required', 'required');
                
                // Hiển thị input một ảnh và ẩn input nhiều ảnh
                $('#multi-image-group').addClass('d-none');
                $('#single-image-group').removeClass('d-none');
                console.log('Switched to single-image upload');
            }
        }
        
        // Chạy một lần khi trang tải
        updateTitleRequirement();
        
        // Chạy khi thay đổi loại banner
        $('#type').on('change', updateTitleRequirement);
        
        // Debug cho form submit
        $('#banner-form').on('submit', function(e) {
            console.log('Form submitted');
            
            // Kiểm tra xem đã chọn file chưa
            const bannerType = $('#type').val();
            console.log('Banner type at submit:', bannerType);
            
            if (bannerType === 'main_slider') {
                const files = $('#images')[0].files;
                console.log('Main slider files:', files);
                console.log('Main slider files length:', files.length);
                
                // Chấp nhận submit form nếu đã chọn ít nhất một file
                if (files && files.length > 0) {
                    console.log('Files selected, submitting form');
                    return true;
                }
                
                // Thông báo lỗi nếu chưa chọn file
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một ảnh cho banner chính');
                return false;
            } else {
                const file = $('#image')[0].files;
                console.log('Single image file:', file);
                console.log('Single image files length:', file.length);
                
                // Chấp nhận submit form nếu đã chọn file
                if (file && file.length > 0) {
                    console.log('File selected, submitting form');
                    return true;
                }
                
                // Thông báo lỗi nếu chưa chọn file
                e.preventDefault();
                alert('Vui lòng chọn ảnh cho banner');
                return false;
            }
        });
    });
</script>
@endsection 