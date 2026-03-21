<?php

namespace App\Http\Controllers\UserCP;

use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use App\Models\WebEngine\CreditConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $account = Auth::user();
        $username = $account->memb___id;

        $characterList = Character::where('AccountID', $username)->get();
        $onlineCharacters = Cache::get('online_characters', []);

        $onlineStatus = $characterList->contains(fn($c) => in_array($c->Name, $onlineCharacters));

        $creditsData = [];
        $configs = CreditConfig::where('config_display', 1)->get();
        foreach ($configs as $config) {
            $identifier = match ($config->config_user_col_id) {
                'userid'   => $account->memb_guid,
                'username' => $account->memb___id,
                'email'    => $account->mail_addr,
                default    => null,
            };
            if ($identifier === null) continue;

            try {
                $connection = strtolower($config->config_database) === 'me_muonline' ? 'memuonline' : 'muonline';
                $value = DB::connection($connection)
                    ->table($config->config_table)
                    ->where($config->config_user_col, $identifier)
                    ->value($config->config_credits_col);

                $creditsData[] = [
                    'title'  => $config->config_title,
                    'amount' => $value !== null ? (int) $value : 0,
                ];
            } catch (\Exception $e) {
                // Skip unavailable credit configs silently
            }
        }

        return view('usercp.dashboard', compact(
            'account', 'characterList', 'onlineCharacters', 'creditsData', 'onlineStatus'
        ));
    }
}
