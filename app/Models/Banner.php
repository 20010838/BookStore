<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'link_url',
        'button_text',
        'description',
        'position',
        'is_active',
        'type',
        'size'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Banner types constants
     */
    const TYPE_MAIN_SLIDER = 'main_slider';  // Banner lớn bên trái slider chính
    const TYPE_RIGHT_TOP = 'right_top';      // Banner nhỏ bên phải trên
    const TYPE_RIGHT_BOTTOM = 'right_bottom'; // Banner nhỏ bên phải dưới
    const TYPE_BOTTOM = 'bottom';            // Banner nhỏ ở dưới (4 banner)
    
    /**
     * Get available banner types
     */
    public static function getTypes()
    {
        return [
            self::TYPE_MAIN_SLIDER => 'Banner chính (Slider)',
            self::TYPE_RIGHT_TOP => 'Banner phải bên trên',
            self::TYPE_RIGHT_BOTTOM => 'Banner phải bên dưới',
            self::TYPE_BOTTOM => 'Banner dưới (4 banner)',
        ];
    }
    
    /**
     * Get active banners with specific position
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('position');
    }
    
    /**
     * Get main slider banners
     */
    public function scopeMainSlider($query)
    {
        return $query->where('type', self::TYPE_MAIN_SLIDER)
            ->where('is_active', true)
            ->orderBy('position');
    }
    
    /**
     * Get right top banner
     */
    public function scopeRightTop($query)
    {
        return $query->where('type', self::TYPE_RIGHT_TOP)
            ->where('is_active', true)
            ->orderBy('position')
            ->limit(1);
    }
    
    /**
     * Get right bottom banner
     */
    public function scopeRightBottom($query)
    {
        return $query->where('type', self::TYPE_RIGHT_BOTTOM)
            ->where('is_active', true)
            ->orderBy('position')
            ->limit(1);
    }
    
    /**
     * Get bottom banners
     */
    public function scopeBottom($query)
    {
        return $query->where('type', self::TYPE_BOTTOM)
            ->where('is_active', true)
            ->orderBy('position')
            ->limit(4);
    }
}
