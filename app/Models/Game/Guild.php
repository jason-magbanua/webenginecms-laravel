<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class Guild extends Model
{
    protected $connection = 'muonline';
    protected $table = 'Guild';
    protected $primaryKey = 'G_Name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'G_Name', 'G_Mark', 'G_Score', 'G_Master', 'G_Notice', 'G_Union',
    ];

    public function members()
    {
        return $this->hasMany(GuildMember::class, 'G_Name', 'G_Name');
    }

    public function master()
    {
        return $this->hasOne(Character::class, 'Name', 'G_Master');
    }
}
