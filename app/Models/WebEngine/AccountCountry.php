<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class AccountCountry extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_ACCOUNT_COUNTRY';
    protected $primaryKey = 'memb___id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'memb___id', 'country_code', 'country_name', 'last_ip', 'updated_at',
    ];
}
