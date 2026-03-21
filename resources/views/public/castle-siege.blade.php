@extends('layouts.app')
@section('title', 'Castle Siege')

@section('content')
<div class="page-header"><h2>Castle Siege</h2></div>

@if(!$castle)
    <div class="alert alert-info">Castle Siege data is not available.</div>
@else

{{-- Castle Owner --}}
@if($castle->OccupyGuild ?? null)
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Castle Owner</h3></div>
    <div class="panel-body">
        <strong>Guild:</strong> <a href="{{ route('profile.guild', $castle->OccupyGuild) }}">{{ $castle->OccupyGuild }}</a>
    </div>
</div>
@endif

{{-- Castle Info --}}
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Castle Information</h3></div>
    <div class="panel-body" style="padding:0;">
        <table class="table table-condensed" style="margin:0;">
            <tbody>
                @if(isset($castle->TaxChaos))
                <tr><td width="40%">Chaos Tax</td><td>{{ $castle->TaxChaos }}%</td></tr>
                @endif
                @if(isset($castle->TaxStore))
                <tr><td>Store Tax</td><td>{{ $castle->TaxStore }}%</td></tr>
                @endif
                @if(isset($castle->TaxHuntZone))
                <tr><td>Hunt Zone Tax</td><td>{{ $castle->TaxHuntZone }}%</td></tr>
                @endif
                @if(isset($castle->Money))
                <tr><td>Castle Funds</td><td>{{ number_format($castle->Money) }} zen</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- Registered Guilds --}}
@if($registeredGuilds->count())
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Registered Guilds</h3></div>
    <div class="panel-body" style="padding:0;">
        <table class="table table-condensed table-striped" style="margin:0;">
            <thead>
                <tr><th>Guild</th><th>Master</th><th>Score</th></tr>
            </thead>
            <tbody>
                @foreach($registeredGuilds as $reg)
                <tr>
                    <td><a href="{{ route('profile.guild', $reg->guild->G_Name) }}">{{ $reg->guild->G_Name }}</a></td>
                    <td><a href="{{ route('profile.player', $reg->guild->G_Master) }}">{{ $reg->guild->G_Master }}</a></td>
                    <td>{{ number_format($reg->guild->G_Score) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endif
@endsection
