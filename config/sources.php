<?php

return [
    /*
    | Počet řádků v jednom INSERT při importu dodavatelů. Menší hodnota = menší
    | zátež DB a paměti, větší = méně dotazů. Při velkých hodnotách (řádově tisíce)
    | může INSERT spadnout (limit paketů, timeout, paměť).
    */
    'import_batch_size' => (int) env('SOURCES_IMPORT_BATCH_SIZE', 100),
];
