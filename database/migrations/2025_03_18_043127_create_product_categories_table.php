<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Kiểm tra xem bảng product_categories đã tồn tại chưa
        if (!Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->integer('parent_id')->nullable();
                $table->timestamps();
            });
        }
        
        // Bổ sung danh mục đồ chơi và dụng cụ học tập nếu chưa tồn tại
        $categories = [
            [
                'name' => 'Đồ chơi',
                'slug' => 'do-choi',
                'description' => 'Đồ chơi an toàn dành cho trẻ em',
                'level' => 1,
                'parent_id' => null,
            ],
            [
                'name' => 'Dụng cụ học tập',
                'slug' => 'dung-cu-hoc-tap',
                'description' => 'Dụng cụ học tập chất lượng cao',
                'level' => 1,
                'parent_id' => null,
            ],
        ];
        
        foreach ($categories as $category) {
            // Kiểm tra xem danh mục đã tồn tại chưa
            $exists = DB::table('categories')->where('slug', $category['slug'])->exists();
            
            if (!$exists) {
                DB::table('categories')->insert([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'level' => $category['level'],
                    'parent_id' => $category['parent_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa bảng product_categories nếu tồn tại
        Schema::dropIfExists('product_categories');
        
        // Xóa các category của đồ chơi và dụng cụ học tập
        DB::table('categories')->whereIn('slug', ['do-choi', 'dung-cu-hoc-tap'])->delete();
    }
};
