@extends('layouts.app')
@section('title', 'Online Players')
@section('content')
<div class="page-header"><h2>Rankings</h2></div>
@include('public.rankings._nav')

@if($online->isEmpty())
    <div class="alert alert-info">No players currently online.</div>
@else
<table class="table table-striped table-condensed">
    <thead>
        <tr><th>#</th><th>Class</th><th>Character</th><th>Level</th><th>Server</th></tr>
    </thead>
    <tbody>
        @foreach($online as $i => $status)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ \App\Support\MuHelper::className($status->character->Class) }}</td>
            <td><a href="{{ route('profile.player', $status->character->Name) }}">{{ $status->character->Name }}</a></td>
            <td>{{ number_format($status->character->cLevel) }}</td>
            <td>{{ $status->ServerName }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<p class="text-muted small">{{ $online->count() }} player(s) online</p>
@endif
@endsection
