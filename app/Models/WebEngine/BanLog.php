<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class BanLog extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_BAN_LOG';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = ['log_account', 'log_action', 'log_date', 'log_by'];
}
