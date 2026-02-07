<?php

namespace App\Models;

use App\Enums\Source\SupplierType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'format',
        'priority',
        'interval',
        'ended_at',
        'interval',
        'active',
    ];

    protected $casts = [
        'type' => SupplierType::class,
        'active' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    /**
     * Distribuce (import) je aktivní a je na řadě podle intervalu.
     */
    public function scopeDueForImport(Builder $query): Builder
    {
        return $query
            ->where('active', true)
            ->whereNotNull('type')
            ->where(function (Builder $q) {
                $q->whereNull('last_run_at')
                    ->orWhereRaw('date_add(last_run_at, interval import_interval_minutes minute) <= ?', [now()]);
            });
    }
}
