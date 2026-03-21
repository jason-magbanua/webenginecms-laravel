<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_DOWNLOADS';
    protected $primaryKey = 'download_id';
    public $timestamps = false;

    protected $fillable = [
        'download_name', 'download_description', 'download_url',
        'download_size', 'download_date', 'download_version',
    ];
}
