@extends('layouts.admin')

@section('title', 'Báo cáo doanh thu')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Báo cáo doanh thu</h1>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> In báo cáo
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lọc theo thời gian</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.sales') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng doanh thu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalSales, 0, ',', '.') }} VNĐ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tổng đơn hàng</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Giá trị trung bình</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 0, ',', '.') : 0 }} VNĐ
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đơn hàng đã giao</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $paymentMethodStats['delivered']['count'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo ngày</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Phương thức thanh toán</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($paymentMethodStats as $method => $stats)
                            @if($method != 'delivered')
                                <span class="mr-2">
                                    <i class="fas fa-circle 
                                        @if($method == 'cod') text-primary 
                                        @elseif($method == 'bank_transfer') text-success 
                                        @elseif($method == 'momo') text-danger 
                                        @elseif($method == 'vnpay') text-info 
                                        @else text-secondary 
                                        @endif"></i> 
                                    {{ $method == 'cod' ? 'Tiền mặt' : ($method == 'bank_transfer' ? 'Chuyển khoản' : ($method == 'momo' ? 'MoMo' : ($method == 'vnpay' ? 'VNPay' : $method))) }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn hàng</h6>
        </div>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders ?? [] as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->id }}</a></td>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach($dailySales as $date => $data)
                    '{{ \Carbon\Carbon::parse($date)->format('d/m') }}',
                @endforeach
            ],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: [
                    @foreach($dailySales as $date => $data)
                        {{ $data['total'] }},
                    @endforeach
                ],
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' VNĐ';
                        }
                    }
                }
            }
        }
    });

    // Payment Method Chart
    var paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
    var paymentMethodChart = new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($paymentMethodStats as $method => $stats)
                    @if($method != 'delivered')
                        '{{ $method == 'cod' ? 'Tiền mặt' : ($method == 'bank_transfer' ? 'Chuyển khoản' : ($method == 'momo' ? 'MoMo' : ($method == 'vnpay' ? 'VNPay' : $method))) }}',
                    @endif
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($paymentMethodStats as $method => $stats)
                        @if($method != 'delivered')
                            {{ $stats['count'] }},
                        @endif
                    @endforeach
                ],
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#e74a3b',
                    '#36b9cc',
                    '#f6c23e'
                ],
                hoverBackgroundColor: [
                    '#2e59d9',
                    '#17a673',
                    '#c23321',
                    '#2c9faf',
                    '#dda20a'
                ],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            cutout: '70%',
        }
    });
</script>
@endpush 