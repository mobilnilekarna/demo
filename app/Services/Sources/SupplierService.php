<?php

namespace App\Services\Sources;

use Illuminate\Support\Facades\DB;

abstract class SupplierService
{
    /** @var array<int, array<string, mixed>> */
    protected array $batch = [];

    public function __construct(
        protected SupplierContext $context
    ) {}

    protected function getBatchLimit(): int
    {
        return config('sources.import_batch_size', 100);
    }

    /**
     * Přidá řádek do batch a při dosažení limitu ihned zapíše do DB a vyčistí batch
     * (nikdy se neukládá 10k+ řádků do paměti, insert proběhne po každých N řádcích).
     */
    protected function pushRow(array $row, string $table): void
    {
        $this->batch[] = $row;

        if (count($this->batch) >= $this->getBatchLimit()) {
            $this->flushBatch($table);
        }
    }

    /**
     * Zapíše aktuální batch do tabulky a vyčistí ho.
     */
    protected function flushBatch(string $table): void
    {
        if ($this->batch === []) {
            return;
        }

        DB::table($table)->insert($this->batch);
        
        $this->batch = [];
    }

    /**
     * Zapíše zbytek batch na konci importu (volat po zpracování všech položek).
     */
    protected function flushRemaining(string $table): void
    {
        $this->flushBatch($table);
    }

    public function check(): void
    {
        // TODO: Implement check() method.
    }

    public function fetchLatest(): mixed
    {
        // TODO: Implement fetchLatest() method.
    }

    public function moveToRead(): void
    {
        // TODO: Implement moveToRead() method.
    }

    public function hasEnough(): void
    {
        // TODO: Implement hasEnough() method.
    }

    public function isActive(): bool
    {
        // TODO: Implement isActive() method.
    }

    abstract public function setup() : void;
    abstract public function process() : void;
}
