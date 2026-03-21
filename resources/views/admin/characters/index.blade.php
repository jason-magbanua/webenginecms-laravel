@extends('layouts.admin')
@section('title', 'Characters')

@section('content')
<h1 class="page-header">Characters</h1>

<form class="form-inline" method="GET" style="margin-bottom:15px;">
    <input type="text" class="form-control" name="q" value="{{ $search }}" placeholder="Name or account...">
    <button class="btn btn-default">Search</button>
</form>

<table class="table table-striped table-condensed table-hover">
    <thead><tr><th>Name</th><th>Account</th><th>Class</th><th>Level</th><th>Resets</th><th>Grand Resets</th><th></th></tr></thead>
    <tbody>
        @forelse($characters as $char)
        <tr>
            <td>{{ $char->Name }}</td>
            <td><a href="{{ route('admin.accounts.index', ['q' => $char->AccountID]) }}">{{ $char->AccountID }}</a></td>
            <td>{{ \App\Support\MuHelper::className($char->Class) }}</td>
            <td>{{ $char->cLevel }}</td>
            <td>{{ $char->RESETS }}</td>
            <td>{{ $char->GrandResets }}</td>
            <td><a href="{{ route('admin.characters.edit', $char->Name) }}" class="btn btn-xs btn-primary">Edit</a></td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">No characters found.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $characters->links() }}
@endsection
