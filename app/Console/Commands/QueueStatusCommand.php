<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class QueueStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zobrazí kompletní status queue a aktivních jobů';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📊 Queue Status');
        $this->newLine();

        // Queue size
        $queueKey = 'queues:default';
        $queueSize = Redis::llen($queueKey);
        
        $this->info("📦 Fronta (queues:default):");
        $this->line("   Pending jobs: {$queueSize}");
        
        if ($queueSize > 0) {
            $this->warn("   ⚠️  Ve frontě je {$queueSize} jobů - spusť 'php artisan queue:work redis' pro zpracování");
        } else {
            $this->info("   ✅ Fronta je prázdná");
        }
        
        $this->newLine();

        // Active distribution jobs
        $this->info("🔄 Aktivní distribution joby:");
        
        $pattern = 'distribution:progress:*';
        $keys = [];
        $cursor = 0;
        
        do {
            $result = Redis::scan($cursor, ['match' => $pattern, 'count' => 100]);
            $cursor = $result[0];
            $keys = array_merge($keys, $result[1]);
        } while ($cursor != 0);

        if (empty($keys)) {
            $this->line("   ❌ Žádné aktivní joby");
        } else {
            foreach ($keys as $key) {
                $data = Redis::get($key);
                if ($data) {
                    $jobData = json_decode($data, true);
                    $jobId = str_replace('distribution:progress:', '', $key);
                    
                    $status = $jobData['status'] ?? 'unknown';
                    $progress = $jobData['progress'] ?? 0;
                    $message = $jobData['message'] ?? 'N/A';
                    $orderId = $jobData['order_id'] ?? 'N/A';

                    $statusIcon = match($status) {
                        'queued' => '⏳',
                        'processing' => '🔄',
                        'completed' => '✅',
                        'error' => '❌',
                        default => '❓'
                    };

                    $this->line("   {$statusIcon} Job: " . substr($jobId, 0, 15) . "...");
                    $this->line("      Order ID: {$orderId}");
                    $this->line("      Status: {$status}");
                    $this->line("      Progress: {$progress}%");
                    $this->line("      Message: {$message}");
                    $this->newLine();
                }
            }
        }

        $this->newLine();
        $this->info("💡 Příkazy:");
        $this->line("   php artisan queue:work redis --verbose  (spustit worker)");
        $this->line("   php artisan queue:show-active            (zobrazit aktivní joby)");
        $this->line("   php artisan queue:clear-redis             (vyčistit frontu)");
        $this->line("   php artisan queue:clear-stuck             (vyčistit zaseklé joby)");

        return 0;
    }
}
