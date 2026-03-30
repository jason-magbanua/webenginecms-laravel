<?php

namespace Database\Seeders;

use App\Models\WebEngine\CronJob;
use Illuminate\Database\Seeder;

class CronJobSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            ['cron_name' => 'online_characters',   'cron_command' => 'cron:run online_characters',   'cron_run_time' => 60,   'cron_status' => true],
            ['cron_name' => 'temporal_bans',        'cron_command' => 'cron:run temporal_bans',        'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'levels_ranking',       'cron_command' => 'cron:run levels_ranking',       'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'resets_ranking',       'cron_command' => 'cron:run resets_ranking',       'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'grandresets_ranking',  'cron_command' => 'cron:run grandresets_ranking',  'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'killers_ranking',      'cron_command' => 'cron:run killers_ranking',      'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'guilds_ranking',       'cron_command' => 'cron:run guilds_ranking',       'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'votes_ranking',        'cron_command' => 'cron:run votes_ranking',        'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'gens_ranking',         'cron_command' => 'cron:run gens_ranking',         'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'masterlevel_ranking',  'cron_command' => 'cron:run masterlevel_ranking',  'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'online_ranking',       'cron_command' => 'cron:run online_ranking',       'cron_run_time' => 300,  'cron_status' => true],
            ['cron_name' => 'castle_siege',         'cron_command' => 'cron:run castle_siege',         'cron_run_time' => 600,  'cron_status' => true],
            ['cron_name' => 'account_country',      'cron_command' => 'cron:run account_country',      'cron_run_time' => 3600, 'cron_status' => true],
            ['cron_name' => 'character_country',    'cron_command' => 'cron:run character_country',    'cron_run_time' => 3600, 'cron_status' => true],
            ['cron_name' => 'server_info',          'cron_command' => 'cron:run server_info',          'cron_run_time' => 300,  'cron_status' => true],
        ];

        foreach ($jobs as $job) {
            CronJob::updateOrCreate(['cron_name' => $job['cron_name']], $job);
        }
    }
}
