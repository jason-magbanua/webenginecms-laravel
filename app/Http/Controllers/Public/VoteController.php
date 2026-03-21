<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\Vote;
use App\Models\WebEngine\VoteLog;
use App\Models\WebEngine\VoteSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function index()
    {
        $sites = VoteSite::where('site_status', true)->get();
        return view('public.vote', compact('sites'));
    }

    public function claim()
    {
        $sites = VoteSite::where('site_status', true)->get();
        return view('usercp.vote', compact('sites'));
    }

    public function processClaim(Request $request)
    {
        $request->validate(['site_id' => ['required', 'integer']]);

        $site    = VoteSite::findOrFail($request->site_id);
        $account = Auth::user()->memb___id;

        $alreadyVoted = Vote::where('vote_account', $account)
            ->where('vote_site_id', $site->site_id)
            ->whereDate('vote_date', today())->exists();

        if ($alreadyVoted) {
            return back()->withErrors(['vote' => 'You have already claimed this vote today.']);
        }

        Vote::create([
            'vote_account' => $account,
            'vote_site_id' => $site->site_id,
            'vote_date'    => now(),
            'vote_ip'      => $request->ip(),
        ]);

        VoteLog::create([
            'log_account' => $account,
            'log_site_id' => $site->site_id,
            'log_date'    => now(),
            'log_ip'      => $request->ip(),
            'log_credits' => $site->site_credits,
        ]);

        return back()->with('success', "Vote reward of {$site->site_credits} credits claimed!");
    }
}
