<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Lấy một số sách đầu tiên để thêm ảnh
        $books = Book::take(5)->get();
        
        foreach ($books as $book) {
            // Thêm 3 ảnh cho mỗi sách
            for ($i = 1; $i <= 3; $i++) {
                // Tạo dữ liệu mẫu với một số đường dẫn ảnh giả định
                // Trong thực tế, đây sẽ là đường dẫn đến tệp ảnh thực
                $imagePath = "books/gallery/sample_{$book->id}_{$i}.jpg";
                
                // Kiểm tra xem ảnh đã tồn tại chưa
                $exists = DB::table('book_images')
                    ->where('book_id', $book->id)
                    ->where('image_path', $imagePath)
                    ->exists();
                
                if (!$exists) {
                    DB::table('book_images')->insert([
                        'book_id' => $book->id,
                        'image_path' => $imagePath,
                        'is_primary' => ($i === 1),  // Ảnh đầu tiên là ảnh chính
                        'sort_order' => $i,
                        'caption' => "{$book->title} - Ảnh {$i}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa tất cả ảnh mẫu đã thêm
        $imagePaths = [];
        
        // Tạo danh sách các đường dẫn ảnh mẫu đã thêm
        $books = Book::take(5)->get();
        foreach ($books as $book) {
            for ($i = 1; $i <= 3; $i++) {
                $imagePaths[] = "books/gallery/sample_{$book->id}_{$i}.jpg";
            }
        }
        
        DB::table('book_images')->whereIn('image_path', $imagePaths)->delete();
    }
};
