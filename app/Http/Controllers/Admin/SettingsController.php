<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $allowed = [
        'server_name', 'server_season', 'server_files',
        'admin_usernames',
        'features.registration', 'features.player_profiles',
        'features.guild_profiles', 'features.castle_siege',
        'features.donations', 'features.votes',
        'features.rankings', 'features.downloads',
        'rankings.limit',
        'brute_force.max_attempts', 'brute_force.lockout_minutes',
        'session.timeout_minutes',
    ];

    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        // In a full implementation this would persist to a DB settings table.
        // For now we write to a runtime-writable config cache.
        $data = $request->only($this->allowed);

        foreach ($data as $key => $value) {
            // Convert dot-notation key to config key
            $configKey = 'webengine.' . str_replace('.', '.', $key);
            config([$configKey => $value]);
        }

        return back()->with('success', 'Settings updated for this session. To persist, update your .env or config/webengine.php.');
    }
}
