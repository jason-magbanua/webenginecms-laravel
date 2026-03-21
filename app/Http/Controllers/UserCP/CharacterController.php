<?php

namespace App\Http\Controllers\UserCP;

use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use App\Support\MuHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    private function getAccountCharacters(): \Illuminate\Database\Eloquent\Collection
    {
        return Character::where('AccountID', Auth::user()->memb___id)->get();
    }

    private function validateCharacterOwnership(string $characterName): ?Character
    {
        return Character::where('AccountID', Auth::user()->memb___id)
            ->where('Name', $characterName)
            ->first();
    }

    // Reset Character
    public function reset()
    {
        $characters = $this->getAccountCharacters();
        $settings = [
            'required_level' => config('webengine.reset.required_level', 400),
            'zen_cost'       => config('webengine.reset.zen_cost', 0),
            'credit_cost'    => config('webengine.reset.credit_cost', 0),
            'maximum_resets' => config('webengine.reset.maximum_resets', 0),
            'credit_reward'  => config('webengine.reset.credit_reward', 0),
        ];
        return view('usercp.reset', compact('characters', 'settings'));
    }

    public function doReset(Request $request)
    {
        $request->validate(['character' => 'required|string']);

        $char = $this->validateCharacterOwnership($request->character);
        if (!$char) {
            return back()->withErrors(['character' => 'Character not found.']);
        }

        $requiredLevel = config('webengine.reset.required_level', 400);
        if ($char->cLevel < $requiredLevel) {
            return back()->with('error', "Character must be level {$requiredLevel} to reset.");
        }

        $maxResets = config('webengine.reset.maximum_resets', 0);
        if ($maxResets > 0 && $char->RESETS >= $maxResets) {
            return back()->with('error', "Maximum resets ({$maxResets}) reached.");
        }

        DB::connection('muonline')
            ->table('Character')
            ->where('Name', $char->Name)
            ->update([
                'cLevel'  => 1,
                'RESETS'  => $char->RESETS + 1,
            ]);

        return back()->with('success', "Character {$char->Name} has been reset.");
    }

    // Add Stats
    public function addStats()
    {
        $characters = $this->getAccountCharacters();
        $cmdClasses = [16, 17, 18]; // Dark Lord classes
        $maxStats = config('webengine.addstats.max_stats', 65535);
        return view('usercp.addstats', compact('characters', 'cmdClasses', 'maxStats'));
    }

    public function doAddStats(Request $request)
    {
        $request->validate(['character' => 'required|string']);

        $char = $this->validateCharacterOwnership($request->character);
        if (!$char) {
            return back()->withErrors(['character' => 'Character not found.']);
        }

        $str = max(0, (int) $request->add_str);
        $agi = max(0, (int) $request->add_agi);
        $vit = max(0, (int) $request->add_vit);
        $ene = max(0, (int) $request->add_ene);
        $cmd = max(0, (int) $request->add_cmd);

        $total = $str + $agi + $vit + $ene + $cmd;
        if ($total <= 0) {
            return back()->with('error', 'Please enter at least one stat point to add.');
        }

        if ($total > $char->LevelUpPoint) {
            return back()->with('error', 'Not enough level-up points available.');
        }

        DB::connection('muonline')
            ->table('Character')
            ->where('Name', $char->Name)
            ->update([
                'Strength'    => $char->Strength + $str,
                'Dexterity'   => $char->Dexterity + $agi,
                'Vitality'    => $char->Vitality + $vit,
                'Energy'      => $char->Energy + $ene,
                'Leadership'  => $char->Leadership + $cmd,
                'LevelUpPoint' => $char->LevelUpPoint - $total,
            ]);

        return back()->with('success', "Stats added to {$char->Name}.");
    }

    // Clear PK
    public function clearPk()
    {
        $characters = $this->getAccountCharacters();
        $zenCost = config('webengine.clearpk.zen_cost', 0);
        return view('usercp.clearpk', compact('characters', 'zenCost'));
    }

    public function doClearPk(Request $request)
    {
        $request->validate(['character' => 'required|string']);

        $char = $this->validateCharacterOwnership($request->character);
        if (!$char) {
            return back()->withErrors(['character' => 'Character not found.']);
        }

        $zenCost = config('webengine.clearpk.zen_cost', 0);
        if ($zenCost > 0 && $char->Money < $zenCost) {
            return back()->with('error', 'Not enough zen.');
        }

        $updates = ['PkCount' => 0, 'PkLevel' => 3, 'PkTime' => 0];
        if ($zenCost > 0) {
            $updates['Money'] = $char->Money - $zenCost;
        }

        DB::connection('muonline')->table('Character')->where('Name', $char->Name)->update($updates);

        return back()->with('success', "PK status cleared for {$char->Name}.");
    }

    // Unstick Character
    public function unstick()
    {
        $characters = $this->getAccountCharacters();
        $zenCost = config('webengine.unstick.zen_cost', 0);
        return view('usercp.unstick', compact('characters', 'zenCost'));
    }

    public function doUnstick(Request $request)
    {
        $request->validate(['character' => 'required|string']);

        $char = $this->validateCharacterOwnership($request->character);
        if (!$char) {
            return back()->withErrors(['character' => 'Character not found.']);
        }

        $zenCost = config('webengine.unstick.zen_cost', 0);
        if ($zenCost > 0 && $char->Money < $zenCost) {
            return back()->with('error', 'Not enough zen.');
        }

        $updates = ['MapNumber' => 0, 'MapPosX' => 125, 'MapPosY' => 125];
        if ($zenCost > 0) {
            $updates['Money'] = $char->Money - $zenCost;
        }

        DB::connection('muonline')->table('Character')->where('Name', $char->Name)->update($updates);

        return back()->with('success', "Character {$char->Name} has been moved to Lorencia.");
    }

    // Clear Skill Tree
    public function clearSkillTree()
    {
        $characters = $this->getAccountCharacters();
        $zenCost = config('webengine.clearskilltree.zen_cost', 0);
        return view('usercp.clearskilltree', compact('characters', 'zenCost'));
    }

    public function doClearSkillTree(Request $request)
    {
        $request->validate(['character' => 'required|string']);

        $char = $this->validateCharacterOwnership($request->character);
        if (!$char) {
            return back()->withErrors(['character' => 'Character not found.']);
        }

        $zenCost = config('webengine.clearskilltree.zen_cost', 0);
        if ($zenCost > 0 && $char->Money < $zenCost) {
            return back()->with('error', 'Not enough zen.');
        }

        $updates = ['mlPoint' => $char->mlExperience > 0 ? $char->mLevel * 5 : 0];
        if ($zenCost > 0) {
            $updates['Money'] = $char->Money - $zenCost;
        }

        DB::connection('muonline')->table('Character')->where('Name', $char->Name)->update($updates);

        return back()->with('success', "Master skill tree cleared for {$char->Name}.");
    }
}
