@extends('layouts.admin')
@section('title', 'Accounts by IP')

@section('content')
<h1 class="page-header">Accounts from IP: <code>{{ $ip }}</code></h1>

@if($accounts->isEmpty())
    <div class="alert alert-info">No accounts found for this IP.</div>
@else
<table class="table table-striped table-condensed">
    <thead><tr><th>Username</th><th>Email</th><th>Status</th><th></th></tr></thead>
    <tbody>
        @foreach($accounts as $acc)
        <tr>
            <td>{{ $acc->memb___id }}</td>
            <td>{{ $acc->mail_addr }}</td>
            <td>{{ $acc->bloc_code == 1 ? 'Banned' : 'Active' }}</td>
            <td><a href="{{ route('admin.accounts.show', $acc->memb_guid) }}" class="btn btn-xs btn-primary">View</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
