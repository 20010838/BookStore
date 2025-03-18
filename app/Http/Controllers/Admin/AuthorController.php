<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     */
    public function index()
    {
        $authors = Author::withCount('books')->paginate(10);
        return view('admin.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        return view('admin.authors.create');
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'biography' => $request->biography,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/authors', $filename);
            $data['photo'] = str_replace('public/', 'storage/', $path);
        }

        Author::create($data);

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã được tạo thành công.');
    }

    /**
     * Display the specified author.
     */
    public function show($id)
    {
        $author = Author::with('books')->findOrFail($id);
        return view('admin.authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit($id)
    {
        $author = Author::findOrFail($id);
        return view('admin.authors.edit', compact('author'));
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'biography' => $request->biography,
        ];

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($author->photo) {
                $oldPath = str_replace('storage/', 'public/', $author->photo);
                Storage::delete($oldPath);
            }

            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/authors', $filename);
            $data['photo'] = str_replace('public/', 'storage/', $path);
        }

        $author->update($data);

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã được cập nhật thành công.');
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        
        // Check if author has books
        if ($author->books()->count() > 0) {
            return redirect()->route('admin.authors.index')->with('error', 'Không thể xóa tác giả này vì có sách thuộc tác giả này.');
        }
        
        // Delete photo if exists
        if ($author->photo) {
            $path = str_replace('storage/', 'public/', $author->photo);
            Storage::delete($path);
        }
        
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Tác giả đã được xóa thành công.');
    }
}
