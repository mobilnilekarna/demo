<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'status',
        'user_id',
        'transport_id',
        'transport_type',
        'transport_price',
        'transport_vat',
        'payment_id',
        'payment_type',
        'payment_price',
        'payment_vat',
        'first_name',
        'last_name',
        'email',
        'phone',
        'street',
        'city',
        'zip',
        'country',
        'is_company',
        'company_name',
        'company_id',
        'company_vat_id',
        'has_delivery_address',
        'delivery_first_name',
        'delivery_last_name',
        'delivery_street',
        'delivery_city',
        'delivery_zip',
        'delivery_country',
        'note',
        'subtotal',
        'vat_total',
        'total',
        'payment_at',
        'completed_at',
    ];

    protected $casts = [
        'is_company' => 'boolean',
        'has_delivery_address' => 'boolean',
        'transport_price' => 'decimal:2',
        'transport_vat' => 'decimal:2',
        'payment_price' => 'decimal:2',
        'payment_vat' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'vat_total' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
