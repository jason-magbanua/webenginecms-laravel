<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class PassChangeRequest extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_PASSCHANGE_REQUEST';
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    protected $fillable = [
        'request_account', 'request_token', 'request_date', 'request_expire',
    ];

    protected $casts = [
        'request_date'   => 'datetime',
        'request_expire' => 'datetime',
    ];
}
