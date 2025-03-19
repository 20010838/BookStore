<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Book;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng của bạn.');
        }
        
        $cartItems = Cart::where('user_id', Auth::id())->with('book.author', 'product')->get();
        $total = 0;
        
        foreach ($cartItems as $item) {
            if ($item->book_id) {
                $total += $item->book->price * $item->quantity;
            } elseif ($item->product_id) {
                $total += $item->product->price * $item->quantity;
            }
        }
        
        return view('frontend.cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Add a book or product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'product_id' => 'nullable|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.');
        }
        
        // Check if we're adding a book or a product
        if ($request->has('book_id')) {
            $book = Book::findOrFail($request->book_id);
            
            // Check if book is in stock
            if ($book->stock < $request->quantity) {
                return redirect()->back()->with('error', 'Số lượng sách trong kho không đủ.');
            }
            
            // Check if book is already in cart
            $existingItem = Cart::where('user_id', Auth::id())
                ->where('book_id', $request->book_id)
                ->first();
            
            if ($existingItem) {
                // Update quantity if book already in cart
                $newQuantity = $existingItem->quantity + $request->quantity;
                
                // Check if new quantity exceeds stock
                if ($newQuantity > $book->stock) {
                    return redirect()->back()->with('error', 'Số lượng sách trong kho không đủ.');
                }
                
                $existingItem->quantity = $newQuantity;
                $existingItem->save();
            } else {
                // Add new item to cart
                Cart::create([
                    'user_id' => Auth::id(),
                    'book_id' => $request->book_id,
                    'quantity' => $request->quantity,
                ]);
            }
        } elseif ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            
            // Check if product is in stock
            if ($product->stock < $request->quantity) {
                return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ.');
            }
            
            // Check if product is already in cart
            $existingItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();
            
            if ($existingItem) {
                // Update quantity if product already in cart
                $newQuantity = $existingItem->quantity + $request->quantity;
                
                // Check if new quantity exceeds stock
                if ($newQuantity > $product->stock) {
                    return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ.');
                }
                
                $existingItem->quantity = $newQuantity;
                $existingItem->save();
            } else {
                // Add new item to cart
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);
            }
        } else {
            return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm để thêm vào giỏ hàng.');
        }
        
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }
    
    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($cartItem->book_id) {
            $book = Book::findOrFail($cartItem->book_id);
            
            // Check if requested quantity exceeds stock
            if ($request->quantity > $book->stock) {
                return redirect()->back()->with('error', 'Số lượng sách trong kho không đủ.');
            }
        } elseif ($cartItem->product_id) {
            $product = Product::findOrFail($cartItem->product_id);
            
            // Check if requested quantity exceeds stock
            if ($request->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Số lượng sản phẩm trong kho không đủ.');
            }
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được cập nhật.');
    }
    
    /**
     * Remove a cart item.
     */
    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
    
    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        
        return redirect()->route('cart.index')->with('success', 'Giỏ hàng đã được xóa.');
    }
}
