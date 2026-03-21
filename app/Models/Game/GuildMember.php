<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    protected $connection = 'muonline';
    protected $table = 'GuildMember';
    protected $primaryKey = 'Name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['Name', 'G_Name', 'G_Level', 'G_Status'];

    public function character()
    {
        return $this->belongsTo(Character::class, 'Name', 'Name');
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class, 'G_Name', 'G_Name');
    }
}
