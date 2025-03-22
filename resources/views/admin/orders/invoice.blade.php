<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_number }} - BookStore</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #4361ee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-details {
            text-align: right;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info-box {
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f5f5f5;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 30px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
            color: #4361ee;
            border-top: 2px solid #4361ee;
            padding-top: 12px;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
        @media print {
            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="margin: 0; color: #4361ee;">HÓA ĐƠN</h1>
                    <p style="margin: 5px 0 0 0;">#{{ $order->order_number }}</p>
                </div>
                <div class="company-details">
                    <h3 style="margin: 0;">Book Store</h3>
                    <p style="margin: 5px 0;">123 Đường Sách, Quận 1, TP. HCM</p>
                    <p style="margin: 5px 0;">Email: info@bookstore.com</p>
                    <p style="margin: 5px 0;">Điện thoại: +84 123 456 789</p>
                </div>
            </div>
        </div>

        <div class="invoice-info">
            <div class="invoice-info-box" style="width: 45%;">
                <h4 style="margin-top: 0;">Thông tin khách hàng:</h4>
                <p style="margin: 5px 0;"><strong>{{ $order->name }}</strong></p>
                <p style="margin: 5px 0;">{{ $order->address }}</p>
                <p style="margin: 5px 0;">{{ $order->city }}</p>
                <p style="margin: 5px 0;">Điện thoại: {{ $order->phone }}</p>
                <p style="margin: 5px 0;">Email: {{ $order->email }}</p>
            </div>
            <div class="invoice-info-box" style="width: 45%;">
                <h4 style="margin-top: 0;">Thông tin đơn hàng:</h4>
                <p style="margin: 5px 0;"><strong>Mã đơn hàng:</strong> #{{ $order->order_number }}</p>
                <p style="margin: 5px 0;"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p style="margin: 5px 0;"><strong>Phương thức thanh toán:</strong> 
                    @if($order->payment_method == 'cod')
                        Tiền mặt khi nhận hàng
                    @elseif($order->payment_method == 'bank_transfer')
                        Chuyển khoản ngân hàng
                    @elseif($order->payment_method == 'momo')
                        Ví MoMo
                    @endif
                </p>
                <p style="margin: 5px 0;"><strong>Trạng thái thanh toán:</strong> 
                    @if($order->payment_status == 'pending')
                        Chờ thanh toán
                    @elseif($order->payment_status == 'paid')
                        Đã thanh toán
                    @elseif($order->payment_status == 'failed')
                        Thất bại
                    @endif
                </p>
            </div>
        </div>

        <h3>Chi tiết đơn hàng</h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="45%">Sản phẩm</th>
                    <th width="15%" class="text-center">Đơn giá</th>
                    <th width="15%" class="text-center">Số lượng</th>
                    <th width="20%" class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($item->book)
                            {{ $item->book->title }}
                            <div style="font-size: 12px; color: #777;">{{ $item->book->author ? $item->book->author->name : '' }}</div>
                        @else
                            Sản phẩm không còn tồn tại
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->price) }} VNĐ</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price * $item->quantity) }} VNĐ</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section" style="width: 350px; margin-left: auto;">
            <div class="total-row">
                <div>Tạm tính:</div>
                <div>{{ number_format($order->total_amount) }} VNĐ</div>
            </div>
            <div class="total-row">
                <div>Phí vận chuyển:</div>
                <div>Miễn phí</div>
            </div>
            <div class="total-row">
                <div>Tổng cộng:</div>
                <div>{{ number_format($order->total_amount) }} VNĐ</div>
            </div>
        </div>

        @if($order->notes)
        <div class="notes">
            <h4 style="margin-top: 0;">Ghi chú:</h4>
            <p style="margin: 5px 0;">{{ $order->notes }}</p>
        </div>
        @endif

        <div class="footer">
            <p>Cảm ơn quý khách đã mua hàng tại Book Store!</p>
            <p>Mọi thắc mắc về đơn hàng, vui lòng liên hệ với chúng tôi qua email: support@bookstore.com hoặc số điện thoại: +84 123 456 789</p>
        </div>

        <!-- Print Button - hidden when printing -->
        <div class="no-print" style="margin-top: 30px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; background-color: #4361ee; color: white; border: none; border-radius: 5px; cursor: pointer;">
                In hóa đơn
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background-color: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Đóng
            </button>
        </div>
    </div>
</body>
</html> 