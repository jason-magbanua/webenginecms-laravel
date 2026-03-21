@extends('layouts.app')
@section('title', 'PK Killers Rankings')
@section('content')
<div class="page-header"><h2>Rankings</h2></div>
@include('public.rankings._nav')

@if($data->isEmpty())
    <div class="alert alert-info">No ranking data available yet.</div>
@else
<table class="table table-striped table-condensed">
    <thead>
        <tr><th>#</th><th>Class</th><th>Character</th><th>Level</th><th>PK Level</th><th>Kills</th></tr>
    </thead>
    <tbody>
        @foreach($data as $i => $char)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ \App\Support\MuHelper::className($char->Class) }}</td>
            <td>
                <a href="{{ route('profile.player', $char->Name) }}">{{ $char->Name }}</a>
                @if(in_array($char->Name, $online)) <span class="label label-success">Online</span> @endif
            </td>
            <td>{{ number_format($char->cLevel) }}</td>
            <td>{{ \App\Support\MuHelper::pkLevel($char->PkLevel) }}</td>
            <td>{{ number_format($char->PkCount) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
