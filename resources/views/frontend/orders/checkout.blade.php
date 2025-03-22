@extends('layouts.frontend')

@section('title', 'Thanh toán')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Thanh toán</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông tin đặt hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.place') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="province" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select @error('province') is-invalid @enderror" id="province" name="province" required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select @error('district') is-invalid @enderror" id="district" name="district" required disabled>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" id="ward" name="ward" required disabled>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="city" id="city_input" value="{{ old('city', Auth::user()->city ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Số nhà, tên đường <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', Auth::user()->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn.">{{ old('notes') }}</textarea>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Phương thức thanh toán</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                    <label class="form-check-label" for="cod">
                                        <i class="fas fa-money-bill me-2"></i> Thanh toán khi nhận hàng (COD)
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university me-2"></i> Chuyển khoản ngân hàng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo">
                                    <label class="form-check-label" for="momo">
                                        <i class="fas fa-wallet me-2"></i> Ví MoMo
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">Đặt hàng</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Đơn hàng của bạn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($cartItems as $item)
                            <li class="list-group-item d-flex justify-content-between lh-sm py-3">
                                <div>
                                    <h6 class="my-0">{{ $item->book->title }}</h6>
                                    <small class="text-muted">Số lượng: {{ $item->quantity }}</small>
                                </div>
                                <span class="text-muted">{{ number_format($item->book->price * $item->quantity) }} VNĐ</span>
                            </li>
                        @endforeach
                    </ul>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-3">
                        <span>Tạm tính</span>
                        <strong>{{ number_format($total) }} VNĐ</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Phí vận chuyển</span>
                        <strong>Miễn phí</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Tổng cộng</span>
                        <strong class="text-primary">{{ number_format($total) }} VNĐ</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load provinces
    $.ajax({
        url: 'https://provinces.open-api.vn/api/p/',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let options = '<option value="">Chọn Tỉnh/Thành phố</option>';
            data.forEach(function(province) {
                options += `<option value="${province.code}">${province.name}</option>`;
            });
            $('#province').html(options);
        },
        error: function(error) {
            console.log('Lỗi khi lấy dữ liệu tỉnh/thành phố:', error);
        }
    });

    // Event handler for province select
    $('#province').on('change', function() {
        const provinceCode = $(this).val();
        const provinceName = $(this).find('option:selected').text();
        
        $('#city_input').val(provinceName);
        
        if (provinceCode) {
            $('#district').prop('disabled', false);
            
            // Load districts
            $.ajax({
                url: `https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let options = '<option value="">Chọn Quận/Huyện</option>';
                    data.districts.forEach(function(district) {
                        options += `<option value="${district.code}">${district.name}</option>`;
                    });
                    $('#district').html(options);
                },
                error: function(error) {
                    console.log('Lỗi khi lấy dữ liệu quận/huyện:', error);
                }
            });
        } else {
            $('#district').prop('disabled', true).html('<option value="">Chọn Quận/Huyện</option>');
            $('#ward').prop('disabled', true).html('<option value="">Chọn Phường/Xã</option>');
        }
    });

    // Event handler for district select
    $('#district').on('change', function() {
        const districtCode = $(this).val();
        
        if (districtCode) {
            $('#ward').prop('disabled', false);
            
            // Load wards
            $.ajax({
                url: `https://provinces.open-api.vn/api/d/${districtCode}?depth=2`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let options = '<option value="">Chọn Phường/Xã</option>';
                    data.wards.forEach(function(ward) {
                        options += `<option value="${ward.code}">${ward.name}</option>`;
                    });
                    $('#ward').html(options);
                },
                error: function(error) {
                    console.log('Lỗi khi lấy dữ liệu phường/xã:', error);
                }
            });
        } else {
            $('#ward').prop('disabled', true).html('<option value="">Chọn Phường/Xã</option>');
        }
    });

    // Form submission handler
    $('form').on('submit', function(e) {
        const fullAddress = [];
        
        // Add street address
        if ($('#address').val().trim()) {
            fullAddress.push($('#address').val().trim());
        }
        
        // Add ward
        if ($('#ward option:selected').text() !== 'Chọn Phường/Xã') {
            fullAddress.push($('#ward option:selected').text());
        }
        
        // Add district
        if ($('#district option:selected').text() !== 'Chọn Quận/Huyện') {
            fullAddress.push($('#district option:selected').text());
        }
        
        // Update the address field
        if (fullAddress.length > 0) {
            $('#address').val(fullAddress.join(', '));
        }
    });
});
</script>
@endsection 