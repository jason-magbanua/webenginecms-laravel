@extends('layouts.admin')
@section('title', 'Accounts')

@section('content')
<h1 class="page-header">Accounts</h1>

<form class="form-inline" method="GET" style="margin-bottom:15px;">
    <input type="text" class="form-control" name="q" value="{{ $search }}" placeholder="Search username...">
    <button class="btn btn-default">Search</button>
</form>

<table class="table table-striped table-condensed table-hover">
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Status</th><th>Level</th><th></th></tr></thead>
    <tbody>
        @forelse($accounts as $acc)
        <tr>
            <td>{{ $acc->memb_guid }}</td>
            <td>{{ $acc->memb___id }}</td>
            <td>{{ $acc->mail_addr }}</td>
            <td>
                @if($acc->bloc_code == 1)
                    <span class="label label-danger">Banned</span>
                @else
                    <span class="label label-success">Active</span>
                @endif
            </td>
            <td>{{ $acc->AccountLevel }}</td>
            <td><a href="{{ route('admin.accounts.show', $acc->memb_guid) }}" class="btn btn-xs btn-primary">View</a></td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">No accounts found.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $accounts->links() }}
@endsection
