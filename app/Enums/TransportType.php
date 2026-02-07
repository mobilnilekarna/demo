<?php

namespace App\Enums;

use App\Enums\DeliveryType;

enum TransportType : string {
    case PERSONAL = 'personal';
    case CP_NP = 'cp-np';
    case CP_DR = 'cp-dr';
    case CP_BA = 'cp-ba';
    case PPL = 'ppl';
    case PPL_PARCEL = 'ppl-parcel';
    case DHL = 'dhl';
    case PACKETA_CZ = 'packeta-cz';
    case PACKETA_SK = 'packeta-sk';
    case DPD = 'dpd';
    case DPD_PICKUP = 'dpd-pickup';

    /**
     * Vrátí název dopravy z jazykových překladů
     */
    public function name(): string
    {
        return match($this) {
            self::PERSONAL => __('general.transport.personal'),
            self::CP_NP => __('general.transport.cp-np'),
            self::CP_DR => __('general.transport.cp-dr'),
            self::CP_BA => __('general.transport.cp-ba'),
            self::PPL => __('general.transport.ppl'),
            self::PPL_PARCEL => __('general.transport.ppl-parcel'),
            self::DHL => __('general.transport.dhl'),
            self::PACKETA_CZ => __('general.transport.packeta-cz'),
            self::PACKETA_SK => __('general.transport.packeta-sk'),
            self::DPD => __('general.transport.dpd'),
            self::DPD_PICKUP => __('general.transport.dpd-pickup'),
        };
    }

    /**
     * Vrátí cestu k obrázku dopravy
     */
    public function image(): string
    {
        $imagePath = match($this) {
            self::PERSONAL => '/images/transports/personal.png',
            self::CP_NP => '/images/transports/cp-np.png',
            self::CP_DR => '/images/transports/cp-dr.png',
            self::CP_BA => '/images/transports/cp-ba.png',
            self::PPL => '/images/transports/ppl.png',
            self::PPL_PARCEL => '/images/transports/ppl-parcel.png',
            self::DHL => '/images/transports/dhl.png',
            self::PACKETA_CZ => '/images/transports/packeta-cz.png',
            self::PACKETA_SK => '/images/transports/packeta-sk.png',
            self::DPD => '/images/transports/dpd.png',
            self::DPD_PICKUP => '/images/transports/dpd-pickup.png',
        };

        // Zkontroluj, zda soubor existuje
        if (file_exists(public_path($imagePath))) {
            return $imagePath;
        }

        // Pokud obrázek neexistuje, vrať SVG placeholder
        return $this->getSvgPlaceholder();
    }

    /**
     * Vrátí SVG placeholder pro chybějící obrázek
     */
    private function getSvgPlaceholder(): string
    {
        $name = $this->name();
        return "data:image/svg+xml," . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <rect width="100" height="100" fill="#f3f4f6"/>
                <text x="50" y="50" font-family="Arial" font-size="10" fill="#6b7280" text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($name) . '</text>
            </svg>'
        );
    }

    /**
     * Vrátí DeliveryType podle typu dopravy
     */
    public function deliveryType(): DeliveryType
    {
        return match($this) {
            // Výdejní místa (pickup)
            self::PERSONAL => DeliveryType::PICKUP,
            self::CP_NP => DeliveryType::PICKUP,
            self::CP_BA => DeliveryType::PICKUP,
            self::PPL_PARCEL => DeliveryType::PICKUP,
            self::PACKETA_CZ => DeliveryType::PICKUP,
            self::PACKETA_SK => DeliveryType::PICKUP,
            self::DPD_PICKUP => DeliveryType::PICKUP,

            // Na adresu (address)
            self::CP_DR => DeliveryType::ADDRESS,
            self::PPL => DeliveryType::ADDRESS,
            self::DHL => DeliveryType::ADDRESS,
            self::DPD => DeliveryType::ADDRESS,
        };
    }
}

