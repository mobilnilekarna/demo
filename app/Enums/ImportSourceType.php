<?php

namespace App\Enums;

/**
 * Univerzální typ zdroje pro import dat (ceníky, feedy, soubory).
 */
enum ImportSourceType: string
{
    case API = 'api';
    case FTP = 'ftp';
    case URL = 'url';
    case EMAIL = 'email';
    case FILE = 'file';
    case WEBHOOK = 'webhook';
    case MANUAL = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::API => 'API',
            self::FTP => 'FTP',
            self::URL => 'URL (feed)',
            self::EMAIL => 'E-mail',
            self::FILE => 'Soubor (lokální / disk)',
            self::WEBHOOK => 'Webhook',
            self::MANUAL => 'Manuální upload',
        };
    }
}
