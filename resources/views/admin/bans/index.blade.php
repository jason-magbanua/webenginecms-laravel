@extends('layouts.admin')
@section('title', 'Bans')

@section('content')
<h1 class="page-header">Bans <a href="{{ route('admin.bans.create') }}" class="btn btn-danger btn-sm pull-right">+ Ban Account</a></h1>

<form class="form-inline" method="GET" style="margin-bottom:15px;">
    <input type="text" class="form-control" name="q" value="{{ $search }}" placeholder="Search account...">
    <button class="btn btn-default">Search</button>
</form>

<table class="table table-striped table-condensed">
    <thead><tr><th>Account</th><th>Reason</th><th>Type</th><th>By</th><th>Date</th><th>Expires</th><th></th></tr></thead>
    <tbody>
        @forelse($bans as $ban)
        <tr>
            <td><a href="{{ route('admin.accounts.index', ['q' => $ban->ban_account]) }}">{{ $ban->ban_account }}</a></td>
            <td>{{ $ban->ban_reason ?: '—' }}</td>
            <td>{{ ucfirst($ban->ban_type) }}</td>
            <td>{{ $ban->ban_by }}</td>
            <td>{{ $ban->ban_date?->format('Y-m-d H:i') }}</td>
            <td>{{ $ban->ban_expire ? $ban->ban_expire->format('Y-m-d') : 'Permanent' }}</td>
            <td>
                <form action="{{ route('admin.bans.destroy', $ban->ban_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Lift this ban?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-success">Lift</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">No bans found.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $bans->links() }}
@endsection
