<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\Ban;
use App\Models\WebEngine\BanLog;
use App\Models\WebEngine\BlockedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query  = Ban::orderByDesc('ban_date');

        if ($search) {
            $query->where('ban_account', 'like', "%{$search}%");
        }

        $bans = $query->paginate(25)->withQueryString();
        return view('admin.bans.index', compact('bans', 'search'));
    }

    public function create()
    {
        return view('admin.bans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ban_account' => 'required|string|max:10',
            'ban_reason'  => 'nullable|string|max:200',
            'ban_days'    => 'required|integer|min:0',
        ]);

        $exists = DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb___id', $request->ban_account)->exists();

        if (!$exists) {
            return back()->withErrors(['ban_account' => 'Account not found.']);
        }

        $expire = $request->ban_days > 0
            ? now()->addDays($request->ban_days)
            : null;

        Ban::create([
            'ban_account' => $request->ban_account,
            'ban_reason'  => $request->ban_reason,
            'ban_type'    => $request->ban_days == 0 ? 'permanent' : 'temporary',
            'ban_date'    => now(),
            'ban_expire'  => $expire,
            'ban_by'      => Auth::user()->memb___id,
        ]);

        // Set bloc_code on account
        DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb___id', $request->ban_account)
            ->update(['bloc_code' => 1]);

        BanLog::create([
            'log_account' => $request->ban_account,
            'log_action'  => 'banned',
            'log_date'    => now(),
            'log_by'      => Auth::user()->memb___id,
        ]);

        return redirect()->route('admin.bans.index')->with('success', "Account {$request->ban_account} has been banned.");
    }

    public function destroy(int $id)
    {
        $ban = Ban::findOrFail($id);

        DB::connection('memuonline')->table('MEMB_INFO')
            ->where('memb___id', $ban->ban_account)
            ->update(['bloc_code' => 0]);

        BanLog::create([
            'log_account' => $ban->ban_account,
            'log_action'  => 'unbanned',
            'log_date'    => now(),
            'log_by'      => Auth::user()->memb___id,
        ]);

        $ban->delete();

        return redirect()->route('admin.bans.index')->with('success', 'Ban lifted.');
    }

    public function blockedIps()
    {
        $ips = BlockedIp::orderByDesc('ip_id')->paginate(25);
        return view('admin.bans.blocked-ips', compact('ips'));
    }

    public function storeBlockedIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'ip_reason'  => 'nullable|string|max:200',
        ]);

        BlockedIp::create([
            'ip_address' => $request->ip_address,
            'ip_reason'  => $request->ip_reason,
            'ip_date'    => now(),
        ]);

        return back()->with('success', "IP {$request->ip_address} blocked.");
    }

    public function destroyBlockedIp(int $id)
    {
        BlockedIp::findOrFail($id)->delete();
        return back()->with('success', 'IP unblocked.');
    }
}
