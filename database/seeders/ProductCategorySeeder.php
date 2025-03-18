<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
