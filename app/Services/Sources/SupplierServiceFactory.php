<?php

namespace App\Services\Sources;

use App\Enums\Source\SupplierType;

/**
 * Vrací konkrétní implementaci dodavatelské služby dle typu.
 * Rozhodování bez podmínek – mapování je v enumu.
 */
class SupplierServiceFactory
{
    public function for(SupplierType $type): SupplierService
    {
        return app($type->serviceClass());
    }
}
