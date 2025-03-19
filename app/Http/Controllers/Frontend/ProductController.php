<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->where('status', true);

        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $category = Category::find($request->category);
            
            if ($category) {
                // Nếu là danh mục cấp 1 hoặc cấp 2 thì lấy tất cả sản phẩm của danh mục con
                if ($category->level == 1 || $category->level == 2) {
                    $categoryIds = $this->getAllChildCategoryIds($category);
                    $categoryIds[] = $category->id;
                    $query->whereIn('category_id', $categoryIds);
                } else {
                    // Danh mục cấp 3, lấy chính xác sản phẩm của danh mục đó
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort products
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        
        // Lấy danh mục có tên là "Đồ chơi" hoặc "Dụng cụ học tập"
        $productCategories = Category::whereIn('name', ['Đồ chơi', 'Dụng cụ học tập'])
            ->with(['children' => function($query) {
                $query->with('children');
            }])
            ->get();

        return view('frontend.products.index', compact('products', 'productCategories'));
    }

    /**
     * Lấy tất cả ID danh mục con của một danh mục
     */
    private function getAllChildCategoryIds($category)
    {
        $ids = [];
        
        if ($category->children) {
            foreach ($category->children as $child) {
                $ids[] = $child->id;
                
                if ($child->children) {
                    foreach ($child->children as $grandChild) {
                        $ids[] = $grandChild->id;
                    }
                }
            }
        }
        
        return $ids;
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->inRandomOrder()
            ->take(4)
            ->get();
        
        // Tải thêm images cho gallery
        $product->load('images');
            
        return view('frontend.details', compact('product', 'relatedProducts'))
                ->with('item', $product)
                ->with('type', 'product');
    }

    public function byCategory($slug)
    {
        // Tìm danh mục theo slug
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $categoryIds = $this->getAllChildCategoryIds($category);
        $categoryIds[] = $category->id;
        
        $products = Product::whereIn('category_id', $categoryIds)
            ->where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Lấy danh mục có tên là "Đồ chơi" hoặc "Dụng cụ học tập"
        $productCategories = Category::whereIn('name', ['Đồ chơi', 'Dụng cụ học tập'])
            ->with(['children' => function($query) {
                $query->with('children');
            }])
            ->get();

        // Truyền thêm category để có thể hiển thị tên danh mục
        return view('frontend.products.index', compact('products', 'category', 'productCategories'));
    }
}
