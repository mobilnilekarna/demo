<?php

namespace App\Services\Sources;

use App\Support\NumberHelper;
use App\Support\TextSanitizer;
use Carbon\Carbon;

class AllianceService extends SupplierService
{
    public function setup(): void
    {
        $this->context->mapping = [
            'ean' => 'EAN',
            'name' => 'Name',
            'price' => 'Price',
        ];
    }

    public function process(): void
    {
        $xml = $this->fetchLatest();
        $items = $xml->CenikPolozka ?? [];

        foreach ($items as $item) {
            $code = (string) $item->KodPDK;
            if (! is_numeric($code)) {
                continue;
            }

            $this->pushRow([
                'code' => $code,
                'name' => TextSanitizer::removeCDATA($item->Nazev),
                'price' => NumberHelper::priceWithVat($item->ProdejniCena, $item->SazbaDPH, 2),
                'price_purchase' => NumberHelper::priceWithVat($item->ProdejniCena, $item->SazbaDPH, 2),
                'vat' => NumberHelper::toDecimalString($item->SazbaDPH),
                'created_at' => Carbon::now(),
            ], self::DISTRIBUTION_TABLE);
        }

        $this->flushRemaining(self::DISTRIBUTION_TABLE);
    }
}

