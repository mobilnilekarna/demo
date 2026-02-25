<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Services\Sources\SupplierImportRunner;
use Illuminate\Http\Response;

class SupplierController extends Controller
{
    public function import(SupplierImportRunner $runner): Response
    {
        try {
            $runner->run();
        } catch (\Exception $e) {
            Log::error('Supplier import failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Supplier import failed: ' . $e->getMessage(),
            ], 500);
        }

        return response()->noContent();
    }
}
