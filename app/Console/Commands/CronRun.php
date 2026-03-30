<?php

namespace App\Console\Commands;

use App\Models\Account\Account;
use App\Models\Account\MemberStatus;
use App\Models\Game\CastleSiege;
use App\Models\Game\CastleSiegeRegistration;
use App\Models\Game\Character;
use App\Models\Game\Guild;
use App\Models\WebEngine\AccountCountry;
use App\Models\WebEngine\Ban;
use App\Models\WebEngine\Vote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CronRun extends Command
{
    protected $signature = 'cron:run {job : The cron job name to execute}';
    protected $description = 'Run a specific WebEngine cron job by name';

    private int $limit;
    private array $excludedChars;
    private array $excludedGuilds;

    public function handle(): int
    {
        $this->limit = config('webengine.rankings.limit', 100);
        $this->excludedChars = config('webengine.rankings.excluded_chars', []);
        $this->excludedGuilds = config('webengine.rankings.excluded_guilds', []);

        $job = $this->argument('job');

        try {
            match ($job) {
                'online_characters'   => $this->onlineCharacters(),
                'temporal_bans'       => $this->temporalBans(),
                'levels_ranking'      => $this->levelsRanking(),
                'resets_ranking'      => $this->resetsRanking(),
                'grandresets_ranking' => $this->grandresetsRanking(),
                'killers_ranking'     => $this->killersRanking(),
                'guilds_ranking'      => $this->guildsRanking(),
                'votes_ranking'       => $this->votesRanking(),
                'gens_ranking'        => $this->gensRanking(),
                'masterlevel_ranking' => $this->masterlevelRanking(),
                'online_ranking'      => $this->onlineRanking(),
                'castle_siege'        => $this->castleSiege(),
                'account_country'     => $this->accountCountry(),
                'character_country'   => $this->characterCountry(),
                'server_info'         => $this->serverInfo(),
                default               => throw new \InvalidArgumentException("Unknown cron job: {$job}"),
            };

            $this->info("Cron [{$job}] completed.");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Cron [{$job}] failed: {$e->getMessage()}");
            Log::error("Cron [{$job}] failed", ['exception' => $e]);
            return self::FAILURE;
        }
    }

    // -------------------------------------------------------------------------
    // Online Characters
    // -------------------------------------------------------------------------

    private function onlineCharacters(): void
    {
        // Both MEMB_STAT and AccountCharacter live in memuonline — join is safe.
        $names = DB::connection('memuonline')
            ->table('MEMB_STAT')
            ->join('AccountCharacter', 'MEMB_STAT.memb___id', '=', 'AccountCharacter.Id')
            ->where('MEMB_STAT.ConnectStat', 1)
            ->whereNotNull('AccountCharacter.GameIDC')
            ->where('AccountCharacter.GameIDC', '!=', '')
            ->pluck('AccountCharacter.GameIDC')
            ->unique()
            ->values()
            ->all();

        Cache::put('online_characters', $names, 600);
    }

    // -------------------------------------------------------------------------
    // Temporal Bans
    // -------------------------------------------------------------------------

    private function temporalBans(): void
    {
        $expired = Ban::whereNotNull('ban_expire')
            ->where('ban_expire', '<=', now())
            ->get();

        foreach ($expired as $ban) {
            Account::where('memb___id', $ban->ban_account)
                ->update(['bloc_code' => 0]);

            $ban->delete();
        }
    }

    // -------------------------------------------------------------------------
    // Rankings
    // -------------------------------------------------------------------------

    private function levelsRanking(): void
    {
        $data = Character::select('Name', 'Class', 'cLevel', 'RESETS', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->orderByDesc('cLevel')->orderByDesc('RESETS')
            ->limit($this->limit)->get();

        Cache::put('rankings_level', $data, 600);
    }

    private function resetsRanking(): void
    {
        $data = Character::select('Name', 'Class', 'RESETS', 'cLevel', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->orderByDesc('RESETS')->orderByDesc('cLevel')
            ->limit($this->limit)->get();

        Cache::put('rankings_resets', $data, 600);
    }

    private function grandresetsRanking(): void
    {
        $data = Character::select('Name', 'Class', 'GrandResets', 'RESETS', 'cLevel', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->orderByDesc('GrandResets')->orderByDesc('RESETS')->orderByDesc('cLevel')
            ->limit($this->limit)->get();

        Cache::put('rankings_grandresets', $data, 600);
    }

    private function killersRanking(): void
    {
        $data = Character::select('Name', 'Class', 'PkCount', 'PkLevel', 'cLevel', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->where('PkCount', '>', 0)->orderByDesc('PkCount')
            ->limit($this->limit)->get();

        Cache::put('rankings_killers', $data, 600);
    }

    private function guildsRanking(): void
    {
        $data = Guild::select('G_Name', 'G_Master', 'G_Score', 'G_Mark')
            ->when($this->excludedGuilds, fn($q) => $q->whereNotIn('G_Name', $this->excludedGuilds))
            ->orderByDesc('G_Score')
            ->limit($this->limit)->get();

        Cache::put('rankings_guilds', $data, 600);
    }

    private function votesRanking(): void
    {
        $data = Vote::select('vote_account', DB::raw('COUNT(*) as vote_count'))
            ->groupBy('vote_account')->orderByDesc('vote_count')
            ->limit($this->limit)->get()
            ->map(function ($row) {
                $row->character = Character::select('Name', 'Class', 'MapNumber')
                    ->where('AccountID', $row->vote_account)
                    ->orderByDesc('cLevel')->first();
                return $row;
            });

        Cache::put('rankings_votes', $data, 600);
    }

    private function gensRanking(): void
    {
        $data = Character::select('Name', 'Class', 'GensFamily', 'GensContribution', 'GensRanking', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->where('GensContribution', '>', 0)->orderByDesc('GensContribution')
            ->limit($this->limit)->get();

        Cache::put('rankings_gens', $data, 600);
    }

    private function masterlevelRanking(): void
    {
        $data = Character::select('Name', 'Class', 'mLevel', 'cLevel', 'RESETS', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->where('mLevel', '>', 0)->orderByDesc('mLevel')
            ->limit($this->limit)->get();

        Cache::put('rankings_master', $data, 600);
    }

    private function onlineRanking(): void
    {
        $online = Cache::get('online_characters', []);

        $data = Character::select('Name', 'Class', 'cLevel', 'RESETS', 'MapNumber')
            ->when($this->excludedChars, fn($q) => $q->whereNotIn('Name', $this->excludedChars))
            ->when($online, fn($q) => $q->whereIn('Name', $online))
            ->orderByDesc('cLevel')
            ->limit($this->limit)->get();

        Cache::put('rankings_online_data', $data, 600);
    }

    // -------------------------------------------------------------------------
    // Castle Siege
    // -------------------------------------------------------------------------

    private function castleSiege(): void
    {
        $castle = CastleSiege::first();

        $registeredGuilds = CastleSiegeRegistration::get()->map(function ($reg) {
            $guildName = $reg->G_Name ?? $reg->GuildName ?? null;
            $reg->guild = $guildName
                ? Guild::select('G_Name', 'G_Master', 'G_Score', 'G_Mark')
                    ->where('G_Name', $guildName)->first()
                : null;
            return $reg;
        })->filter(fn($r) => $r->guild !== null)->values();

        Cache::put('castle_siege', compact('castle', 'registeredGuilds'), 600);
    }

    // -------------------------------------------------------------------------
    // Geolocation
    // -------------------------------------------------------------------------

    private function accountCountry(): void
    {
        $existing = AccountCountry::pluck('memb___id');

        // Use the last known IP from MEMB_STAT for accounts not yet resolved
        $accounts = MemberStatus::whereNotNull('IP')
            ->where('IP', '!=', '')
            ->whereNotIn('memb___id', $existing)
            ->take(40)
            ->get();

        foreach ($accounts as $status) {
            $ip = $status->IP;

            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                continue;
            }

            $geo = $this->resolveGeo($ip);
            if (!$geo) continue;

            AccountCountry::create([
                'memb___id'    => $status->memb___id,
                'country_code' => $geo['code'],
                'country_name' => $geo['name'],
                'last_ip'      => $ip,
                'updated_at'   => now(),
            ]);
        }
    }

    private function characterCountry(): void
    {
        // Both WEBENGINE_ACCOUNT_COUNTRY and Character live in muonline — join is safe.
        $map = DB::connection('muonline')
            ->table('WEBENGINE_ACCOUNT_COUNTRY')
            ->join('Character', 'WEBENGINE_ACCOUNT_COUNTRY.memb___id', '=', 'Character.AccountID')
            ->select('Character.Name', 'WEBENGINE_ACCOUNT_COUNTRY.country_code')
            ->pluck('country_code', 'Name')
            ->all();

        Cache::put('character_country', $map, 3600);
    }

    private function resolveGeo(string $ip): ?array
    {
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode");

            if ($response->ok() && $response->json('status') === 'success') {
                return [
                    'code' => $response->json('countryCode'),
                    'name' => $response->json('country'),
                ];
            }
        } catch (\Throwable) {
            // silently skip unresolvable IPs
        }

        return null;
    }

    // -------------------------------------------------------------------------
    // Server Info
    // -------------------------------------------------------------------------

    private function serverInfo(): void
    {
        Cache::put('server_info', [
            'accounts'   => Account::count(),
            'characters' => Character::count(),
            'guilds'     => Guild::count(),
            'online'     => MemberStatus::where('ConnectStat', 1)->count(),
        ], 600);
    }
}
