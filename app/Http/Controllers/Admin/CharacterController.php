<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game\Character;
use App\Support\MuHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $query  = Character::query();

        if ($search) {
            $query->where('Name', 'like', "%{$search}%")
                  ->orWhere('AccountID', 'like', "%{$search}%");
        }

        $characters = $query->orderByDesc('cLevel')->paginate(25)->withQueryString();
        return view('admin.characters.index', compact('characters', 'search'));
    }

    public function edit(string $name)
    {
        $character = Character::findOrFail($name);
        return view('admin.characters.edit', compact('character'));
    }

    public function update(Request $request, string $name)
    {
        $request->validate([
            'AccountID'    => 'required|string|max:10',
            'cLevel'       => 'required|integer|min:1|max:800',
            'LevelUpPoint' => 'required|integer|min:0',
            'Class'        => 'required|integer|min:0',
            'Strength'     => 'required|integer|min:0',
            'Dexterity'    => 'required|integer|min:0',
            'Vitality'     => 'required|integer|min:0',
            'Energy'       => 'required|integer|min:0',
            'Leadership'   => 'required|integer|min:0',
            'Money'        => 'required|integer|min:0',
            'PkLevel'      => 'required|integer|min:0',
            'RESETS'       => 'nullable|integer|min:0',
            'GrandResets'  => 'nullable|integer|min:0',
            'mLevel'       => 'required|integer|min:0',
            'mlPoint'      => 'required|integer|min:0',
        ]);

        $character = Character::findOrFail($name);
        $character->update($request->only([
            'AccountID', 'cLevel', 'LevelUpPoint', 'Class',
            'Strength', 'Dexterity', 'Vitality', 'Energy', 'Leadership',
            'Money', 'PkLevel', 'RESETS', 'GrandResets', 'mLevel', 'mlPoint',
        ]));

        return redirect()->route('admin.characters.index')->with('success', "Character {$name} updated.");
    }
}
