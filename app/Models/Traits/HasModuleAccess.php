<?php

namespace App\Models\Traits;

use App\Models\Module;

trait HasModuleAccess
{
    /**
     * The modules that are assigned to this role.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_role');
    }
}


