<?php

namespace App\Enums;

enum Entity : string {

    // Max 20 characters
    case PRODUCT = 'product';
    case ARTICLE = 'article';
    case USER = 'user';
    case PAGE = 'page';
    case BANNER = 'banner';
    case BRAND = 'brand';
    case MESSAGE = 'message';
    case REGISTER = 'register';
    case CUSTOMER = 'customer';
    case TRANSPORT = 'transport';
    case PAYMENT = 'payment';
    case ATTRIBUTE = 'attribute';
}

