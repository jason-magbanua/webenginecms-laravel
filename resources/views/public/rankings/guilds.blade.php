@extends('layouts.app')
@section('title', 'Guild Rankings')
@section('content')
<div class="page-header"><h2>Rankings</h2></div>
@include('public.rankings._nav')

@if($data->isEmpty())
    <div class="alert alert-info">No ranking data available yet.</div>
@else
<table class="table table-striped table-condensed">
    <thead>
        <tr><th>#</th><th>Guild</th><th>Master</th><th>Score</th></tr>
    </thead>
    <tbody>
        @foreach($data as $i => $guild)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td><a href="{{ route('profile.guild', $guild->G_Name) }}">{{ $guild->G_Name }}</a></td>
            <td>
                <a href="{{ route('profile.player', $guild->G_Master) }}">{{ $guild->G_Master }}</a>
                @if(in_array($guild->G_Master, $online)) <span class="label label-success">Online</span> @endif
            </td>
            <td>{{ number_format($guild->G_Score) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
