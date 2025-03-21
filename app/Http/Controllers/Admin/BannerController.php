<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('type')->orderBy('position')->get();
        $types = Banner::getTypes();
        return view('admin.banners.index', compact('banners', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Banner::getTypes();
        return view('admin.banners.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug để kiểm tra dữ liệu gửi lên chi tiết hơn
        \Log::info('Banner form data:', $request->all());
        \Log::info('Has file images:', [$request->hasFile('images')]);
        \Log::info('Files keys:', array_keys($request->allFiles()));
        if ($request->hasFile('images')) {
            if (is_array($request->file('images'))) {
                \Log::info('Images is array with count: ' . count($request->file('images')));
            } else {
                \Log::info('Images is single file');
            }
        }
        
        try {
            // Xác định quy tắc xác nhận dựa trên loại banner
            $validationRules = [
                'subtitle' => 'nullable|string|max:255',
                'link_url' => 'nullable|url|max:255',
                'button_text' => 'nullable|string|max:50',
                'description' => 'nullable|string',
                'position' => 'nullable|integer',
                'is_active' => 'nullable|boolean',
                'type' => 'required|string|in:' . implode(',', array_keys(Banner::getTypes())),
                'size' => 'nullable|string|max:50',
            ];
            
            // Bỏ qua phần validation cho trường images và image
            // Sẽ kiểm tra thủ công sau
            if ($request->type == Banner::TYPE_MAIN_SLIDER) {
                $validationRules['title'] = 'nullable|string|max:255';
            } else {
                $validationRules['title'] = 'required|string|max:255';
            }
            
            // Thử kiểm tra nếu có tệp ảnh trong request
            $hasFiles = !empty($request->allFiles());
            \Log::info('Has any files:', [$hasFiles]);
            
            \Log::info('Validation rules:', $validationRules);
            
            // Validate dữ liệu
            $validated = $request->validate($validationRules);
            \Log::info('Validation passed', $validated);
            
            // Kiểm tra thủ công sau khi validate các trường khác
            if ($request->type == Banner::TYPE_MAIN_SLIDER) {
                // Kiểm tra chi tiết hơn cho trường hợp images
                $hasImages = false;
                
                // Cách kiểm tra chính xác nhất cho file upload
                $uploadedFiles = $request->file('images');
                if (!empty($uploadedFiles)) {
                    $hasImages = true;
                    \Log::info('Found images in request->file', ['count' => count($uploadedFiles)]);
                }
                
                // Kiểm tra trực tiếp request input
                \Log::info('Request has input images: ', [$request->has('images')]);
                
                if (!$hasImages) {
                    \Log::warning('No image files found for images field');
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['images' => 'Vui lòng chọn ít nhất một ảnh cho banner chính.']);
                }
                
                // Đã có file, tiếp tục xử lý
                \Log::info('Images validation passed, continuing to processing');
            } else {
                if (!$request->hasFile('image')) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'Vui lòng chọn ảnh cho banner.']);
                }
                
                // Validate file type manually
                $file = $request->file('image');
                if (!$file->isValid() || 
                    !in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'File không hợp lệ. Vui lòng chọn file ảnh (jpg, jpeg, png, gif).']);
                }
                
                if ($file->getSize() > 2048 * 1024) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'Kích thước file quá lớn (tối đa 2MB).']);
                }
            }
            
            // Set position as the last one if not provided (by type)
            if (!isset($validated['position'])) {
                $lastPosition = Banner::where('type', $validated['type'])->max('position');
                $validated['position'] = $lastPosition ? $lastPosition + 1 : 1;
            }
            
            // Set is_active default to true if not provided
            $validated['is_active'] = $validated['is_active'] ?? true;
            
            // Xử lý upload nhiều ảnh cho banner chính
            if ($request->type == Banner::TYPE_MAIN_SLIDER) {
                \Log::info('Processing main slider images');
                $created = false;
                
                // Lấy files trực tiếp từ request
                $files = $request->file('images');
                \Log::info('Files for processing: ', [$files]);
                
                if (!empty($files)) {
                    foreach ($files as $index => $image) {
                        \Log::info('Processing image ' . ($index + 1));
                        try {
                            $path = $image->store('banners', 'public');
                            \Log::info('Image stored at: ' . $path);
                            
                            // Tạo banner mới cho mỗi ảnh
                            $bannerData = $validated;
                            $bannerData['image_path'] = $path;
                            
                            // Nếu là ảnh đầu tiên thì giữ nguyên vị trí, các ảnh tiếp theo tăng vị trí lên
                            if ($index > 0) {
                                $bannerData['position'] = $validated['position'] + $index;
                            }
                            
                            $banner = Banner::create($bannerData);
                            \Log::info('Banner created with ID: ' . $banner->id);
                            $created = true;
                        } catch (\Exception $e) {
                            \Log::error('Error uploading image ' . ($index + 1) . ': ' . $e->getMessage());
                        }
                    }
                    
                    if ($created) {
                        return redirect()->route('admin.banners.index')
                            ->with('success', 'Banner đã được tạo thành công.');
                    }
                }
                
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['images' => 'Không thể tải lên hình ảnh. Vui lòng thử lại.']);
            }
            
            // Xử lý upload một ảnh cho các loại banner khác
            if ($request->hasFile('image')) {
                \Log::info('Processing single image');
                try {
                    $path = $request->file('image')->store('banners', 'public');
                    \Log::info('Image stored at: ' . $path);
                    $validated['image_path'] = $path;
                    
                    $banner = Banner::create($validated);
                    \Log::info('Banner created with ID: ' . $banner->id);
                    
                    return redirect()->route('admin.banners.index')
                        ->with('success', 'Banner đã được tạo thành công.');
                } catch (\Exception $e) {
                    \Log::error('Error uploading single image: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['image' => 'Không thể tải lên hình ảnh. Vui lòng thử lại.']);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Banner creation error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $banner = Banner::findOrFail($id);
        $types = Banner::getTypes();
        return view('admin.banners.edit', compact('banner', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);
        
        $validationRules = [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url|max:255',
            'button_text' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
            'type' => 'required|string|in:' . implode(',', array_keys(Banner::getTypes())),
            'size' => 'nullable|string|max:50',
        ];
        
        // Nếu không phải banner chính thì tiêu đề bắt buộc
        if ($request->type != Banner::TYPE_MAIN_SLIDER) {
            $validationRules['title'] = 'required|string|max:255';
        }
        
        $validated = $request->validate($validationRules);
        
        // Đảm bảo trường title là null nếu không có giá trị
        if ($request->type == Banner::TYPE_MAIN_SLIDER && empty($validated['title'])) {
            $validated['title'] = null;
        }
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }
        
        // Set is_active to false if not provided (since checkboxes only submit when checked)
        $validated['is_active'] = $validated['is_active'] ?? false;
        
        // If type changed, update position
        if ($banner->type != $validated['type']) {
            $lastPosition = Banner::where('type', $validated['type'])->max('position');
            $validated['position'] = $lastPosition ? $lastPosition + 1 : 1;
        }
        
        $banner->update($validated);
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);
        
        // Xóa ảnh banner
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner đã được xóa thành công.');
    }
    
    /**
     * Update position of banners
     */
    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions', []);
        
        foreach ($positions as $id => $position) {
            Banner::where('id', $id)->update(['position' => $position]);
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Toggle the active status of a banner
     */
    public function toggleActive(string $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->is_active = !$banner->is_active;
        $banner->save();
        
        return redirect()->route('admin.banners.index')
            ->with('success', 'Trạng thái banner đã được cập nhật.');
    }
}
