<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Game\CastleSiege;
use App\Models\Game\CastleSiegeRegistration;
use App\Models\Game\Guild;

class CastleSiegeController extends Controller
{
    public function index()
    {
        $castle = CastleSiege::first();

        $registeredGuilds = CastleSiegeRegistration::get()->map(function ($reg) {
            $guildName  = $reg->G_Name ?? $reg->GuildName ?? null;
            $reg->guild = $guildName ? Guild::select('G_Name', 'G_Master', 'G_Score', 'G_Mark')
                ->where('G_Name', $guildName)->first() : null;
            return $reg;
        })->filter(fn($r) => $r->guild !== null)->values();

        return view('public.castle-siege', compact('castle', 'registeredGuilds'));
    }
}
