<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;
use App\Models\Review;

class BookController extends Controller
{
    /**
     * Display a listing of the books.
     */
    public function index(Request $request)
    {
        $query = Book::query()->where('status', true);
        
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }
        
        // Apply category filter
        if ($request->has('category') && !empty($request->category)) {
            $categoryId = $request->category;
            $category = Category::find($categoryId);
            
            if ($category) {
                // If it's a parent category, include all its descendants
                if ($category->level < 3) {
                    $descendantIds = $this->getCategoryDescendantIds($category);
                    $query->whereIn('category_id', array_merge([$categoryId], $descendantIds));
                } else {
                    // If it's a leaf category, just filter by it
                    $query->where('category_id', $categoryId);
                }
            }
        }
        
        // Apply author filter
        if ($request->has('author') && !empty($request->author)) {
            $query->whereHas('author', function($q) use ($request) {
                $q->where('id', $request->author);
            });
        }
        
        // Apply price range filter
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'title_asc':
                    $query->orderBy('title', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $books = $query->paginate(12);
        
        // Get categories with hierarchy, exclude "Đồ chơi" and "Dụng cụ học tập"
        $rootCategories = Category::where('level', 1)
            ->whereNotIn('name', ['Đồ chơi', 'Dụng cụ học tập'])
            ->with(['children' => function($query) {
                $query->with('children');
            }])
            ->get();
            
        $authors = Author::all();
        
        return view('frontend.books.index_new', compact('books', 'rootCategories', 'authors'));
    }
    
    /**
     * Display the specified book.
     */
    public function show($slug)
    {
        $book = Book::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        
        // Get related books (same category)
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('status', true)
            ->take(4)
            ->get();
        
        // Get book reviews
        $reviews = Review::where('book_id', $book->id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Tải thêm images và gán cho gallery
        $book->load('images');
        $book->gallery = $book->images;
        
        return view('frontend.books.show', compact('book', 'relatedBooks', 'reviews'));
    }
    
    /**
     * Display books by category.
     */
    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Get all books in this category and its descendants
        $categoryIds = [$category->id];
        
        // If it's a parent category, include all its descendants
        if ($category->level < 3) {
            $descendantIds = $this->getCategoryDescendantIds($category);
            $categoryIds = array_merge($categoryIds, $descendantIds);
        }
        
        $books = Book::whereIn('category_id', $categoryIds)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Get categories with hierarchy, exclude "Đồ chơi" and "Dụng cụ học tập"
        $rootCategories = Category::where('level', 1)
            ->whereNotIn('name', ['Đồ chơi', 'Dụng cụ học tập'])
            ->with(['children' => function($query) {
                $query->with('children');
            }])
            ->get();
            
        $authors = Author::all();
        
        // Get breadcrumb data
        $breadcrumbs = [];
        if ($category->level > 1) {
            $breadcrumbs = $category->ancestors();
        }
        
        return view('frontend.books.index_new', compact('books', 'rootCategories', 'authors', 'category', 'breadcrumbs'));
    }
    
    /**
     * Display books by author.
     */
    public function byAuthor($slug)
    {
        $author = Author::where('slug', $slug)->firstOrFail();
        
        $books = Book::where('author_id', $author->id)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Get categories with hierarchy, exclude "Đồ chơi" and "Dụng cụ học tập"
        $rootCategories = Category::where('level', 1)
            ->whereNotIn('name', ['Đồ chơi', 'Dụng cụ học tập'])
            ->with(['children' => function($query) {
                $query->with('children');
            }])
            ->get();
            
        $authors = Author::all();
        
        return view('frontend.books.index_new', compact('books', 'rootCategories', 'authors', 'author'));
    }

    /**
     * Get all descendant category IDs for a given category.
     */
    private function getCategoryDescendantIds($category)
    {
        $ids = [];
        
        // Add direct children
        if ($category->children) {
            foreach ($category->children as $child) {
                $ids[] = $child->id;
                
                // Add grandchildren if any
                if ($child->children) {
                    foreach ($child->children as $grandchild) {
                        $ids[] = $grandchild->id;
                    }
                }
            }
        }
        
        return $ids;
    }
}
