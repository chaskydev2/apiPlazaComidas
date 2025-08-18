<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'is_promotion',
        'original_price',
        'promotion_ends_at',
        'promotion_badge'
    ];

    protected $casts = [
        'is_promotion' => 'boolean',
        'promotion_ends_at' => 'datetime'
    ];

    public function getDiscountPercentageAttribute()
    {
        if ($this->is_promotion && $this->original_price) {
            $discount = (($this->original_price - $this->price) / $this->original_price) * 100;
            return round($discount);
        }
        return 0;
    }

    public function isPromotionValid()
    {
        return $this->is_promotion && 
               ($this->promotion_ends_at === null || $this->promotion_ends_at->isFuture());
    }
}
