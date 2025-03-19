@extends('layouts.frontend')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h1 class="mb-4">Đặt hàng thành công!</h1>
                    <p class="lead mb-4">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xác nhận.</p>
                    
                    <div class="alert alert-info mb-4">
                        <p class="mb-0">Mã đơn hàng: <strong>{{ $order->order_number }}</strong></p>
                    </div>
                    
                    <p>Chúng tôi đã gửi email xác nhận đơn hàng đến <strong>{{ $order->email }}</strong>. Vui lòng kiểm tra hộp thư của bạn.</p>
                    
                    <div class="d-flex justify-content-center mt-4">
                        <a href="{{ route('orders.history') }}" class="btn btn-primary me-3">
                            <i class="fas fa-list-ul me-2"></i> Xem đơn hàng của tôi
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            <i class="fas fa-home me-2"></i> Quay lại trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Chi tiết đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông tin người nhận:</h6>
                            <p class="mb-1"><strong>{{ $order->name }}</strong></p>
                            <p class="mb-1">{{ $order->address }}</p>
                            <p class="mb-1">{{ $order->city }}</p>
                            <p class="mb-1">Điện thoại: {{ $order->phone }}</p>
                            <p class="mb-0">Email: {{ $order->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Thông tin đơn hàng:</h6>
                            <p class="mb-1">Mã đơn hàng: <strong>{{ $order->order_number }}</strong></p>
                            <p class="mb-1">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-1">Phương thức thanh toán: 
                                @if($order->payment_method == 'cod')
                                    Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method == 'bank_transfer')
                                    Chuyển khoản ngân hàng
                                @elseif($order->payment_method == 'momo')
                                    Ví MoMo
                                @endif
                            </p>
                            <p class="mb-0">Trạng thái thanh toán: 
                                @if($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Chờ thanh toán</span>
                                @elseif($order->payment_status == 'completed')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($order->payment_status == 'failed')
                                    <span class="badge bg-danger">Thất bại</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <h6 class="text-muted mb-3">Sản phẩm đã đặt:</h6>
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
                                    <td>{{ $item->book->title }}</td>
                                    <td class="text-center">{{ number_format($item->price) }} VNĐ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-end">{{ number_format($order->total_amount) }} VNĐ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 