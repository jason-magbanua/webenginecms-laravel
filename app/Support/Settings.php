<?php

namespace App\Support;

use App\Models\WebEngine\Setting;
use Illuminate\Support\Facades\Cache;

class Settings
{
    private const CACHE_KEY = 'webengine_settings';
    private const CACHE_TTL = 3600;

    /**
     * Load all settings from DB and merge into the webengine config.
     * Called once at boot via AppServiceProvider.
     */
    public static function boot(): void
    {
        try {
            $settings = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn() =>
                Setting::pluck('setting_value', 'setting_key')->all()
            );

            foreach ($settings as $key => $value) {
                $decoded = json_decode($value, true);
                config(['webengine.' . $key => $decoded !== null ? $decoded : $value]);
            }
        } catch (\Throwable) {
            // DB may not be ready (e.g., fresh install) — fall back to defaults silently.
        }
    }

    /**
     * Persist a set of key=>value pairs (dot-notation keys under webengine.*).
     * Values are JSON-encoded for uniform storage.
     */
    public static function save(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['setting_key' => $key],
                ['setting_value' => json_encode($value)]
            );

            config(['webengine.' . $key => $value]);
        }

        Cache::forget(self::CACHE_KEY);
    }
}
