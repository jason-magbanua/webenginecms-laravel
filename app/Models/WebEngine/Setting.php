<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_SETTINGS';
    protected $primaryKey = 'setting_key';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['setting_key', 'setting_value'];
}
