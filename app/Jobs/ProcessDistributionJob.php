<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProcessDistributionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID objednávky k zpracování
     *
     * @var int
     */
    public $orderId;

    /**
     * ID jobu pro tracking progressu
     *
     * @var string
     */
    public $jobId;

    /**
     * Create a new job instance.
     *
     * @param int $orderId
     * @param string|null $jobId
     */
    public function __construct(int $orderId, ?string $jobId = null)
    {
        $this->orderId = $orderId;
        $this->jobId = $jobId ?? uniqid('job_', true);
    }

    /**
     * Uloží progress do Redis
     *
     * @param int $progress
     * @param string $message
     * @param string $status
     * @return void
     */
    private function updateProgress(int $progress, string $message, string $status = 'processing'): void
    {
        $progressKey = "distribution:progress:{$this->jobId}";
        $data = [
            'status' => $status,
            'progress' => $progress,
            'message' => $message,
            'order_id' => $this->orderId,
            'timestamp' => time(), // Přidat timestamp pro lepší tracking
        ];

        Redis::setex($progressKey, 300, json_encode($data));

        // Publikovat do Redis pub/sub pro live updates (volitelné)
        Redis::publish("distribution:progress:{$this->jobId}", json_encode($data));

        // Debug log pro ověření
        Log::debug("Progress update", [
            'job_id' => $this->jobId,
            'progress' => $progress,
            'message' => $message,
            'status' => $status,
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info("Zahájení zpracování distribuce pro objednávku ID: {$this->orderId}, Job ID: {$this->jobId}");

        // Aktualizovat progress hned na začátku
        $this->updateProgress(1, 'Začínám zpracování...', 'processing');
        usleep(200000); // 0.2 sekundy

        // Načtení objednávky
        $order = Order::with('items')->find($this->orderId);

        if (!$order) {
            Log::error("Objednávka ID {$this->orderId} nebyla nalezena");
            $this->updateProgress(0, 'Objednávka nebyla nalezena', 'error');
            return;
        }

        $this->updateProgress(5, 'Objednávka načtena', 'processing');
        usleep(200000); // 0.2 sekundy

        echo "\n";
        $orderNumberStr = "Objednávka #{$order->order_number}";
        $padding = 42 - mb_strlen($orderNumberStr, 'UTF-8');
        $paddingStr = str_repeat(' ', max(0, $padding));

        echo "╔════════════════════════════════════════╗\n";
        echo "║  Zpracování distribuce                ║\n";
        echo "║  {$orderNumberStr}{$paddingStr}║\n";
        echo "╚════════════════════════════════════════╝\n";
        echo "ID objednávky: {$order->id}\n";
        echo "Status: {$order->status}\n";
        echo "Zákazník: {$order->first_name} {$order->last_name}\n";
        echo "Email: {$order->email}\n";
        echo "Celková částka: {$order->total} CZK\n";
        echo "\n";

        // Simulace zpracování položek objednávky
        $items = $order->items;
        $processedItems = [];
        $totalSteps = count($items) + 4; // položky + 4 kroky distribuce
        $currentStep = 0;

        $this->updateProgress(10, 'Příprava zpracování položek...', 'processing');

        echo "📦 Zpracování položek objednávky:\n";
        echo "─────────────────────────────────────────────────────────\n";

        foreach ($items as $index => $item) {
            $itemData = [
                'id' => $item->id,
                'nazev' => $item->name ?? 'Neznámá položka',
                'mnozstvi' => $item->quantity ?? 1,
                'cena' => $item->price ?? 0,
            ];

            echo "Položka " . ($index + 1) . ": {$itemData['nazev']} x{$itemData['mnozstvi']} - {$itemData['cena']} CZK\n";

            $currentStep++;
            $progress = round(($currentStep / $totalSteps) * 100);
            $this->showProgressBar($progress, "Příprava položky...");
            $this->updateProgress($progress, "Příprava položky: {$itemData['nazev']}");

            // Simulace zpracování každé položky (2 sekundy)
            $this->simulateWork(2, $progress, $totalSteps);

            $processedItems[] = $itemData;
        }

        echo "\n";
        echo "✅ Celkem zpracováno položek: " . count($processedItems) . "\n";
        echo "\n";

        // Simulace dalších kroků distribuce
        $distributionSteps = [
            'Příprava balíku' => 3,
            'Generování štítku' => 4,
            'Rezervace dopravy' => 5,
            'Označení jako připraveno k odeslání' => 3,
        ];

        echo "🚚 Kroky distribuce:\n";
        echo "─────────────────────────────────────────────────────────\n";

        foreach ($distributionSteps as $step => $duration) {
            echo "→ {$step}\n";

            $currentStep++;
            $progress = round(($currentStep / $totalSteps) * 100);
            $this->showProgressBar($progress, $step);
            $this->updateProgress($progress, $step);

            // Simulace práce s animovaným progress barem
            $this->simulateWork($duration, $progress, $totalSteps);
        }

        echo "\n";
        $this->showProgressBar(100, "Hotovo!");
        echo "\n";
        echo "╔════════════════════════════════════════╗\n";
        echo "║  ✅ Distribuce úspěšně zpracována!    ║\n";
        echo "╚════════════════════════════════════════╝\n\n";

        $this->updateProgress(100, 'Distribuce úspěšně dokončena!', 'completed');

        Log::info("Distribuce pro objednávku ID {$this->orderId} byla úspěšně zpracována", [
            'order_number' => $order->order_number,
            'items_count' => count($processedItems),
        ]);

        // V reálné aplikaci by zde bylo např. aktualizování statusu objednávky
        // $order->update(['status' => 'processing']);
    }

    /**
     * Zobrazí progress bar
     *
     * @param int $percent
     * @param string $message
     * @return void
     */
    private function showProgressBar(int $percent, string $message = ''): void
    {
        $percent = min(100, max(0, $percent));
        $filled = (int) round($percent / 2);
        $empty = 50 - $filled;

        $bar = str_repeat('█', $filled) . str_repeat('░', $empty);
        $percentStr = str_pad((string) $percent, 3, ' ', STR_PAD_LEFT);

        echo "\r";
        echo "[{$bar}] {$percentStr}% {$message}";
    }

    /**
     * Simuluje práci s animovaným progress barem
     *
     * @param int $seconds
     * @param int $startProgress
     * @param int $totalSteps
     * @return void
     */
    private function simulateWork(int $seconds, int $startProgress, int $totalSteps): void
    {
        $steps = $seconds * 10; // 10 updatů za sekundu
        $stepProgress = (100 / $totalSteps) / $steps; // Progres pro jeden krok

        for ($i = 0; $i < $steps; $i++) {
            $currentProgress = $startProgress + ($stepProgress * $i);
            $percent = (int) round(min(100, $currentProgress));

            // Animace teček
            $dots = str_repeat('.', ($i % 3) + 1);
            $this->showProgressBar($percent, "Zpracování{$dots}");

            // Aktualizovat progress v Redis každých 5 kroků (0.5 sekundy)
            if ($i % 5 === 0 || $i === 0) {
                $this->updateProgress($percent, "Zpracování{$dots}");
            }

            usleep(100000); // 0.1 sekundy
        }

        // Zobrazit finální progress pro tento krok
        $finalProgress = (int) round(min(100, $startProgress + (100 / $totalSteps)));
        $this->showProgressBar($finalProgress, "Hotovo");
        $this->updateProgress($finalProgress, "Hotovo");
        echo "\n";
    }
}
