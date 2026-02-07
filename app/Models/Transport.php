<?php

namespace App\Models;

use App\Enums\TransportType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transport extends Model
{
    use HasFactory;

    protected $table = 'transports';

    protected $fillable = [
        'name',
        'description',
        'price',
        'free_from',
        'order',
        'active',
        'type',
        'type_delivery',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'free_from' => 'decimal:2',
        'order' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Získá TransportType enum z typu
     */
    public function getTypeEnumAttribute(): ?TransportType
    {
        return $this->type ? TransportType::tryFrom($this->type) : null;
    }

    /**
     * Získá název dopravy z enumu nebo z databáze
     */
    public function getNameAttribute($value): string
    {
        if ($this->type_enum) {
            return $this->type_enum->name();
        }
        return $value;
    }

    /**
     * Získá obrázek dopravy z enumu
     */
    public function getImageAttribute(): string
    {
        if ($this->type_enum) {
            return $this->type_enum->image();
        }
        return $this->getDefaultSvgPlaceholder();
    }

    /**
     * Vrátí výchozí SVG placeholder
     */
    private function getDefaultSvgPlaceholder(): string
    {
        $name = $this->attributes['name'] ?? 'Doprava';
        return "data:image/svg+xml," . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <rect width="100" height="100" fill="#f3f4f6"/>
                <text x="50" y="50" font-family="Arial" font-size="10" fill="#6b7280" text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($name) . '</text>
            </svg>'
        );
    }

    /**
     * Scope pro aktivní dopravy
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope pro seřazení podle pořadí
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Vztah k shippings (kombinace s platbami)
     */
    public function shippings(): HasMany
    {
        return $this->hasMany(Shipping::class);
    }
}
