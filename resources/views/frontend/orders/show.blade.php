@extends('layouts.frontend')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Chi tiết đơn hàng</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.history') }}">Lịch sử đơn hàng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #{{ $order->order_number }}</li>
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
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn hàng #{{ $order->order_number }}</h5>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning
                        @elseif($order->status == 'processing') bg-info
                        @elseif($order->status == 'shipped') bg-primary
                        @elseif($order->status == 'delivered') bg-success
                        @elseif($order->status == 'cancelled') bg-danger
                        @endif">
                        @if($order->status == 'pending') Đang xử lý
                        @elseif($order->status == 'processing') Đang chuẩn bị
                        @elseif($order->status == 'shipped') Đang giao hàng
                        @elseif($order->status == 'delivered') Đã giao hàng
                        @elseif($order->status == 'cancelled') Đã hủy
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông tin đơn hàng</h6>
                            <p class="mb-1"><strong>Đơn hàng:</strong> #{{ $order->order_number }}</p>
                            <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-1">
                                <strong>Thanh toán:</strong> 
                                @if($order->payment_method == 'cod')
                                    Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method == 'bank_transfer')
                                    Chuyển khoản ngân hàng
                                @elseif($order->payment_method == 'momo')
                                    Ví MoMo
                                @endif
                            </p>
                            <p class="mb-0">
                                <strong>Trạng thái thanh toán:</strong> 
                                @if($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                @elseif($order->payment_status == 'completed')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="badge bg-danger">Thất bại</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông tin giao hàng</h6>
                            <p class="mb-1"><strong>{{ $order->name }}</strong></p>
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->city }}</p>
                            <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->phone }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $order->email }}</p>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3">Sản phẩm đã đặt</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->book->cover_image)
                                                <img src="{{ asset('storage/' . $item->book->cover_image) }}" alt="{{ $item->book->title }}" class="img-thumbnail me-3" style="width: 60px;">
                                            @else
                                                <div class="bg-light me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 80px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->book->title }}</h6>
                                                <small class="text-muted">{{ $item->book->author }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">{{ number_format($item->price) }} VNĐ</td>
                                    <td class="text-center align-middle">{{ $item->quantity }}</td>
                                    <td class="text-end align-middle">{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Tạm tính:</th>
                                    <td class="text-end">{{ number_format($order->total_amount) }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Phí vận chuyển:</th>
                                    <td class="text-end">Miễn phí</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-end">{{ number_format($order->total_amount) }} VNĐ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="mt-4">
                            <h6 class="text-muted mb-3">Ghi chú đơn hàng</h6>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex mt-4">
                        <a href="{{ route('orders.history') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-arrow-left me-2"></i> Quay lại đơn hàng
                        </a>
                        @if($order->status == 'pending')
                            <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                <i class="fas fa-times me-2"></i> Hủy đơn hàng
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Xác nhận hủy đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy đơn hàng #{{ $order->order_number }} không?</p>
                <p class="text-danger"><small>Lưu ý: Thao tác này không thể hoàn tác.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 