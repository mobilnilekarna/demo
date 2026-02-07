<?php

namespace App\Enums;

enum BasketItemType : string {
    case PRODUCT = 'product';
    case SERVICE = 'service';
    case BONUS = 'bonus';
    case COUPON = 'coupon';
}



