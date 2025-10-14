<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class order_items extends Model
{
    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "price",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(orders::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(products::class);
    }
    /** @use HasFactory<\Database\Factories\OrderItemsFactory> */
    use HasFactory;
}
