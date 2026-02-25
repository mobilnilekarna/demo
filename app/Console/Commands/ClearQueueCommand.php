<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class ClearQueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:clear-redis {--queue=default : Název fronty}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vymaže všechny joby z Redis fronty';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $queueName = $this->option('queue');
        $queueKey = "queues:{$queueName}";
        
        $count = Redis::llen($queueKey);
        
        if ($count > 0) {
            Redis::del($queueKey);
            $this->info("✅ Vymazáno {$count} jobů z fronty '{$queueName}'");
        } else {
            $this->info("ℹ️  Fronta '{$queueName}' je již prázdná");
        }
        
        return 0;
    }
}
