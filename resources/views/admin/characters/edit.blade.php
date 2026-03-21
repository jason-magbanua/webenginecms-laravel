@extends('layouts.admin')
@section('title', 'Edit Character: ' . $character->Name)

@section('content')
<h1 class="page-header">Edit Character: {{ $character->Name }}</h1>

<form action="{{ route('admin.characters.update', $character->Name) }}" method="POST">
    @csrf @method('PUT')
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Account</label>
                <input type="text" class="form-control" name="AccountID" value="{{ old('AccountID', $character->AccountID) }}" required>
            </div>
            <div class="form-group">
                <label>Class</label>
                <input type="number" class="form-control" name="Class" value="{{ old('Class', $character->Class) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Level</label>
                <input type="number" class="form-control" name="cLevel" value="{{ old('cLevel', $character->cLevel) }}" min="1" max="800" required>
            </div>
            <div class="form-group">
                <label>Level Up Points</label>
                <input type="number" class="form-control" name="LevelUpPoint" value="{{ old('LevelUpPoint', $character->LevelUpPoint) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Resets</label>
                <input type="number" class="form-control" name="RESETS" value="{{ old('RESETS', $character->RESETS) }}" min="0">
            </div>
            <div class="form-group">
                <label>Grand Resets</label>
                <input type="number" class="form-control" name="GrandResets" value="{{ old('GrandResets', $character->GrandResets) }}" min="0">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Strength</label>
                <input type="number" class="form-control" name="Strength" value="{{ old('Strength', $character->Strength) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Agility</label>
                <input type="number" class="form-control" name="Dexterity" value="{{ old('Dexterity', $character->Dexterity) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Vitality</label>
                <input type="number" class="form-control" name="Vitality" value="{{ old('Vitality', $character->Vitality) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Energy</label>
                <input type="number" class="form-control" name="Energy" value="{{ old('Energy', $character->Energy) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Command (Leadership)</label>
                <input type="number" class="form-control" name="Leadership" value="{{ old('Leadership', $character->Leadership) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Zen</label>
                <input type="number" class="form-control" name="Money" value="{{ old('Money', $character->Money) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>PK Level</label>
                <input type="number" class="form-control" name="PkLevel" value="{{ old('PkLevel', $character->PkLevel) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Master Level</label>
                <input type="number" class="form-control" name="mLevel" value="{{ old('mLevel', $character->mLevel ?? 0) }}" min="0" required>
            </div>
            <div class="form-group">
                <label>Master Level Points</label>
                <input type="number" class="form-control" name="mlPoint" value="{{ old('mlPoint', $character->mlPoint ?? 0) }}" min="0" required>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="{{ route('admin.characters.index') }}" class="btn btn-default">Cancel</a>
</form>
@endsection
