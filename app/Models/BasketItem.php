<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasketItem extends Model
{
    use HasFactory;

    protected $table = 'basket_items';

    protected $fillable = [
        'basket_id',
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

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'model_id');
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
