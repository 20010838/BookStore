@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
        <div>
            <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-secondary" target="_blank">
                <i class="fas fa-file-invoice"></i> Xuất hóa đơn
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn hàng</h6>
                    <div>
                        <span class="badge 
                            @if($order->status == 'pending') bg-warning
                            @elseif($order->status == 'processing') bg-info
                            @elseif($order->status == 'shipped') bg-primary
                            @elseif($order->status == 'delivered') bg-success
                            @elseif($order->status == 'cancelled') bg-danger
                            @endif">
                            @if($order->status == 'pending') Chờ xử lý
                            @elseif($order->status == 'processing') Đang xử lý
                            @elseif($order->status == 'shipped') Đang giao
                            @elseif($order->status == 'delivered') Đã giao
                            @elseif($order->status == 'cancelled') Đã hủy
                            @endif
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Thông tin đơn hàng</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Mã đơn hàng:</strong></td>
                                    <td>{{ $order->order_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày đặt hàng:</strong></td>
                                    <td>{{ $order->created_at->format('H:i - d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phương thức thanh toán:</strong></td>
                                    <td>
                                        @if($order->payment_method == 'cod')
                                            Tiền mặt khi nhận hàng (COD)
                                        @elseif($order->payment_method == 'bank_transfer')
                                            Chuyển khoản ngân hàng
                                        @elseif($order->payment_method == 'momo')
                                            Ví MoMo
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái thanh toán:</strong></td>
                                    <td>
                                        @if($order->payment_status == 'pending')
                                            <span class="badge bg-warning">Chờ thanh toán</span>
                                        @elseif($order->payment_status == 'paid')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                        @elseif($order->payment_status == 'failed')
                                            <span class="badge bg-danger">Thất bại</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold">Thông tin khách hàng</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Tên:</strong></td>
                                    <td>{{ $order->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $order->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Điện thoại:</strong></td>
                                    <td>{{ $order->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Địa chỉ:</strong></td>
                                    <td>{{ $order->address }}, {{ $order->city }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h6 class="font-weight-bold mb-3">Sản phẩm đã đặt</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Giá (VNĐ)</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Thành tiền (VNĐ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->book && $item->book->cover_image)
                                                <img src="{{ asset('storage/' . $item->book->cover_image) }}" alt="{{ $item->book->title }}" class="img-thumbnail me-2" style="width: 50px;">
                                            @else
                                                <div class="bg-light me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 60px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                @if($item->book)
                                                    <strong>{{ $item->book->title }}</strong>
                                                    <div class="small text-muted">{{ $item->book->author ? $item->book->author->name : 'N/A' }}</div>
                                                @else
                                                    <span class="text-muted">Sản phẩm không còn tồn tại</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ number_format($item->price * $item->quantity) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Tạm tính:</th>
                                    <td class="text-center">{{ number_format($order->total_amount) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Phí vận chuyển:</th>
                                    <td class="text-center">Miễn phí</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-center">{{ number_format($order->total_amount) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->notes)
                        <div class="mt-4">
                            <h6 class="font-weight-bold mb-2">Ghi chú đơn hàng</h6>
                            <div class="p-3 bg-light rounded">
                                <p class="mb-0">{{ $order->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cập nhật trạng thái đơn hàng</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select name="status" id="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật trạng thái</button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cập nhật trạng thái thanh toán</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update_payment', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thất bại</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật thanh toán</button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Lịch sử đơn hàng</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="timeline-item-marker-indicator bg-primary"></div>
                            </div>
                            <div class="timeline-item-content">
                                <span class="fw-bold text-primary">Đơn hàng đã được tạo</span>
                                <p class="mb-0 small">{{ $order->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        <!-- Có thể thêm lịch sử đơn hàng ở đây nếu có -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1rem;
        margin: 0;
    }
    .timeline-item {
        position: relative;
        padding-left: 1.5rem;
        padding-bottom: 1.5rem;
    }
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        border-left: 1px solid #e3e6ec;
    }
    .timeline-item-marker {
        position: absolute;
        left: -1rem;
        width: 2rem;
    }
    .timeline-item-marker-text {
        width: 100%;
        text-align: center;
        font-size: 0.8rem;
        color: #a2acba;
        margin-bottom: 0.25rem;
    }
    .timeline-item-marker-indicator {
        display: block;
        width: 10px;
        height: 10px;
        border-radius: 100%;
        margin-left: 0.75rem;
    }
    .timeline-item-content {
        padding-top: 0;
    }
</style>
@endsection 