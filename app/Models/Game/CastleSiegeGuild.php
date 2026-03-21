<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class CastleSiegeGuild extends Model
{
    protected $connection = 'muonline';
    protected $table = 'MuCastle_SIEGE_GUILDLIST';
    public $timestamps = false;
}
