<?php

namespace App\Console\Commands;

use App\Models\WebEngine\CronJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CronDispatch extends Command
{
    protected $signature = 'cron:dispatch';
    protected $description = 'Run all enabled WebEngine cron jobs that are due';

    public function handle(): int
    {
        $jobs = CronJob::where('cron_status', true)->get();

        if ($jobs->isEmpty()) {
            $this->info('No enabled cron jobs found.');
            return self::SUCCESS;
        }

        $ran = 0;

        foreach ($jobs as $job) {
            if (!$this->isDue($job)) {
                continue;
            }

            try {
                Artisan::call('cron:run', ['job' => $job->cron_name]);
                $job->update(['cron_last_run' => now()]);
                $this->info("Ran: {$job->cron_name}");
                $ran++;
            } catch (\Throwable $e) {
                $this->error("Failed [{$job->cron_name}]: {$e->getMessage()}");
                Log::error("cron:dispatch failed for [{$job->cron_name}]", ['exception' => $e]);
            }
        }

        $this->info("cron:dispatch complete. Ran {$ran} job(s).");
        return self::SUCCESS;
    }

    private function isDue(CronJob $job): bool
    {
        if ($job->cron_last_run === null) {
            return true;
        }

        // cron_run_time is the interval in seconds
        $nextRun = $job->cron_last_run->timestamp + $job->cron_run_time;

        return now()->timestamp >= $nextRun;
    }
}
