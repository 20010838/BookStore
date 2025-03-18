<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
        'status',
        'slug',
        // Thông tin chung
        'supplier',
        'brand',
        'brand_origin',
        'manufacturing_place',
        'color',
        'material',
        'weight',
        'dimensions',
        // Thông tin dụng cụ học tập
        'ink_color',
        // Thông tin đồ chơi
        'age_recommendation',
        'publish_year',
        'technical_specs',
        'warnings',
        'usage_instructions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'boolean',
        'weight' => 'integer',
        'publish_year' => 'integer'
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
} 