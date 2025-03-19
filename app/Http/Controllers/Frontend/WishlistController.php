<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistController extends Controller
{
    /**
     * Display the wishlist page
     */
    public function index()
    {
        $wishlist = session('wishlist', []);
        $books = [];
        
        if (!empty($wishlist)) {
            $bookIds = array_keys($wishlist);
            $books = Book::whereIn('id', $bookIds)->get();
        }
        
        return view('frontend.wishlist.index', compact('books', 'wishlist'));
    }
    
    /**
     * Add a book to wishlist
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);
        
        $bookId = $request->book_id;
        $book = Book::findOrFail($bookId);
        
        $wishlist = session()->get('wishlist', []);
        
        // Add to wishlist if not already in the list
        if (!isset($wishlist[$bookId])) {
            $wishlist[$bookId] = [
                'title' => $book->title,
                'price' => $book->price,
                'added_at' => now()->toDateTimeString(),
            ];
            
            session()->put('wishlist', $wishlist);
            return redirect()->back()->with('success', 'Sách đã được thêm vào danh sách yêu thích.');
        }
        
        return redirect()->back()->with('info', 'Sách đã có trong danh sách yêu thích.');
    }
    
    /**
     * Remove a book from wishlist
     */
    public function remove($id)
    {
        $wishlist = session()->get('wishlist', []);
        
        if (isset($wishlist[$id])) {
            unset($wishlist[$id]);
            session()->put('wishlist', $wishlist);
            return redirect()->back()->with('success', 'Sách đã được xóa khỏi danh sách yêu thích.');
        }
        
        return redirect()->back()->with('error', 'Sách không tồn tại trong danh sách yêu thích.');
    }
    
    /**
     * Clear the entire wishlist
     */
    public function clear()
    {
        session()->forget('wishlist');
        return redirect()->back()->with('success', 'Danh sách yêu thích đã được xóa.');
    }
} 