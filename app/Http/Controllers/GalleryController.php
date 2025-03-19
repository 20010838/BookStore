<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookImage;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    /**
     * Hiển thị danh sách sách có gallery ảnh
     */
    public function index()
    {
        // Tìm các sách có ảnh bằng cách join
        $bookIds = DB::table('book_images')
            ->select('book_id')
            ->distinct()
            ->pluck('book_id')
            ->toArray();
        
        $booksWithGallery = Book::whereIn('id', $bookIds)
            ->with('author', 'category')
            ->paginate(10);
        
        // Tải thêm images cho từng sách
        foreach ($booksWithGallery as $book) {
            $book->loadMissing('images');
        }
        
        return view('gallery.index', compact('booksWithGallery'));
    }
    
    /**
     * Hiển thị gallery ảnh của một cuốn sách
     */
    public function show($id)
    {
        $book = Book::findOrFail($id);
        $book->load('images', 'author', 'category');
        
        return view('gallery.show', compact('book'));
    }
}
