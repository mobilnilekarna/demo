<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'entity_id',
        'model_id',
        'type',
        'price',
        'price_total',
        'vat',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_total' => 'decimal:2',
        'vat' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'model_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }
}
