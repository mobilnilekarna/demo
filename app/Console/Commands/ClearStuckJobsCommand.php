<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ClearStuckJobsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:clear-stuck {--all : Vymazat všechny progress joby}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vyčistí zaseklé distribution joby';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Hledám zaseklé joby...');
        
        $pattern = 'distribution:progress:*';
        $keys = [];
        $cursor = 0;
        
        do {
            $result = Redis::scan($cursor, ['match' => $pattern, 'count' => 100]);
            $cursor = $result[0];
            $keys = array_merge($keys, $result[1]);
        } while ($cursor != 0);

        if (empty($keys)) {
            $this->info('✅ Žádné joby k vyčištění');
            return 0;
        }

        $deleted = 0;
        foreach ($keys as $key) {
            $data = Redis::get($key);
            if ($data) {
                $jobData = json_decode($data, true);
                $status = $jobData['status'] ?? 'unknown';
                $progress = $jobData['progress'] ?? 0;

                // Zaseklé joby: processing s progress < 5% nebo queued příliš dlouho
                $shouldDelete = false;
                
                if ($this->option('all')) {
                    $shouldDelete = true;
                } elseif ($status === 'processing' && $progress < 5) {
                    // Job běží, ale progress je velmi malý (pravděpodobně zaseklý)
                    $shouldDelete = true;
                    $this->warn("   Zaseklý job: {$key} - status: {$status}, progress: {$progress}%");
                } elseif ($status === 'queued') {
                    // Job je ve frontě - necháme ho
                    continue;
                } elseif (in_array($status, ['completed', 'error'])) {
                    // Dokončené joby smažeme
                    $shouldDelete = true;
                }

                if ($shouldDelete) {
                    Redis::del($key);
                    $deleted++;
                }
            }
        }

        if ($deleted > 0) {
            $this->info("✅ Vymazáno {$deleted} jobů");
        } else {
            $this->info('✅ Žádné zaseklé joby nenalezeny');
        }

        return 0;
    }
}
