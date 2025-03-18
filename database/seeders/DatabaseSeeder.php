<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            'Văn học Việt Nam',
            'Văn học nước ngoài',
            'Kinh tế',
            'Tâm lý - Kỹ năng sống',
            'Thiếu nhi',
            'Giáo khoa - Tham khảo',
            'Tiểu sử - Hồi ký',
            'Khoa học - Công nghệ',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => 'Mô tả cho danh mục ' . $categoryName,
            ]);
        }

        // Create authors
        $authors = [
            'Nguyễn Nhật Ánh' => 'Tác giả của nhiều tác phẩm văn học thiếu nhi nổi tiếng',
            'Nam Cao' => 'Nhà văn hiện thực xuất sắc của văn học Việt Nam',
            'Dale Carnegie' => 'Tác giả của nhiều cuốn sách về phát triển bản thân',
            'Paulo Coelho' => 'Nhà văn người Brazil nổi tiếng với tác phẩm Nhà giả kim',
            'J.K. Rowling' => 'Tác giả của series Harry Potter',
            'Robert Kiyosaki' => 'Tác giả của Rich Dad Poor Dad',
        ];

        foreach ($authors as $name => $biography) {
            Author::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'biography' => $biography,
            ]);
        }

        // Create books
        $books = [
            [
                'title' => 'Cho tôi xin một vé đi tuổi thơ',
                'author' => 'Nguyễn Nhật Ánh',
                'category' => 'Văn học Việt Nam',
                'price' => 85000,
                'stock' => 50,
                'description' => 'Một câu chuyện đầy cảm xúc về tuổi thơ',
                'isbn' => '9786041234567',
                'pages' => 208,
                'publisher' => 'NXB Trẻ',
                'publication_date' => '2008-01-01',
                'language' => 'Tiếng Việt',
            ],
            [
                'title' => 'Chí Phèo',
                'author' => 'Nam Cao',
                'category' => 'Văn học Việt Nam',
                'price' => 65000,
                'stock' => 30,
                'description' => 'Tác phẩm kinh điển của văn học Việt Nam',
                'isbn' => '9786041234568',
                'pages' => 150,
                'publisher' => 'NXB Văn học',
                'publication_date' => '1941-01-01',
                'language' => 'Tiếng Việt',
            ],
            [
                'title' => 'Đắc nhân tâm',
                'author' => 'Dale Carnegie',
                'category' => 'Tâm lý - Kỹ năng sống',
                'price' => 115000,
                'stock' => 100,
                'description' => 'Cuốn sách về nghệ thuật đối nhân xử thế',
                'isbn' => '9786041234569',
                'pages' => 320,
                'publisher' => 'NXB Tổng hợp',
                'publication_date' => '1936-01-01',
                'language' => 'Tiếng Việt',
            ],
            [
                'title' => 'Nhà giả kim',
                'author' => 'Paulo Coelho',
                'category' => 'Văn học nước ngoài',
                'price' => 95000,
                'stock' => 80,
                'description' => 'Câu chuyện về hành trình theo đuổi ước mơ',
                'isbn' => '9786041234570',
                'pages' => 228,
                'publisher' => 'NXB Văn học',
                'publication_date' => '1988-01-01',
                'language' => 'Tiếng Việt',
            ],
            [
                'title' => 'Harry Potter và Hòn đá Phù thủy',
                'author' => 'J.K. Rowling',
                'category' => 'Văn học nước ngoài',
                'price' => 155000,
                'stock' => 60,
                'description' => 'Phần đầu tiên của series Harry Potter',
                'isbn' => '9786041234571',
                'pages' => 366,
                'publisher' => 'NXB Trẻ',
                'publication_date' => '1997-06-26',
                'language' => 'Tiếng Việt',
            ],
            [
                'title' => 'Dạy con làm giàu - Tập 1',
                'author' => 'Robert Kiyosaki',
                'category' => 'Kinh tế',
                'price' => 125000,
                'stock' => 70,
                'description' => 'Những bài học về tài chính và đầu tư',
                'isbn' => '9786041234572',
                'pages' => 400,
                'publisher' => 'NXB Trẻ',
                'publication_date' => '2000-01-01',
                'language' => 'Tiếng Việt',
            ],
        ];

        foreach ($books as $bookData) {
            $author = Author::where('name', $bookData['author'])->first();
            $category = Category::where('name', $bookData['category'])->first();

            Book::create([
                'title' => $bookData['title'],
                'slug' => Str::slug($bookData['title']),
                'author_id' => $author->id,
                'category_id' => $category->id,
                'price' => $bookData['price'],
                'stock' => $bookData['stock'],
                'description' => $bookData['description'],
                'isbn' => $bookData['isbn'],
                'pages' => $bookData['pages'],
                'publisher' => $bookData['publisher'],
                'publication_date' => $bookData['publication_date'],
                'language' => $bookData['language'],
            ]);
        }

        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}
