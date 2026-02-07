<?php

return [
    'checkout' => [
        // Typ checkoutu: 'simple' - adresní údaje nejsou povinné při výdejním místě, 'advanced' - jsou povinné
        'type' => env('BASKET_CHECKOUT_TYPE', 'simple'),
    ],
];
