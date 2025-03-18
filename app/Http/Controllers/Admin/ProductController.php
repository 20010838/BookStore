<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->with('category')->latest()->paginate(10);

        // Get categories for dropdown
        $categories = Category::orderBy('name')->get();

        // Set page title based on category
        $pageTitle = 'Quản lý sản phẩm';
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $pageTitle = 'Quản lý ' . $category->name;
            }
        }

        return view('admin.products.index', compact('products', 'pageTitle', 'categories'));
    }

    public function create(Request $request)
    {
        $pageTitle = 'Thêm sản phẩm mới';
        
        if ($request->has('category_id')) {
            $category = Category::find($request->category_id);
            if ($category) {
                $pageTitle = 'Thêm ' . $category->name . ' mới';
            }
        }
        
        // Get categories for dropdown
        $categories = Category::orderBy('name')->get();
        
        return view('admin.products.create', compact('pageTitle', 'categories'));
    }

    public function store(Request $request)
    {
        // Validation rules for all products
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            // Common fields
            'supplier' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'brand_origin' => 'nullable|string|max:255',
            'manufacturing_place' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'weight' => 'nullable|integer',
            'dimensions' => 'nullable|string|max:255',
            'ink_color' => 'nullable|string|max:255',
            'age_recommendation' => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer',
            'technical_specs' => 'nullable|string',
            'warnings' => 'nullable|string',
            'usage_instructions' => 'nullable|string',
        ];
        
        $validated = $request->validate($rules);
        
        // Add slug
        $validated['slug'] = Str::slug($request->name);

        // Handle main image upload
        if ($request->hasFile('image')) {
            $mainImage = $request->file('image');
            $filename = time() . '.' . $mainImage->getClientOriginalExtension();
            $path = $mainImage->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        $validated['status'] = $request->has('status');

        $product = Product::create($validated);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $this->uploadProductImages($request->file('images'), $product);
        }

        $category = Category::find($request->category_id);
        $successMessage = 'Sản phẩm đã được thêm thành công.';
        
        if ($category) {
            $successMessage = $category->name . ' đã được thêm thành công.';
        }

        return redirect()->route('admin.products.index', ['category_id' => $request->category_id])
            ->with('success', $successMessage);
    }

    public function edit(Product $product)
    {
        $pageTitle = 'Chỉnh sửa sản phẩm';
        
        if ($product->category) {
            $pageTitle = 'Chỉnh sửa ' . $product->category->name;
        }
        
        // Get categories for dropdown
        $categories = Category::orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'pageTitle', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Validation rules for all products
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            // Common fields
            'supplier' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'brand_origin' => 'nullable|string|max:255',
            'manufacturing_place' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'weight' => 'nullable|integer',
            'dimensions' => 'nullable|string|max:255',
            'ink_color' => 'nullable|string|max:255',
            'age_recommendation' => 'nullable|string|max:255',
            'publish_year' => 'nullable|integer',
            'technical_specs' => 'nullable|string',
            'warnings' => 'nullable|string',
            'usage_instructions' => 'nullable|string',
        ];
        
        $validated = $request->validate($rules);
        
        // Update slug
        $validated['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $mainImage = $request->file('image');
            $filename = time() . '.' . $mainImage->getClientOriginalExtension();
            $path = $mainImage->storeAs('products', $filename, 'public');
            $validated['image'] = $path;
        }

        $validated['status'] = $request->has('status');

        $product->update($validated);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $this->uploadProductImages($request->file('images'), $product);
        }

        // Delete images if requested
        if ($request->has('delete_images')) {
            $this->deleteProductImages($request->delete_images);
        }

        $category = Category::find($request->category_id);
        $successMessage = 'Sản phẩm đã được cập nhật thành công.';
        
        if ($category) {
            $successMessage = $category->name . ' đã được cập nhật thành công.';
        }

        return redirect()->route('admin.products.index', ['category_id' => $request->category_id])
            ->with('success', $successMessage);
    }

    public function destroy(Product $product)
    {
        $category_id = $product->category_id;
        $category = $product->category;
        
        // Delete main image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete all product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        $product->delete();

        $successMessage = 'Sản phẩm đã được xóa thành công.';
        
        if ($category) {
            $successMessage = $category->name . ' đã được xóa thành công.';
        }

        return redirect()->route('admin.products.index', ['category_id' => $category_id])
            ->with('success', $successMessage);
    }
    
    /**
     * Upload multiple product images.
     */
    private function uploadProductImages($images, $product)
    {
        $sortOrder = $product->images()->max('sort_order') ?? 0;
        
        foreach ($images as $index => $image) {
            $filename = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('products/gallery', $filename, 'public');
            
            // Set the first image as primary if no primary image exists
            $isPrimary = ($index === 0 && !$product->images()->where('is_primary', true)->exists());
            
            $product->images()->create([
                'image_path' => $path,
                'is_primary' => $isPrimary,
                'sort_order' => $sortOrder + $index + 1,
                'caption' => $product->name . ' - Ảnh ' . ($index + 1),
            ]);
        }
    }

    /**
     * Delete product images by IDs.
     */
    private function deleteProductImages($imageIds)
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }
} 