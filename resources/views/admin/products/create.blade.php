@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $pageTitle ?? 'Thêm sản phẩm mới' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Chọn danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', request('category_id')) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Giá <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" step="1000" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stock">Tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">Ảnh chính</label>
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="image" name="image">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Chọn ảnh chính cho sản phẩm (tối đa 10MB)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="images">Ảnh khác (có thể chọn nhiều)</label>
                                    <input type="file" class="form-control-file @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple>
                                    @error('images.*')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Chọn nhiều ảnh cho sản phẩm (tối đa 10MB mỗi ảnh)</small>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h4>Thông tin chung</h4>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="supplier">Nhà cung cấp</label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand">Thương hiệu</label>
                                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="brand_origin">Xuất xứ thương hiệu</label>
                                    <input type="text" class="form-control" id="brand_origin" name="brand_origin" value="{{ old('brand_origin') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="manufacturing_place">Nơi sản xuất</label>
                                    <input type="text" class="form-control" id="manufacturing_place" name="manufacturing_place" value="{{ old('manufacturing_place') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="color">Màu sắc</label>
                                    <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="material">Chất liệu</label>
                                    <input type="text" class="form-control" id="material" name="material" value="{{ old('material') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="weight">Trọng lượng (gr)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dimensions">Kích thước bao bì</label>
                                    <input type="text" class="form-control" id="dimensions" name="dimensions" value="{{ old('dimensions') }}">
                                </div>
                            </div>
                        </div>

                        <div id="additional-fields">
                            <hr>
                            <h4>Thông tin bổ sung</h4>

                            <!-- Thông tin dụng cụ học tập -->
                            <div class="form-group">
                                <label for="ink_color">Màu mực</label>
                                <input type="text" class="form-control" id="ink_color" name="ink_color" value="{{ old('ink_color') }}">
                            </div>

                            <!-- Thông tin đồ chơi -->
                            <div class="form-group">
                                <label for="age_recommendation">Độ tuổi</label>
                                <input type="text" class="form-control" id="age_recommendation" name="age_recommendation" value="{{ old('age_recommendation') }}">
                            </div>

                            <div class="form-group">
                                <label for="publish_year">Năm xuất bản</label>
                                <input type="number" class="form-control" id="publish_year" name="publish_year" value="{{ old('publish_year') }}">
                            </div>

                            <div class="form-group">
                                <label for="technical_specs">Thông số kỹ thuật</label>
                                <textarea class="form-control" id="technical_specs" name="technical_specs" rows="2">{{ old('technical_specs') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="warnings">Thông tin cảnh báo</label>
                                <textarea class="form-control" id="warnings" name="warnings" rows="2">{{ old('warnings') }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="usage_instructions">Hướng dẫn sử dụng</label>
                                <textarea class="form-control" id="usage_instructions" name="usage_instructions" rows="2">{{ old('usage_instructions') }}</textarea>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status">Đang bán</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Thêm các xử lý JavaScript nếu cần
});
</script>
@endpush
@endsection 