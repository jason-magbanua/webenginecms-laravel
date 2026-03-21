<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\Ban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query = DB::connection('memuonline')->table('MEMB_INFO')
            ->orderByDesc('memb_guid');

        if ($search) {
            $query->where('memb___id', 'like', "%{$search}%");
        }

        $accounts = $query->paginate(25)->withQueryString();
        return view('admin.accounts.index', compact('accounts', 'search'));
    }

    public function show(int $id)
    {
        $account = DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb_guid', $id)->first();

        if (!$account) abort(404);

        $characters = \App\Models\Game\Character::where('AccountID', $account->memb___id)->get();
        $bans       = Ban::where('ban_account', $account->memb___id)->orderByDesc('ban_date')->get();
        $onlineChars = Cache::get('online_characters', []);

        return view('admin.accounts.show', compact('account', 'characters', 'bans', 'onlineChars'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'action'   => 'required|in:changepassword,changeemail,changebloc',
            'value'    => 'required|string',
        ]);

        $account = DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb_guid', $id)->first();

        if (!$account) abort(404);

        $col = match ($request->action) {
            'changepassword' => 'memb__pwd',
            'changeemail'    => 'mail_addr',
            'changebloc'     => 'bloc_code',
        };

        DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb_guid', $id)
            ->update([$col => $request->value]);

        return back()->with('success', 'Account updated.');
    }

    public function online()
    {
        $onlineNames = Cache::get('online_characters', []);
        $accounts = collect();

        if (!empty($onlineNames)) {
            $accountIds = \App\Models\Game\Character::whereIn('Name', $onlineNames)
                ->pluck('AccountID')->unique();

            $accounts = DB::connection('memuonline')->table('MEMB_INFO')
                ->whereIn('memb___id', $accountIds)->get();
        }

        return view('admin.accounts.online', compact('accounts', 'onlineNames'));
    }

    public function registrations()
    {
        $accounts = DB::connection('memuonline')->table('MEMB_INFO')
            ->orderByDesc('memb_guid')->paginate(25);

        return view('admin.accounts.registrations', compact('accounts'));
    }

    public function byIp(string $ip)
    {
        $accounts = DB::connection('memuonline')->table('MEMB_INFO')
            ->where('IP', $ip)->get();

        return view('admin.accounts.by-ip', compact('accounts', 'ip'));
    }
}
