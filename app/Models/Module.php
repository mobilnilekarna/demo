<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Module extends Model
{
    use HasFactory, HasRecursiveRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'route',
        'active',
        'order',
        'rank',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'order' => 'integer',
        'rank' => 'integer',
    ];

    /**
     * Custom ordering column for tree structure
     */
    public function getCustomPaths(): array
    {
        return [
            [
                'name' => 'order_path',
                'column' => 'order',
                'separator' => '.',
            ],
        ];
    }

    /**
     * Scope a query to only include active modules.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * The users that have access to this module – resolved via roles.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The roles that have access to this module.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'module_role');
    }

    /**
     * Get module structure for Inertia middleware.
     */
    public static function getForInertia(): array
    {
        return static::tree()
            ->where('active', true)
            ->orderBy('rank')
            ->orderBy('order')
            ->get()
            ->toTree()
            ->map(function ($module) {
                return static::formatModuleForInertia($module);
            })
            ->toArray();
    }

    /**
     * Format module data for frontend
     */
    public static function formatModuleForInertia($module): array
    {
        $formatted = [
            'id' => $module->id,
            'name' => $module->name,
            'slug' => $module->slug,
            'icon' => $module->icon,
            'route' => $module->route,
        ];

        if ($module->children && $module->children->isNotEmpty()) {
            $formatted['children'] = $module->children->map(function ($child) {
                return static::formatModuleForInertia($child);
            })->toArray();
        }

        return $formatted;
    }
}
