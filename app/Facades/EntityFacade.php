<?php

namespace App\Facades;

use App\Models\Entity;

class EntityFacade
{
    private static array $cache = [];

    /**
     * Get entity ID by type with cache
     */
    public static function getEntityId(string $type): int
    {
        // Check cache first
        if (isset(self::$cache[$type])) {
            return self::$cache[$type];
        }

        // Load from database
        $entity = Entity::where('type', $type)->first();

        if (!$entity) {
            // Automaticky vytvoř entitu, pokud neexistuje
            $entity = Entity::create(['type' => $type]);
        }

        // Cache the result
        self::$cache[$type] = $entity->id;

        return $entity->id;
    }
}
