@extends('layouts.admin')
@section('title', 'Top Voters')

@section('content')
<h1 class="page-header">Top Voters</h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>#</th><th>Account</th><th>Total Votes</th></tr></thead>
    <tbody>
        @forelse($voters as $i => $voter)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $voter->log_account }}</td>
            <td>{{ number_format($voter->total_votes) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="text-muted text-center">No votes recorded.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
