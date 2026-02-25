<?php

namespace App\Enums\Source;

use App\Services\Sources\AllianceService;
use App\Services\Sources\PhoenixService;
use App\Services\Sources\PharmosService;

enum SupplierType: string
{
    case Alliance = 'alliance';
    case Phoenix = 'phoenix';
    case Pharmos = 'pharmos';

    public function name(): string
    {
        return match ($this) {
            self::Alliance => __('general.supplier.name.alliance'),
            self::Phoenix => __('general.supplier.name.phoenix'),
            self::Pharmos => __('general.supplier.name.pharmos'),
        };
    }
    /**
     * Vrátí FQCN služby pro daný typ dodavatele.
     * Přidání nového dodavatele = nový case + jedna řádka v matchi.
     */
    public function serviceClass(): string
    {
        return match ($this) {
            self::Alliance => AllianceService::class,
            self::Phoenix => PhoenixService::class,
            self::Pharmos => PharmosService::class,
        };
    }

     public function formats() : array {
        return match($this) {
            self::Alliance => [
                FileType::XML,
                FileType::TXT
            ],
            self::Phoenix => [
                FileType::XML,
            ],
            self::Pharmos => [
                FileType::XML,
            ],
        };
    }


    public function formatDefault() : string {
        return match($this) {
            self::Alliance => FileType::XML,
            self::Phoenix => FileType::XML,
            self::Pharmos => FileType::XML,
        };
    }

    /**
     * @return array<ImportMethod>
     */
    public function methods(): array
    {
        return [
            ImportMethod::Url,
            ImportMethod::File,
            ImportMethod::Soap,
            ImportMethod::Api,
        ];
    }

    public function methodDefault(): ImportMethod
    {
        return match ($this) {
            self::Alliance => ImportMethod::File,
            self::Phoenix => ImportMethod::Api,
            self::Pharmos => ImportMethod::File,
        };
    }

    public function priority() : int {
        return match($this) {
            self::Alliance => 1,
            self::Phoenix => 2,
            self::Pharmos => 3,
        };
    }
}
