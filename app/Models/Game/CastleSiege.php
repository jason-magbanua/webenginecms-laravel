<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class CastleSiege extends Model
{
    protected $connection = 'muonline';
    protected $table = 'MuCastle_DATA';
    protected $primaryKey = 'c_index';
    public $timestamps = false;
}
