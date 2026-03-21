<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_PLUGINS';
    protected $primaryKey = 'plugin_id';
    public $timestamps = false;

    protected $fillable = [
        'plugin_name', 'plugin_slug', 'plugin_version',
        'plugin_status', 'plugin_installed_at',
    ];

    protected $casts = [
        'plugin_status'       => 'boolean',
        'plugin_installed_at' => 'datetime',
    ];
}
