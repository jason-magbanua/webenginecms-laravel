<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $allowed = [
        'server_name', 'server_season', 'server_files',
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
        $input = $request->all();
        $toSave = [];

        foreach ($this->allowed as $key) {
            if (!array_key_exists($key, $input)) {
                // Unchecked checkboxes are absent — treat as false for boolean keys
                $toSave[$key] = str_starts_with($key, 'features.') ? false : null;
                if ($toSave[$key] === null) unset($toSave[$key]);
            } else {
                $value = $input[$key];
                // Cast booleans
                if (str_starts_with($key, 'features.')) {
                    $value = (bool) $value;
                } elseif (in_array($key, ['server_season', 'rankings.limit', 'brute_force.max_attempts', 'brute_force.lockout_minutes', 'session.timeout_minutes'])) {
                    $value = (int) $value;
                }
                $toSave[$key] = $value;
            }
        }

        Settings::save($toSave);

        return back()->with('success', 'Settings saved successfully.');
    }
}
