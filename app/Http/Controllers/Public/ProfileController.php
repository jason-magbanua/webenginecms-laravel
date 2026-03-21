<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    public function player(string $name)
    {
        $character   = Character::where('Name', $name)->firstOrFail();
        $guildMember = $character->guildMember;
        $guild       = $guildMember?->guild;
        $online      = in_array($name, Cache::get('online_characters', []));

        return view('public.profile.player', compact('character', 'guild', 'online'));
    }

    public function guild(string $name)
    {
        $guild   = Guild::where('G_Name', $name)->firstOrFail();
        $members = $guild->members()->get();

        return view('public.profile.guild', compact('guild', 'members'));
    }
}
