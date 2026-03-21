@extends('layouts.app')
@section('title', 'Grand Reset Rankings')
@section('content')
<div class="page-header"><h2>Rankings</h2></div>
@include('public.rankings._nav')

@if($data->isEmpty())
    <div class="alert alert-info">No ranking data available yet.</div>
@else
<table class="table table-striped table-condensed">
    <thead>
        <tr><th>#</th><th>Class</th><th>Character</th><th>Grand Resets</th><th>Resets</th><th>Level</th></tr>
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
            <td>{{ number_format($char->GrandResets) }}</td>
            <td>{{ number_format($char->RESETS) }}</td>
            <td>{{ number_format($char->cLevel) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
