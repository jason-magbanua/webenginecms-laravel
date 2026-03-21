<?php

namespace App\Models\WebEngine;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $connection = 'muonline';
    protected $table = 'WEBENGINE_CREDITS';
    protected $primaryKey = 'memb___id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['memb___id', 'credits', 'used'];
}
