<?php

namespace App\Models\Game;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $connection = 'muonline';
    protected $table = 'Character';
    protected $primaryKey = 'Name';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'Name', 'AccountID', 'cLevel', 'LevelUpPoint', 'Class',
        'Strength', 'Dexterity', 'Vitality', 'Energy', 'Leadership',
        'Money', 'MapNumber', 'MapPosX', 'MapPosY',
        'PkCount', 'PkLevel', 'PkTime',
        'RESETS', 'GrandResets',
        'mLevel', 'mlExperience', 'mlPoint',
        'GensFamily', 'GensContribution', 'GensRanking',
    ];

    public function guildMember()
    {
        return $this->hasOne(GuildMember::class, 'Name', 'Name');
    }

    public function guild()
    {
        return $this->hasOneThrough(
            Guild::class,
            GuildMember::class,
            'Name',   // FK on GuildMember
            'G_Name', // FK on Guild
            'Name',   // local key on Character
            'G_Name'  // local key on GuildMember
        );
    }
}
