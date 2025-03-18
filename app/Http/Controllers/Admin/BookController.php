<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\BookImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index()
    {
        $books = Book::with(['author', 'category'])->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $categories = Category::all();
        $authors = Author::all();
        return view('admin.books.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'isbn' => 'required|string|max:13|unique:books',
            'pages' => 'nullable|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'language' => 'nullable|string|max:50',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $filename = time() . '.' . $coverImage->getClientOriginalExtension();
            $path = $coverImage->storeAs('books', $filename, 'public');
            $data['cover_image'] = $path;
        }

        $book = Book::create($data);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $this->uploadBookImages($request->file('images'), $book);
        }

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được tạo thành công.');
    }

    /**
     * Display the specified book.
     */
    public function show($id)
    {
        $book = Book::with(['author', 'category', 'reviews.user'])->findOrFail($id);
        return view('admin.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        $authors = Author::all();
        return view('admin.books.edit', compact('book', 'categories', 'authors'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $id,
            'pages' => 'nullable|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'language' => 'nullable|string|max:50',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $coverImage = $request->file('cover_image');
            $filename = time() . '.' . $coverImage->getClientOriginalExtension();
            $path = $coverImage->storeAs('books', $filename, 'public');
            $data['cover_image'] = $path;
        }

        // Handle primary image selection
        if ($request->has('primary_image')) {
            $primaryImageId = $request->input('primary_image');
            $book->images()->update(['is_primary' => false]);
            $book->images()->where('id', $primaryImageId)->update(['is_primary' => true]);
        }

        // Handle image captions
        if ($request->has('image_captions')) {
            foreach ($request->input('image_captions') as $imageId => $caption) {
                $book->images()->where('id', $imageId)->update(['caption' => $caption]);
            }
        }

        $book->update($data);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $this->uploadBookImages($request->file('images'), $book);
        }

        // Handle deleted images
        if ($request->has('delete_images')) {
            $this->deleteBookImages($request->input('delete_images'));
        }

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được cập nhật thành công.');
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Delete cover image if exists
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        // Delete all book images
        foreach ($book->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Sách đã được xóa thành công.');
    }

    /**
     * Upload multiple images for a book.
     */
    private function uploadBookImages($images, $book)
    {
        $sortOrder = $book->images()->max('sort_order') ?? 0;
        
        foreach ($images as $index => $image) {
            $filename = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('books/gallery', $filename, 'public');
            
            // Set the first image as primary if no primary image exists
            $isPrimary = ($index === 0 && !$book->images()->where('is_primary', true)->exists());
            
            $book->images()->create([
                'image_path' => $path,
                'is_primary' => $isPrimary,
                'sort_order' => $sortOrder + $index + 1,
                'caption' => $book->title . ' - Ảnh ' . ($index + 1),
            ]);
        }
    }

    /**
     * Delete book images by IDs.
     */
    private function deleteBookImages($imageIds)
    {
        $images = BookImage::whereIn('id', $imageIds)->get();
        
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }
}
