<?php

namespace App\Enums\Source;

/**
 * Způsob načtení dat od dodavatele (zdroj importu).
 */
enum ImportMethod: string
{
    case Url = 'url';
    case File = 'file';
    case Soap = 'soap';
    case Api = 'api';

    public function label(): string
    {
        return match ($this) {
            self::Url => __('general.import.method.url'),
            self::File => __('general.import.method.file'),
            self::Soap => __('general.import.method.soap'),
            self::Api => __('general.import.method.api'),
        };
    }
}
