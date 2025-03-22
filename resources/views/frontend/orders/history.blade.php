@extends('layouts.frontend')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Lịch sử đơn hàng</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Lịch sử đơn hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tài khoản của tôi</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Thông tin tài khoản
                    </a>
                    <a href="{{ route('orders.history') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag me-2"></i> Đơn hàng của tôi
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-heart me-2"></i> Danh sách yêu thích
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Đơn hàng của tôi</h5>
                </div>
                <div class="card-body">
                    @if($orders->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag text-muted mb-4" style="font-size: 4rem;"></i>
                            <h4>Bạn chưa có đơn hàng nào</h4>
                            <p class="text-muted">Hãy khám phá các sản phẩm của chúng tôi và đặt hàng.</p>
                            <a href="{{ route('books.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-book me-2"></i> Khám phá sách ngay
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('H:i - d/m/Y') }}</td>
                                        <td>{{ number_format($order->total_amount) }} VNĐ</td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="badge bg-warning">Đang xử lý</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info">Đang chuẩn bị</span>
                                            @elseif($order->status == 'shipped')
                                                <span class="badge bg-primary">Đang giao hàng</span>
                                            @elseif($order->status == 'delivered')
                                                <span class="badge bg-success">Đã giao hàng</span>
                                            @elseif($order->status == 'cancelled')
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 