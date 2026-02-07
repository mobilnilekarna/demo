<?php

namespace App\Context\Sources;

class SupplierContext
{
    public function __construct(
        public readonly string $file,
        public readonly string $type,
        public array $data = [],
        public array $mapping = [],
    ) {}
}
