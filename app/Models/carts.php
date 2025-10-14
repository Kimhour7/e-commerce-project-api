<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class carts extends Model
{
    protected $fillable = [
        "user_id",
        "status",
        "total",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(cart_items::class, 'cart_id');
    }

    /** @use HasFactory<\Database\Factories\CartsFactory> */
    use HasFactory;
}
