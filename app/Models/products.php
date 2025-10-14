<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class products extends Model
{
    protected $fillable = [
        "name",
        "description",
        "price",
        "image",
        "category_id",
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cart_items(): HasMany
    {
        return $this->hasMany(cart_items::class);
    }

    public function order_items(): HasMany
    {
        return $this->hasMany(order_items::class);
    }
    
    /* @use HasFactory<\Database\Factories\ProductsFactory> */
    use HasFactory;
}
