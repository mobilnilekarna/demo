<?php

namespace App\Enums;

enum BasketType : string {
    case OPEN = 'open';
    case CHECKED_OUT = 'checked_out';
    case ABANDONED = 'abandoned';
}



