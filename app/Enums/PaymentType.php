<?php

namespace App\Enums;

enum PaymentType : string {
    case COD = 'cod';
    case CASH = 'cash';
    case CARD = 'card';
    case BANK = 'bank';
    case BENEFIT_CARD = 'benefit_card';

    /**
     * Vrátí název platby z jazykových překladů
     */
    public function name(): string
    {
        return match($this) {
            self::COD => __('general.payment.cod'),
            self::CASH => __('general.payment.cash'),
            self::CARD => __('general.payment.card'),
            self::BANK => __('general.payment.bank'),
            self::BENEFIT_CARD => __('general.payment.benefit_card'),
        };
    }

    /**
     * Vrátí cestu k obrázku platby nebo SVG placeholder
     */
    public function image(): string
    {
        // Obrázky zatím nemáme, takže vracíme SVG placeholder
        return $this->getSvgPlaceholder();
    }

    /**
     * Vrátí SVG placeholder pro platbu
     */
    private function getSvgPlaceholder(): string
    {
        $name = $this->name();
        $icon = match($this) {
            self::COD => '💰',
            self::CASH => '💵',
            self::CARD => '💳',
            self::BANK => '🏦',
            self::BENEFIT_CARD => '🎁',
        };

        return "data:image/svg+xml," . rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <rect width="100" height="100" fill="#f3f4f6" rx="8"/>
                <text x="50" y="40" font-family="Arial" font-size="30" text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($icon) . '</text>
                <text x="50" y="70" font-family="Arial" font-size="10" fill="#6b7280" text-anchor="middle" dominant-baseline="middle">' . htmlspecialchars($name) . '</text>
            </svg>'
        );
    }
}

