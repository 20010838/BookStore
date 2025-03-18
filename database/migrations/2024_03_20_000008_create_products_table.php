<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->integer('stock');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->string('slug')->unique();
            
            // Thông tin chung
            $table->string('supplier')->nullable(); // Nhà cung cấp
            $table->string('brand')->nullable(); // Thương hiệu
            $table->string('brand_origin')->nullable(); // Xuất xứ thương hiệu
            $table->string('manufacturing_place')->nullable(); // Nơi sản xuất
            $table->string('color')->nullable(); // Màu sắc
            $table->string('material')->nullable(); // Chất liệu
            $table->integer('weight')->nullable(); // Trọng lượng (gr)
            $table->string('dimensions')->nullable(); // Kích thước bao bì
            
            // Thông tin dụng cụ học tập
            $table->string('ink_color')->nullable(); // Màu mực
            
            // Thông tin đồ chơi
            $table->string('age_recommendation')->nullable(); // Độ tuổi
            $table->integer('publish_year')->nullable(); // Năm xuất bản
            $table->text('technical_specs')->nullable(); // Thông số kỹ thuật
            $table->text('warnings')->nullable(); // Thông tin cảnh báo
            $table->text('usage_instructions')->nullable(); // Hướng dẫn sử dụng
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 