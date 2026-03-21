@extends('layouts.admin')
@section('title', 'Online Accounts')

@section('content')
<h1 class="page-header">Online Accounts <span class="badge">{{ count($onlineNames) }}</span></h1>

@if($accounts->isEmpty())
    <div class="alert alert-info">No players online.</div>
@else
<table class="table table-striped table-condensed">
    <thead><tr><th>Username</th><th>Email</th><th></th></tr></thead>
    <tbody>
        @foreach($accounts as $acc)
        <tr>
            <td>{{ $acc->memb___id }}</td>
            <td>{{ $acc->mail_addr }}</td>
            <td><a href="{{ route('admin.accounts.show', $acc->memb_guid) }}" class="btn btn-xs btn-primary">View</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection
