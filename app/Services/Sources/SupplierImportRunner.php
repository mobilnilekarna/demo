<?php

namespace App\Services\Sources;

use App\Models\Supplier;
use App\Enums\Source\SupplierType;
use Illuminate\Support\Facades\Log;

/**
 * Řídící logika importu: načte z DB dodavatele s aktivní distribucí a splněným časem,
 * spustí příslušnou službu a po úspěchu aktualizuje last_run_at.
 */
class SupplierImportRunner
{
    public function __construct(
        protected SupplierServiceFactory $factory
    ) {}

    /**
     * Spustí import pro dodavatele z DB (active = 1, na řadě dle import_interval_minutes).
     */
    public function run(): void
    {
        Supplier::dueForImport()->get()->each(function (Supplier $supplier) {
            $this->runSupplier($supplier);
        });
    }

    private function runSupplier(Supplier $supplier): void
    {
        $type = $supplier->type;

        if ($type === null) {
            Log::warning("Supplier import skipped (missing type): {$supplier->slug}");
            return;
        }

        $service = $this->factory->for($type);

        if (! $service->isActive()) {
            Log::info("Supplier import skipped (inactive): {$supplier->slug}");
            return;
        }

        $service->setup();
        $service->check();
        $service->process();
        $service->hasEnough();
        $service->moveToRead();

        $supplier->update(['last_run_at' => now()]);
    }
}
