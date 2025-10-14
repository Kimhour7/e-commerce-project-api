<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payments extends Model
{
    protected $fillable = [
        "order_id",
        "payment_method",
        "amount",
        "status",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(orders::class);
    }
    /** @use HasFactory<\Database\Factories\PaymentsFactory> */
    use HasFactory;
}
