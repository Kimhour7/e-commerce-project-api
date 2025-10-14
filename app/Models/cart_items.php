<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class cart_items extends Model
{
    protected $fillable = [
        "cart_id",	
        "product_id",
        "quantity",
        "price",
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(carts::class, 'cart_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class);
    }
    /** @use HasFactory<\Database\Factories\CartItemsFactory> */
    use HasFactory;
}
