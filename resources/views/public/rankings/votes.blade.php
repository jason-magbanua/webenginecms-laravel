@extends('layouts.app')
@section('title', 'Vote Rankings')
@section('content')
<div class="page-header"><h2>Rankings</h2></div>
@include('public.rankings._nav')

@if($data->isEmpty())
    <div class="alert alert-info">No ranking data available yet.</div>
@else
<table class="table table-striped table-condensed">
    <thead>
        <tr><th>#</th><th>Class</th><th>Character</th><th>Votes</th></tr>
    </thead>
    <tbody>
        @foreach($data as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->character ? \App\Support\MuHelper::className($row->character->Class) : '—' }}</td>
            <td>
                @if($row->character)
                    <a href="{{ route('profile.player', $row->character->Name) }}">{{ $row->character->Name }}</a>
                    @if(in_array($row->character->Name, $online)) <span class="label label-success">Online</span> @endif
                @else
                    {{ $row->vote_account }}
                @endif
            </td>
            <td>{{ number_format($row->vote_count) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
