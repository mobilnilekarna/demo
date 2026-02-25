<?php

namespace App\Support;

/**
 * Statické pomocné metody pro práci s čísly a cenami (parsování, DPH, zaokrouhlování).
 */
final class NumberHelper
{
    /**
     * Převede řetězec s desetinným číslem na float (čárka → tečka).
     */
    public static function parseDecimal(mixed $value): float
    {
        $str = is_object($value) ? (string) $value : (string) $value;
        $str = str_replace(',', '.', $str);

        return (float) $str;
    }

    /**
     * Cenu vynásobí koeficientem DPH (vatPercent v procentech, např. 21) a zaokrouhlí.
     */
    public static function withVat(float $price, float $vatPercent, int $decimals = 2): float
    {
        $withVat = $price * (1 + ($vatPercent / 100));

        return round($withVat, $decimals);
    }

    /**
     * Z surové ceny a sazby DPH vypočte cenu včetně DPH (parsuje čárku/tečku, aplikuje DPH, zaokrouhlí).
     */
    public static function priceWithVat(mixed $priceRaw, mixed $vatPercent, int $decimals = 2): float
    {
        $price = self::parseDecimal($priceRaw);
        $vat = self::parseDecimal($vatPercent);

        return self::withVat($price, $vat, $decimals);
    }

    /**
     * Zaokrouhlí na zadaný počet desetinných míst.
     */
    public static function round(mixed $value, int $decimals = 2): float
    {
        $num = is_numeric($value) ? (float) $value : self::parseDecimal($value);

        return round($num, $decimals);
    }

    /**
     * Převede hodnotu na řetězec s tečkou jako desetinným oddělovačem.
     */
    public static function toDecimalString(mixed $value, int $decimals = 2): string
    {
        $num = is_numeric($value) ? (float) $value : self::parseDecimal($value);

        return number_format($num, $decimals, '.', '');
    }
}
