<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::truncate();

        foreach(SupplierType::cases() as $supplierType) {
            Supplier::create([
                'name' => $supplierType->name(),
                'slug' => $supplierType->value,
                'type' => $supplierType->value,
                'format' => array_rand($supplierType->formats()),
                'priority' => $supplierType->priority(),
            ]);
        }
    }
}
