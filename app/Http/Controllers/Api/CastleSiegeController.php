<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class CastleSiegeController extends Controller
{
    /**
     * GET /api/castle-siege
     *
     * Returns live castle siege data from cache (populated by the castle_siege cron job).
     * Consumers can poll this endpoint to display real-time siege state without
     * hitting the game database on every request.
     */
    public function live(): JsonResponse
    {
        $data = Cache::get('castle_siege');

        if (!$data) {
            return response()->json(['castle' => null, 'registered_guilds' => []]);
        }

        $castle = $data['castle'];
        $guilds = collect($data['registeredGuilds'])->map(fn($reg) => [
            'guild_name'   => $reg->guild->G_Name   ?? null,
            'guild_master' => $reg->guild->G_Master ?? null,
            'guild_score'  => $reg->guild->G_Score  ?? 0,
            'mark_url'     => $reg->guild->G_Name
                ? url('/api/guild-mark/' . urlencode($reg->guild->G_Name))
                : null,
        ])->values();

        return response()->json([
            'castle'            => $castle,
            'registered_guilds' => $guilds,
        ]);
    }
}
