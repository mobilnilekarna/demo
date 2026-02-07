<?php

namespace App\Enums;

enum StockState : int {
    case IN_STOCK = 1;
    case OUT_OF_STOCK = 2;
    case PREORDER = 3;
    case IN_SUPLIER_STOCK = 4;

    public function name(): string
    {
        return match($this) {
            self::IN_STOCK => __('general.stock.in_stock'),
            self::OUT_OF_STOCK => __('general.stock.out_of_stock'),
            self::PREORDER => __('general.stock.preorder'),
            self::IN_SUPLIER_STOCK => __('general.stock.in_supplier_stock'),
        };
    }
}
