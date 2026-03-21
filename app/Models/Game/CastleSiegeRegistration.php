<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class CastleSiegeRegistration extends Model
{
    protected $connection = 'muonline';
    protected $table = 'MuCastle_REG_SIEGE';
    public $timestamps = false;
}
