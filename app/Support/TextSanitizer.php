<?php

namespace App\Support;

/**
 * Statické pomocné metody pro úpravu textu (např. z XML, importů).
 * Omezuje nebezpečný obsah (script, style, HTML) a délku řetězce.
 */
final class TextSanitizer
{
    /** Tagy, které se odstraňují i s obsahem (case-insensitive). */
    private const DANGEROUS_TAGS = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'link', 'meta'];

    /**
     * Odstraní CDATA obal z řetězce. Přijímá string nebo objekt (např. SimpleXMLElement).
     */
    public static function removeCDATA(mixed $value): string
    {
        $str = is_object($value) ? (string) $value : (string) $value;

        if ($str === '') {
            return '';
        }

        $str = trim(preg_replace('/^\s*<!\[CDATA\[|\]\]>\s*$/s', '', $str));

        return trim($str);
    }

    /**
     * Odstraní nebezpečné HTML tagy (script, style, iframe, …) i s obsahem. Case-insensitive.
     * Odstraní i osamocené otevírací tagy (bez páru).
     */
    public static function removeDangerousTags(string $value): string
    {
        $str = $value;
        foreach (self::DANGEROUS_TAGS as $tag) {
            $str = preg_replace('/<' . $tag . '\b[^>]*>.*?<\/' . $tag . '>/si', '', $str);
            $str = preg_replace('/<' . $tag . '\b[^>]*\/?\s*>/si', '', $str);
        }

        return $str;
    }

    /**
     * Odstraní všechny HTML tagy z řetězce.
     */
    public static function stripAllTags(string $value): string
    {
        $str = strip_tags($value);
        $str = preg_replace('/<[^>]+>/', '', $str);

        return $str;
    }

    /**
     * Ořízne řetězec na maximální délku. Respektuje slova (neřeže uprostřed).
     */
    public static function truncate(string $value, int $maxLength, string $suffix = '…'): string
    {
        $value = trim($value);
        if ($maxLength <= 0 || mb_strlen($value) <= $maxLength) {
            return $value;
        }
        $cut = mb_substr($value, 0, $maxLength - mb_strlen($suffix));
        $lastSpace = mb_strrpos($cut, ' ');

        if ($lastSpace !== false && $lastSpace > (int) ($maxLength * 0.6)) {
            $cut = mb_substr($cut, 0, $lastSpace);
        }

        return rtrim($cut) . $suffix;
    }

    /**
     * Sanitizace pro import: CDATA, nebezpečné tagy, všechny tagy, přebytečné mezery, volitelně ořez délky.
     */
    public static function sanitizeForImport(mixed $value, ?int $maxLength = null): string
    {
        $str = self::removeCDATA($value);
        $str = self::removeDangerousTags($str);
        $str = self::stripAllTags($str);
        $str = trim(preg_replace('/\s+/u', ' ', $str));

        if ($maxLength !== null && $maxLength > 0 && mb_strlen($str) > $maxLength) {
            $str = self::truncate($str, $maxLength);
        }

        return $str;
    }

    /**
     * Normalizuje řetězec na plain text (odstraní CDATA a přebytečné mezery).
     */
    public static function toPlainText(mixed $value): string
    {
        $str = self::removeCDATA($value);

        return trim(preg_replace('/\s+/u', ' ', $str));
    }
}
