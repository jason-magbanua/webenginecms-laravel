<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_BLOCKED_IP';
    protected $primaryKey = 'ip_id';
    public $timestamps = false;

    protected $fillable = ['ip_address', 'ip_reason', 'ip_date'];
}
