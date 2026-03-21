<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\CreditConfig;
use App\Models\WebEngine\PaypalTransaction;
use App\Models\WebEngine\VoteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditController extends Controller
{
    public function index()
    {
        $configs = CreditConfig::all();
        return view('admin.credits.index', compact('configs'));
    }

    public function add(Request $request, string $account)
    {
        $request->validate([
            'config_id' => 'required|integer',
            'amount'    => 'required|integer|min:1',
        ]);

        $config = CreditConfig::findOrFail($request->config_id);
        $identifier = $this->resolveIdentifier($config, $account);

        if ($identifier === null) {
            return back()->withErrors(['account' => 'Could not resolve account identifier.']);
        }

        $connection = strtolower($config->config_database) === 'me_muonline' ? 'memuonline' : 'muonline';

        DB::connection($connection)->table($config->config_table)
            ->where($config->config_user_col, $identifier)
            ->increment($config->config_credits_col, $request->amount);

        return back()->with('success', "Added {$request->amount} credits to {$account}.");
    }

    public function remove(Request $request, string $account)
    {
        $request->validate([
            'config_id' => 'required|integer',
            'amount'    => 'required|integer|min:1',
        ]);

        $config = CreditConfig::findOrFail($request->config_id);
        $identifier = $this->resolveIdentifier($config, $account);

        if ($identifier === null) {
            return back()->withErrors(['account' => 'Could not resolve account identifier.']);
        }

        $connection = strtolower($config->config_database) === 'me_muonline' ? 'memuonline' : 'muonline';

        DB::connection($connection)->table($config->config_table)
            ->where($config->config_user_col, $identifier)
            ->decrement($config->config_credits_col, $request->amount);

        return back()->with('success', "Removed {$request->amount} credits from {$account}.");
    }

    public function paypal()
    {
        $transactions = PaypalTransaction::orderByDesc('transaction_date')->paginate(25);
        return view('admin.credits.paypal', compact('transactions'));
    }

    public function topVoters()
    {
        $voters = VoteLog::select('log_account', DB::raw('COUNT(*) as total_votes'))
            ->groupBy('log_account')
            ->orderByDesc('total_votes')
            ->limit(50)
            ->get();

        return view('admin.credits.top-voters', compact('voters'));
    }

    private function resolveIdentifier(CreditConfig $config, string $account): mixed
    {
        if ($config->config_user_col_id === 'username') {
            return $account;
        }

        $row = DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb___id', $account)->first();

        if (!$row) return null;

        return match ($config->config_user_col_id) {
            'userid' => $row->memb_guid,
            'email'  => $row->mail_addr,
            default  => null,
        };
    }
}
