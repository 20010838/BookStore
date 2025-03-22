@extends('layouts.admin')

@section('title', 'Tạo đơn hàng mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Tạo đơn hàng mới</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                @csrf
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="border-bottom pb-2">Thông tin khách hàng</h5>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Khách hàng</label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">-- Chọn khách hàng --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên người nhận</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="city" class="form-label">Thành phố</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="border-bottom pb-2">Thông tin đơn hàng</h5>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái đơn hàng</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Chờ xử lý</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="shipped">Đang giao</option>
                                <option value="delivered">Đã giao</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="cod">Tiền mặt khi nhận hàng</option>
                                <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                <option value="momo">Ví MoMo</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Trạng thái thanh toán</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending">Chờ thanh toán</option>
                                <option value="paid">Đã thanh toán</option>
                                <option value="failed">Thất bại</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="border-bottom pb-2">Sản phẩm</h5>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <button type="button" class="btn btn-secondary" id="addProduct">
                            <i class="fas fa-plus"></i> Thêm sản phẩm
                        </button>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="productsTable">
                                <thead>
                                    <tr>
                                        <th width="40%">Sách</th>
                                        <th width="15%">Giá</th>
                                        <th width="15%">Số lượng</th>
                                        <th width="20%">Thành tiền</th>
                                        <th width="10%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="productRows">
                                    <!-- Product rows will be added here -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                        <td id="totalAmount" class="text-end">0 VNĐ</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Tạo đơn hàng
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Xử lý khi chọn khách hàng
        $('#user_id').change(function() {
            let userId = $(this).val();
            if (userId) {
                $.ajax({
                    url: '/admin/users/' + userId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#name').val(data.name);
                        $('#email').val(data.email);
                        $('#phone').val(data.phone || '');
                        $('#address').val(data.address || '');
                        $('#city').val(data.city || '');
                    },
                    error: function() {
                        alert('Không thể lấy thông tin khách hàng');
                    }
                });
            }
        });
        
        // Khi click nút thêm sản phẩm
        $('#addProduct').click(function() {
            addProductRow();
        });
        
        // Thêm dòng sản phẩm đầu tiên
        addProductRow();
        
        // Xóa sản phẩm
        $(document).on('click', '.removeProduct', function() {
            if ($('#productRows tr').length > 1) {
                $(this).closest('tr').remove();
                updateTotal();
            } else {
                alert('Đơn hàng phải có ít nhất một sản phẩm');
            }
        });
        
        // Cập nhật thành tiền khi thay đổi số lượng
        $(document).on('change', '.book-select, .quantity-input', function() {
            let row = $(this).closest('tr');
            updateRowAmount(row);
            updateTotal();
        });
        
        // Hàm thêm dòng sản phẩm
        function addProductRow() {
            let index = $('#productRows tr').length;
            let html = `
                <tr>
                    <td>
                        <select class="form-select book-select" name="book_ids[]" required>
                            <option value="">-- Chọn sách --</option>
                            @foreach($books as $book)
                            <option value="{{ $book->id }}" data-price="{{ $book->price }}">
                                {{ $book->title }} - {{ number_format($book->price) }} VNĐ (SL: {{ $book->stock }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="price-display text-end">0 VNĐ</td>
                    <td>
                        <input type="number" class="form-control quantity-input" name="quantities[]" value="1" min="1" required>
                    </td>
                    <td class="amount-display text-end">0 VNĐ</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger removeProduct">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#productRows').append(html);
        }
        
        // Cập nhật thành tiền cho một dòng
        function updateRowAmount(row) {
            let select = row.find('.book-select');
            let quantity = parseInt(row.find('.quantity-input').val()) || 0;
            let price = 0;
            
            if (select.val()) {
                price = parseFloat(select.find('option:selected').data('price')) || 0;
                row.find('.price-display').text(formatCurrency(price) + ' VNĐ');
            }
            
            let amount = price * quantity;
            row.find('.amount-display').text(formatCurrency(amount) + ' VNĐ');
            return amount;
        }
        
        // Cập nhật tổng cộng
        function updateTotal() {
            let total = 0;
            $('#productRows tr').each(function() {
                total += updateRowAmount($(this));
            });
            $('#totalAmount').text(formatCurrency(total) + ' VNĐ');
        }
        
        // Định dạng tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount);
        }
    });
</script>
@endsection 