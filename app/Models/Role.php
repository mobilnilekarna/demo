<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    /**
     * Moduly přiřazené této roli (pivot module_role).
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_role');
    }
}


