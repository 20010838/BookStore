@extends('layouts.frontend')

@section('title', 'Hồ sơ của tôi')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <img src="{{ asset('images/user-avatar.png') }}" alt="Avatar" class="rounded-circle" width="100" height="100">
                        </div>
                        <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                        <p class="text-muted small mb-0">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="list-group list-group-flush">
                        <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> Hồ sơ của tôi
                        </a>
                        <a href="{{ route('user.change_password') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-key me-2"></i> Đổi mật khẩu
                        </a>
                        <a href="{{ route('orders.history') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                        </a>
                        <a href="{{ route('user.reviews') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-star me-2"></i> Đánh giá của tôi
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-heart me-2"></i> Danh sách yêu thích
                        </a>
                        <a href="#" class="list-group-item list-group-item-action text-danger"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Hồ sơ của tôi</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('user.update_profile') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', Auth::user()->address) }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Cập nhật hồ sơ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 