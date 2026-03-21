<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_VOTE_LOGS';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'log_account', 'log_site_id', 'log_date', 'log_ip', 'log_credits',
    ];
}
