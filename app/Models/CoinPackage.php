<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'beans',
        'bonus_beans',
        'price',
        'discount_price',
        'badge_label',
        'is_active',
        'order_priority',
    ];

    public function getTotalBeansAttribute(): int
    {
        return $this->beans + $this->bonus_beans;
    }

    public function getFinalPriceAttribute(): int
    {
        return $this->discount_price && $this->discount_price < $this->price
            ? $this->discount_price
            : $this->price;
    }
}
