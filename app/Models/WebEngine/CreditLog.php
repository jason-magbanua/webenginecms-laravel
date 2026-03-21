<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_CREDITS_LOGS';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'log_account', 'log_credits', 'log_type', 'log_date', 'log_description',
    ];
}
