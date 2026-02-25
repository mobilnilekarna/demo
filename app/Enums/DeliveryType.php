<?php

namespace App\Enums;

enum DeliveryType : string {
    case PICKUP = 'pickup';
    case ADDRESS = 'address';

    /**
     * Vrátí název typu doručení z jazykových překladů
     */
    public function name(): string
    {
        return match($this) {
            self::PICKUP => __('general.delivery.pickup'),
            self::ADDRESS => __('general.delivery.address'),
        };
    }
}


