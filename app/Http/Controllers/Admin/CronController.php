<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\CronJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    public function index()
    {
        $jobs = CronJob::orderBy('cron_id')->get();
        return view('admin.cron.index', compact('jobs'));
    }

    public function toggle(int $id)
    {
        $job = CronJob::findOrFail($id);
        $job->update(['cron_status' => !$job->cron_status]);
        $state = $job->cron_status ? 'enabled' : 'disabled';
        return back()->with('success', "Cron job [{$job->cron_name}] {$state}.");
    }

    public function run(int $id)
    {
        $job = CronJob::findOrFail($id);

        try {
            Artisan::call("cron:run", ['job' => $job->cron_name]);
            $job->update(['cron_last_run' => now()]);
            return back()->with('success', "Cron job [{$job->cron_name}] executed.");
        } catch (\Exception $e) {
            return back()->with('error', "Failed to run [{$job->cron_name}]: {$e->getMessage()}");
        }
    }
}
