<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shippings';

    protected $fillable = [
        'transport_id',
        'payment_id',
        'price',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'order' => 'integer',
    ];

    /**
     * Vztah k dopravě
     */
    public function transport(): BelongsTo
    {
        return $this->belongsTo(Transport::class);
    }

    /**
     * Vztah k platbě
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Vypočítá finální cenu (pokud je price nastaveno, použije se, jinak součet cen transportu a platby)
     */
    public function getFinalPriceAttribute(): float
    {
        if ($this->price !== null) {
            return (float) $this->price;
        }

        $transportPrice = $this->transport ? (float) $this->transport->price : 0;
        $paymentPrice = $this->payment ? (float) $this->payment->price : 0;

        return $transportPrice + $paymentPrice;
    }
}
