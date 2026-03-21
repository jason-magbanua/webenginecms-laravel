<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class VoteSite extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_VOTE_SITES';
    protected $primaryKey = 'site_id';
    public $timestamps = false;

    protected $fillable = [
        'site_name', 'site_url', 'site_callback_url',
        'site_credits', 'site_status',
    ];

    protected $casts = [
        'site_status' => 'boolean',
    ];
}
