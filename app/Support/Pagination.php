<?php

namespace App\Support;

final class Pagination
{
    public const DEFAULT_PER_PAGE = 10;
    public const DEFAULT_PER_PAGE_DASHBOARD = 25;

    public const DEFAULT_PER_PAGE_OPTIONS = [
        12,
        24,
        48,
        96,
    ];

    public const DEFAULT_PER_PAGE_OPTIONS_DASHBOARD = [
        10,
        25,
        50,
        100,
    ];
}
