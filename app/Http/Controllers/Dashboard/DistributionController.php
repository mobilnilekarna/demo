<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDistributionJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class DistributionController extends Controller
{
    /**
     * Zobrazí stránku pro spuštění distribuce
     */
    public function index()
    {
        return Inertia::render('Dashboard/Distribution/Index');
    }

    /**
     * Spustí job pro distribuci
     */
    public function start(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        $orderId = $request->input('order_id');
        $jobId = uniqid('job_', true);

        // Vytvořit klíč pro progress v Redis
        $progressKey = "distribution:progress:{$jobId}";
        Redis::setex($progressKey, 300, json_encode([
            'status' => 'queued',
            'progress' => 0,
            'message' => 'Job zařazen do fronty...',
            'order_id' => $orderId,
        ]));

        // Spustit job s jobId - důležité: jobId musí být druhý parametr
        $job = new ProcessDistributionJob($orderId, $jobId);
        dispatch($job);

        return response()->json([
            'success' => true,
            'job_id' => $jobId,
            'message' => 'Job byl úspěšně spuštěn',
        ]);
    }

    /**
     * Získá aktuální progress jobu
     */
    public function getProgress(Request $request, string $jobId)
    {
        $progressKey = "distribution:progress:{$jobId}";
        $progressData = Redis::get($progressKey);

        if (!$progressData) {
            return response()->json([
                'status' => 'not_found',
                'progress' => 0,
                'message' => 'Job nebyl nalezen nebo již expiroval',
            ]);
        }

        $data = json_decode($progressData, true);

        return response()->json($data);
    }

    /**
     * SSE stream pro live progress updates
     */
    public function streamProgress(Request $request, string $jobId)
    {
        $progressKey = "distribution:progress:{$jobId}";

        // Nastavit SSE headers
        return response()->stream(function () use ($progressKey, $jobId) {
            $lastProgress = -1;
            $startTime = time();
            $maxDuration = 300; // Max 5 minut

            while (true) {
                // Kontrola timeoutu
                if (time() - $startTime > $maxDuration) {
                    echo "data: " . json_encode([
                        'status' => 'timeout',
                        'progress' => $lastProgress,
                        'message' => 'Connection timeout'
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                    break;
                }

                // Získat progress z Redis
                $progressData = Redis::get($progressKey);

                if (!$progressData) {
                    // Job neexistuje - poslat a ukončit
                    echo "data: " . json_encode([
                        'status' => 'not_found',
                        'progress' => $lastProgress,
                        'message' => 'Job nebyl nalezen'
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                    break;
                }

                $data = json_decode($progressData, true);
                $currentProgress = $data['progress'] ?? 0;

                // Poslat update pouze pokud se změnil progress nebo status
                if ($currentProgress != $lastProgress || $data['status'] != ($data['lastStatus'] ?? '')) {
                    echo "data: " . json_encode([
                        'status' => $data['status'] ?? 'processing',
                        'progress' => $currentProgress,
                        'message' => $data['message'] ?? 'Zpracovává se...',
                    ]) . "\n\n";
                    ob_flush();
                    flush();
                    $lastProgress = $currentProgress;

                    // Pokud je job dokončený nebo má chybu, ukončit stream
                    if (in_array($data['status'] ?? '', ['completed', 'error'])) {
                        break;
                    }
                }

                // Čekat 500ms před dalším checkem
                usleep(500000);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }
}
