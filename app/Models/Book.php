<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'author_id',
        'category_id',
        'description',
        'price',
        'stock',
        'status',
        'cover_image',
        'isbn',
        'pages',
        'publisher',
        'publication_date',
        'language',
    ];

    protected $casts = [
        'publication_date' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Get the author that owns the book
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the category that owns the book
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all reviews for the book
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all order items for the book
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all carts for the book
     */
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get all images for the book
     */
    public function images()
    {
        return $this->hasMany(BookImage::class);
    }

    /**
     * Get the primary image for the book
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->first();
    }
    
    /**
     * Get the primary image path for the book
     * Returns cover_image if exists, otherwise gets from book_images if exists
     */
    public function getPrimaryImagePathAttribute()
    {
        if ($this->cover_image) {
            return $this->cover_image;
        }
        
        $primaryImage = $this->getPrimaryImageAttribute();
        return $primaryImage ? $primaryImage->image_path : null;
    }

    /**
     * Get the average rating of the book.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    /**
     * Check if the book is in stock.
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }
}
