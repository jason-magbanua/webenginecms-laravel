<?php

namespace App\Support;

class MuHelper
{
    // Character class ID → [name, css_class]
    public const CLASSES = [
        0  => ['Dark Wizard',       'class-dw'],
        1  => ['Soul Master',       'class-sm'],
        2  => ['Grand Master',      'class-gm'],
        16 => ['Dark Knight',       'class-dk'],
        17 => ['Blade Knight',      'class-bk'],
        18 => ['Blade Master',      'class-bm'],
        32 => ['Fairy Elf',         'class-fe'],
        33 => ['Muse Elf',          'class-me'],
        34 => ['High Elf',          'class-he'],
        48 => ['Magic Gladiator',   'class-mg'],
        49 => ['Duel Master',       'class-dm'],
        64 => ['Dark Lord',         'class-dl'],
        65 => ['Lord Emperor',      'class-le'],
        80 => ['Summoner',          'class-su'],
        81 => ['Bloody Summoner',   'class-bs'],
        82 => ['Dimension Master',  'class-dim'],
        96 => ['Rage Fighter',      'class-rf'],
        97 => ['Fist Master',       'class-fm'],
    ];

    // Map number → map name
    public const MAPS = [
        0  => 'Lorencia',
        1  => 'Dungeon',
        2  => 'Devias',
        3  => 'Noria',
        4  => 'Lost Tower',
        6  => 'Atlans',
        7  => 'Tarkan',
        8  => 'Silent Map',
        9  => 'Icarus',
        10 => 'Blood Castle 1',
        11 => 'Blood Castle 2',
        12 => 'Blood Castle 3',
        13 => 'Blood Castle 4',
        14 => 'Blood Castle 5',
        15 => 'Blood Castle 6',
        16 => 'Blood Castle 7',
        17 => 'Devil Square 1',
        18 => 'Devil Square 2',
        19 => 'Devil Square 3',
        20 => 'Devil Square 4',
        22 => 'Aida',
        23 => 'Crywolf Fortress',
        24 => 'Kanturu 1',
        25 => 'Kanturu 2',
        26 => 'Kanturu 3',
        27 => 'Sanctuary',
        32 => 'Elbeland',
        33 => 'Blood Castle 8',
        34 => 'Devil Square 5',
        36 => 'Arena',
        37 => 'Vulcanus',
        38 => 'Duel Arena',
        45 => 'Land of Trials',
        46 => 'Devil Square 6',
        47 => 'Devastated Aida',
        56 => 'Nixies Lake',
        57 => 'Deep Dungeon 1',
        58 => 'Deep Dungeon 2',
        62 => 'Swamp of Calmness',
        63 => 'Raklion',
        64 => 'Swamp of Darkness',
        65 => 'Santa Village',
        66 => 'Valley of Loren',
        68 => 'Karutan 1',
        69 => 'Karutan 2',
        80 => 'Acheron',
    ];

    // PK level ID → label
    public const PK_LEVELS = [
        0 => 'Hero',
        1 => 'Commoner',
        2 => 'Outlaw',
        3 => 'Outlaw Warning',
        4 => 'Murderer',
        5 => 'Murderer+',
    ];

    public static function className(int $classId): string
    {
        return self::CLASSES[$classId][0] ?? 'Unknown';
    }

    public static function classCss(int $classId): string
    {
        return self::CLASSES[$classId][1] ?? 'class-unknown';
    }

    public static function mapName(int $mapId): string
    {
        return self::MAPS[$mapId] ?? "Map {$mapId}";
    }

    public static function pkLevel(int $level): string
    {
        return self::PK_LEVELS[$level] ?? "Level {$level}";
    }

    public static function guildMarkUrl(string $markHex): string
    {
        return route('api.guild-mark', ['data' => base64_encode($markHex)]);
    }
}
