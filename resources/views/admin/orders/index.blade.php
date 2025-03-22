@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý đơn hàng</h1>
        <a href="{{ route('admin.orders.create') }}" class="d-none d-sm-inline-block btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tạo đơn hàng mới
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.orders.export') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lọc đơn hàng</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                    <select name="payment_status" id="payment_status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="from_date" class="form-label">Từ ngày</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="to_date" class="form-label">Đến ngày</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái thanh toán</th>
                            <th>Trạng thái đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @if($order->payment_method == 'cod')
                                    <span class="badge bg-secondary">Tiền mặt khi nhận hàng</span>
                                @elseif($order->payment_method == 'bank_transfer')
                                    <span class="badge bg-info">Chuyển khoản</span>
                                @elseif($order->payment_method == 'momo')
                                    <span class="badge bg-danger">Ví MoMo</span>
                                @elseif($order->payment_method == 'vnpay')
                                    <span class="badge bg-primary">VNPay</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->payment_method }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->payment_status == 'pending')
                                    <span class="badge bg-warning">Chưa thanh toán</span>
                                @elseif($order->payment_status == 'paid')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($order->payment_status == 'refunded')
                                    <span class="badge bg-danger">Đã hoàn tiền</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Chờ xử lý</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Đang xử lý</span>
                                @elseif($order->status == 'shipped')
                                    <span class="badge bg-primary">Đang giao</span>
                                @elseif($order->status == 'delivered')
                                    <span class="badge bg-success">Đã giao</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Đã hủy</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-sm btn-secondary" target="_blank">
                                        <i class="fas fa-file-invoice"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($orders) && $orders->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 