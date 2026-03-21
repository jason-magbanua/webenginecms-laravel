@extends('layouts.app')
@section('title', $character->Name . ' — Player Profile')

@section('content')
<div class="page-header">
    <h2>Player Profile</h2>
</div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ $character->Name }}
                    @if($online)
                        <span class="label label-success pull-right">Online</span>
                    @else
                        <span class="label label-default pull-right">Offline</span>
                    @endif
                </h3>
            </div>
            <div class="panel-body">
                <table class="table table-condensed">
                    <tr><td class="text-right" width="40%"><strong>Class</strong></td><td>{{ \App\Support\MuHelper::className($character->Class) }}</td></tr>
                    <tr><td class="text-right"><strong>Level</strong></td><td>{{ number_format($character->cLevel) }}</td></tr>
                    @if($character->RESETS)
                    <tr><td class="text-right"><strong>Resets</strong></td><td>{{ number_format($character->RESETS) }}</td></tr>
                    @endif
                    @if($character->GrandResets)
                    <tr><td class="text-right"><strong>Grand Resets</strong></td><td>{{ number_format($character->GrandResets) }}</td></tr>
                    @endif
                    <tr><td class="text-right"><strong>Strength</strong></td><td>{{ number_format($character->Strength) }}</td></tr>
                    <tr><td class="text-right"><strong>Dexterity</strong></td><td>{{ number_format($character->Dexterity) }}</td></tr>
                    <tr><td class="text-right"><strong>Vitality</strong></td><td>{{ number_format($character->Vitality) }}</td></tr>
                    <tr><td class="text-right"><strong>Energy</strong></td><td>{{ number_format($character->Energy) }}</td></tr>
                    @if($character->Leadership)
                    <tr><td class="text-right"><strong>Leadership</strong></td><td>{{ number_format($character->Leadership) }}</td></tr>
                    @endif
                    <tr><td class="text-right"><strong>Zen</strong></td><td>{{ number_format($character->Money) }}</td></tr>
                    @if($guild)
                    <tr>
                        <td class="text-right"><strong>Guild</strong></td>
                        <td><a href="{{ route('profile.guild', $guild->G_Name) }}">{{ $guild->G_Name }}</a></td>
                    </tr>
                    @endif
                    <tr><td class="text-right"><strong>Location</strong></td><td>{{ \App\Support\MuHelper::mapName($character->MapNumber) }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
