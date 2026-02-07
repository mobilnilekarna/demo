<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ShowActiveJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:show-active {--all : Zobrazit všechny joby včetně dokončených}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zobrazí aktivní distribution joby a jejich progress';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Hledám aktivní distribution joby...');
        $this->newLine();

        // Najít všechny klíče pro distribution progress
        $pattern = 'distribution:progress:*';
        $keys = [];
        
        // Redis SCAN pro nalezení všech klíčů
        $cursor = 0;
        do {
            $result = Redis::scan($cursor, ['match' => $pattern, 'count' => 100]);
            $cursor = $result[0];
            $keys = array_merge($keys, $result[1]);
        } while ($cursor != 0);

        if (empty($keys)) {
            $this->warn('❌ Žádné aktivní joby nenalezeny');
            return 0;
        }

        $this->info("✅ Nalezeno " . count($keys) . " jobů:");
        $this->newLine();

        $tableData = [];
        foreach ($keys as $key) {
            $data = Redis::get($key);
            if ($data) {
                $jobData = json_decode($data, true);
                $jobId = str_replace('distribution:progress:', '', $key);
                
                // Pokud není --all, zobrazit jen aktivní
                if (!$this->option('all')) {
                    if (in_array($jobData['status'] ?? '', ['completed', 'error', 'not_found'])) {
                        continue;
                    }
                }

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

                $tableData[] = [
                    'Job ID' => substr($jobId, 0, 20) . '...',
                    'Order ID' => $orderId,
                    'Status' => $statusIcon . ' ' . $status,
                    'Progress' => $progress . '%',
                    'Message' => substr($message, 0, 40) . (strlen($message) > 40 ? '...' : ''),
                ];
            }
        }

        if (empty($tableData)) {
            $this->warn('❌ Žádné aktivní joby (použij --all pro zobrazení všech)');
            return 0;
        }

        $this->table(
            ['Job ID', 'Order ID', 'Status', 'Progress', 'Message'],
            $tableData
        );

        $this->newLine();
        $this->info('💡 Tip: Použij --all pro zobrazení všech jobů včetně dokončených');

        return 0;
    }
}
