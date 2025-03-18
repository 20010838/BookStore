<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::withCount('books')
            ->orderBy('level')
            ->orderBy('path')
            ->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = Category::where('level', '<', 3)
            ->orderBy('level')
            ->orderBy('path')
            ->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $parent = null;
        $level = 1;
        $path = '';

        if ($request->parent_id) {
            $parent = Category::findOrFail($request->parent_id);
            $level = $parent->level + 1;
            
            // Limit to 3 levels
            if ($level > 3) {
                return redirect()->back()->with('error', 'Không thể tạo danh mục quá 3 cấp.');
            }
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'level' => $level,
        ]);

        // Update path after creation
        if ($parent) {
            $category->path = $parent->path . '/' . $category->id;
        } else {
            $category->path = (string) $category->id;
        }
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::with(['books', 'parent', 'children'])->findOrFail($id);
        $ancestors = $category->ancestors();
        $descendants = $category->descendants;
        
        return view('admin.categories.show', compact('category', 'ancestors', 'descendants'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('level', '<', 3)
            ->where('id', '!=', $id)
            ->whereNotIn('id', $category->descendants()->pluck('id')->toArray())
            ->orderBy('level')
            ->orderBy('path')
            ->get();
            
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Check if trying to set a descendant as parent
        if ($request->parent_id) {
            $descendants = $category->descendants()->pluck('id')->toArray();
            if (in_array($request->parent_id, $descendants)) {
                return redirect()->back()->with('error', 'Không thể chọn danh mục con làm danh mục cha.');
            }
        }

        $parent = null;
        $level = 1;
        $path = '';

        if ($request->parent_id) {
            $parent = Category::findOrFail($request->parent_id);
            $level = $parent->level + 1;
            
            // Limit to 3 levels
            if ($level > 3) {
                return redirect()->back()->with('error', 'Không thể tạo danh mục quá 3 cấp.');
            }
            
            $path = $parent->path . '/' . $category->id;
        } else {
            $path = (string) $category->id;
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'level' => $level,
            'path' => $path,
        ]);

        // Update all descendants' level and path
        $this->updateDescendants($category);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has books
        if ($category->books()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục này vì có sách thuộc danh mục này.');
        }
        
        // Check if category has children
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục này vì có danh mục con.');
        }
        
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được xóa thành công.');
    }
    
    /**
     * Update all descendants' level and path recursively.
     */
    private function updateDescendants($category)
    {
        $children = $category->children;
        
        foreach ($children as $child) {
            $child->level = $category->level + 1;
            $child->path = $category->path . '/' . $child->id;
            $child->save();
            
            $this->updateDescendants($child);
        }
    }
}
