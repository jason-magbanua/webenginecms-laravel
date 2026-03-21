<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Account\MemberStatus;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use App\Models\WebEngine\Vote;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RankingsController extends Controller
{
    private int $limit;
    private array $excludedChars;
    private array $excludedGuilds;

    public function __construct()
    {
        $this->limit          = config('webengine.rankings.limit', 100);
        $this->excludedChars  = config('webengine.rankings.excluded_chars', []);
        $this->excludedGuilds = config('webengine.rankings.excluded_guilds', []);
    }

    public function index()
    {
        return redirect()->route('rankings.level');
    }

    public function level()
    {
        $data = Cache::remember('rankings_level', 300, fn() =>
            Character::select('Name', 'Class', 'cLevel', 'RESETS', 'MapNumber')
                ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
                ->orderByDesc('cLevel')->orderByDesc('RESETS')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.level', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function resets()
    {
        $data = Cache::remember('rankings_resets', 300, fn() =>
            Character::select('Name', 'Class', 'RESETS', 'cLevel', 'MapNumber')
                ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
                ->orderByDesc('RESETS')->orderByDesc('cLevel')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.resets', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function grandResets()
    {
        $data = Cache::remember('rankings_grandresets', 300, fn() =>
            Character::select('Name', 'Class', 'GrandResets', 'RESETS', 'cLevel', 'MapNumber')
                ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
                ->orderByDesc('GrandResets')->orderByDesc('RESETS')->orderByDesc('cLevel')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.grandresets', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function killers()
    {
        $data = Cache::remember('rankings_killers', 300, fn() =>
            Character::select('Name', 'Class', 'PkCount', 'PkLevel', 'cLevel', 'MapNumber')
                ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
                ->where('PkCount', '>', 0)->orderByDesc('PkCount')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.killers', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function guilds()
    {
        $data = Cache::remember('rankings_guilds', 300, fn() =>
            Guild::select('G_Name', 'G_Master', 'G_Score', 'G_Mark')
                ->when($this->excludedGuilds, fn($q) => $q->whereNotIn('G_Name', $this->excludedGuilds))
                ->orderByDesc('G_Score')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.guilds', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function votes()
    {
        $data = Cache::remember('rankings_votes', 300, fn() =>
            Vote::select('vote_account', DB::raw('COUNT(*) as vote_count'))
                ->groupBy('vote_account')->orderByDesc('vote_count')
                ->limit($this->limit)->get()
                ->map(function ($row) {
                    $row->character = Character::select('Name', 'Class', 'MapNumber')
                        ->where('AccountID', $row->vote_account)
                        ->orderByDesc('cLevel')->first();
                    return $row;
                })
        );
        return view('public.rankings.votes', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function gens()
    {
        $data = Cache::remember('rankings_gens', 300, fn() =>
            Character::select('Name', 'Class', 'GensFamily', 'GensContribution', 'GensRanking', 'MapNumber')
                ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
                ->where('GensContribution', '>', 0)->orderByDesc('GensContribution')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.gens', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function master()
    {
        $data = Cache::remember('rankings_master', 300, fn() =>
            Character::select('Name', 'Class', 'mLevel', 'cLevel', 'RESETS', 'MapNumber')
                ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
                ->where('mLevel', '>', 0)->orderByDesc('mLevel')
                ->limit($this->limit)->get()
        );
        return view('public.rankings.master', ['data' => $data, 'online' => $this->onlineNames()]);
    }

    public function online()
    {
        $online = MemberStatus::where('ConnectStat', 1)
            ->select('memb___id', 'ServerName', 'IP')->get()
            ->map(function ($status) {
                $status->character = Character::select('Name', 'Class', 'cLevel', 'MapNumber')
                    ->where('AccountID', $status->memb___id)
                    ->orderByDesc('cLevel')->first();
                return $status;
            })
            ->filter(fn($s) => $s->character !== null)->values();

        return view('public.rankings.online', compact('online'));
    }

    private function onlineNames(): array
    {
        return Cache::get('online_characters', []);
    }
}
