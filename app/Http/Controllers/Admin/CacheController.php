<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CacheController extends Controller
{
    private array $knownKeys = [
        'rankings_level',
        'rankings_resets',
        'rankings_grandresets',
        'rankings_killers',
        'rankings_guilds',
        'rankings_votes',
        'rankings_gens',
        'rankings_master',
        'online_characters',
    ];

    public function index()
    {
        $keys = collect($this->knownKeys)->map(fn($key) => [
            'key'    => $key,
            'exists' => Cache::has($key),
        ]);

        return view('admin.cache.index', compact('keys'));
    }

    public function clear(string $key)
    {
        Cache::forget($key);
        return back()->with('success', "Cache key [{$key}] cleared.");
    }

    public function clearAll()
    {
        foreach ($this->knownKeys as $key) {
            Cache::forget($key);
        }
        return back()->with('success', 'All known cache keys cleared.');
    }
}
