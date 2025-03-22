<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->book->price;
        });
        
        return view('frontend.orders.checkout', compact('cartItems', 'total'));
    }

    /**
     * Place a new order.
     */
    public function place(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,bank_transfer,momo',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();
            
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }
        
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->book->price;
        });

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->book->stock < $item->quantity) {
                return redirect()->route('checkout')->with('error', "Sách '{$item->book->title}' không đủ số lượng trong kho.");
            }
        }

        try {
            DB::beginTransaction();
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'notes' => $request->notes,
            ]);
            
            // Create order items and update book stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'quantity' => $item->quantity,
                    'price' => $item->book->price,
                ]);
                
                // Update book stock
                $book = Book::find($item->book_id);
                $book->stock -= $item->quantity;
                $book->save();
            }
            
            // Clear cart
            Cart::where('user_id', Auth::id())->delete();
            
            DB::commit();
            
            return redirect()->route('orders.success', $order->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Display order success page.
     */
    public function success($id)
    {
        $order = Order::with('orderItems.book')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('frontend.orders.success', compact('order'));
    }

    /**
     * Display user's order history.
     */
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('frontend.orders.history', compact('orders'));
    }

    /**
     * Display details of a specific order.
     */
    public function show($id)
    {
        $order = Order::with('orderItems.book')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('frontend.orders.show', compact('order'));
    }

    /**
     * Cancel an order.
     */
    public function cancel($id)
    {
        // Chỉ có thể hủy đơn hàng ở trạng thái 'pending'
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);
            
        try {
            DB::beginTransaction();
            
            // Cập nhật trạng thái đơn hàng
            $order->status = 'cancelled';
            $order->save();
            
            // Hoàn lại số lượng sách vào kho
            foreach ($order->orderItems as $item) {
                if ($item->book_id) {
                    $book = Book::find($item->book_id);
                    if ($book) {
                        $book->stock += $item->quantity;
                        $book->save();
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('orders.show', $id)->with('success', 'Đơn hàng đã được hủy thành công.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi hủy đơn hàng. Vui lòng thử lại.');
        }
    }
}
