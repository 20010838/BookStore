<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'image_path',
        'is_primary',
        'sort_order',
        'caption',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Get the book that owns the image
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
