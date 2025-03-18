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
        // Thêm danh mục đồ chơi và dụng cụ học tập
        DB::table('categories')->insert([
            [
                'name' => 'Đồ chơi',
                'slug' => 'do-choi',
                'description' => 'Đồ chơi an toàn dành cho trẻ em',
                'level' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dụng cụ học tập',
                'slug' => 'dung-cu-hoc-tap',
                'description' => 'Dụng cụ học tập chất lượng cao',
                'level' => 1,
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')
            ->whereIn('slug', ['do-choi', 'dung-cu-hoc-tap'])
            ->delete();
    }
};
