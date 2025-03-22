<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Search by order number or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.book'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.orders.show', $id)->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }

    /**
     * Update the payment status.
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('admin.orders.show', $id)->with('success', 'Trạng thái thanh toán đã được cập nhật thành công.');
    }

    /**
     * Generate invoice for the order.
     */
    public function invoice($id)
    {
        $order = Order::with(['user', 'orderItems.book.author'])->findOrFail($id);
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'orderItems.book']);
        
        // Apply filters
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status != 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        // Generate CSV file
        $filename = 'orders-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Order Number', 'Customer', 'Email', 'Phone', 
                'Total Amount', 'Status', 'Payment Method', 'Payment Status', 
                'Date'
            ]);
            
            // Add order data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->order_number,
                    $order->name,
                    $order->email,
                    $order->phone,
                    number_format($order->total_amount),
                    $order->status,
                    $order->payment_method,
                    $order->payment_status,
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $users = User::all();
        $books = Book::where('stock', '>', 0)->get();
        return view('admin.orders.create', compact('users', 'books'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'payment_status' => 'required|in:pending,paid,failed',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1',
        ]);

        // Tạo mã đơn hàng
        $orderNumber = 'ORD-' . strtoupper(Str::random(10));
        
        // Tạo đơn hàng mới
        $order = Order::create([
            'user_id' => $request->user_id,
            'order_number' => $orderNumber,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'notes' => $request->notes,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'total_amount' => 0, // Sẽ cập nhật sau
        ]);
        
        $totalAmount = 0;
        
        // Thêm các sản phẩm vào đơn hàng
        for ($i = 0; $i < count($request->book_ids); $i++) {
            $bookId = $request->book_ids[$i];
            $quantity = $request->quantities[$i];
            
            $book = Book::find($bookId);
            if ($book && $quantity > 0) {
                // Thêm chi tiết đơn hàng
                $order->orderItems()->create([
                    'book_id' => $bookId,
                    'quantity' => $quantity,
                    'price' => $book->price,
                ]);
                
                // Cập nhật tổng tiền
                $totalAmount += $book->price * $quantity;
                
                // Cập nhật số lượng sách
                $book->decrement('stock', $quantity);
            }
        }
        
        // Cập nhật tổng tiền cho đơn hàng
        $order->update(['total_amount' => $totalAmount]);
        
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được tạo thành công.');
    }
} 