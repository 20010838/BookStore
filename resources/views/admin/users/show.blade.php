@extends('layouts.admin')

@section('title', 'Chi tiết người dùng: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Chi tiết người dùng</h6>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Thông tin cá nhân</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">ID</th>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <th>Tên</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Vai trò</th>
                                    <td>
                                        <span class="badge badge-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Hoạt động của người dùng</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center mb-4">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->orders->count() }}</div>
                                        <div class="text-muted">Đơn hàng</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center mb-4">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->reviews->count() }}</div>
                                        <div class="text-muted">Đánh giá</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng gần đây -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đơn hàng gần đây</h6>
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->orders->take(5) as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($order->total_amount) }} VND</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center">Người dùng chưa có đơn hàng nào.</p>
                    @endif
                </div>
            </div>

            <!-- Đánh giá gần đây -->
            <div class="card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Đánh giá gần đây</h6>
                </div>
                <div class="card-body">
                    @if($user->reviews->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sách</th>
                                    <th>Đánh giá</th>
                                    <th>Nội dung</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->reviews->take(5) as $review)
                                <tr>
                                    <td>{{ $review->book->title }}</td>
                                    <td>{{ $review->rating }}/5</td>
                                    <td>{{ Str::limit($review->comment, 50) }}</td>
                                    <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $review->is_approved ? 'success' : 'secondary' }}">
                                            {{ $review->is_approved ? 'Đã duyệt' : 'Chờ duyệt' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-center">Người dùng chưa có đánh giá nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 