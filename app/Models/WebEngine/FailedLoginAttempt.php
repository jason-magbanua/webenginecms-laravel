<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class FailedLoginAttempt extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_FLA';
    protected $primaryKey = 'fla_id';
    public $timestamps = false;

    protected $fillable = [
        'fla_ip', 'fla_attempts', 'fla_last_attempt', 'fla_locked_until',
    ];

    protected $casts = [
        'fla_last_attempt' => 'datetime',
        'fla_locked_until' => 'datetime',
    ];
}
