@extends('layouts.admin')
@section('title', 'New Registrations')

@section('content')
<h1 class="page-header">Registrations</h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Registered</th><th>IP</th><th></th></tr></thead>
    <tbody>
        @forelse($accounts as $acc)
        <tr>
            <td>{{ $acc->memb_guid }}</td>
            <td>{{ $acc->memb___id }}</td>
            <td>{{ $acc->mail_addr }}</td>
            <td>{{ $acc->regist_datetime }}</td>
            <td>{{ $acc->IP }}</td>
            <td><a href="{{ route('admin.accounts.show', $acc->memb_guid) }}" class="btn btn-xs btn-primary">View</a></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">No accounts found.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $accounts->links() }}
@endsection
