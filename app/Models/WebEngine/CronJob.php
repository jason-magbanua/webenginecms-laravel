<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_CRON';
    protected $primaryKey = 'cron_id';
    public $timestamps = false;

    protected $fillable = [
        'cron_name', 'cron_command', 'cron_run_time',
        'cron_last_run', 'cron_status',
    ];

    protected $casts = [
        'cron_last_run' => 'datetime',
        'cron_status'   => 'boolean',
    ];
}
