<?php

namespace App\Enums;

enum CodeType : string {
    case PDK = 'pdk';
    case SKU = 'sku';
    case EAN = 'ean';
    case ISBN = 'isbn';
    case GTIN = 'gtin';
    case UPC = 'upc';
    case ISRC = 'isrc';

    public function length(): int
    {
        return match($this) {
            self::PDK => 20,
            self::SKU => 10,
            self::EAN => 20,
            self::ISBN => 13,
            self::GTIN => 14,
            self::UPC => 12,
        };
    }
}
