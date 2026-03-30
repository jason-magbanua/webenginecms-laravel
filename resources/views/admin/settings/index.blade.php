@extends('layouts.admin')
@section('title', 'Settings')

@section('content')
<h1 class="page-header">Settings</h1>
<div class="alert alert-info">Settings are stored in the database and take effect immediately on the next request.</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    <div class="panel panel-default">
        <div class="panel-heading">Server</div>
        <div class="panel-body">
            <div class="form-group">
                <label>Server Name</label>
                <input type="text" class="form-control" name="server_name" value="{{ config('webengine.server_name') }}">
            </div>
            <div class="form-group">
                <label>Season</label>
                <input type="number" class="form-control" name="server_season" value="{{ config('webengine.server_season') }}" min="1">
            </div>
            <div class="form-group">
                <label>Server Files</label>
                <select class="form-control" name="server_files">
                    @foreach(['igcn', 'xteam', 'custom'] as $f)
                    <option value="{{ $f }}" {{ config('webengine.server_files') === $f ? 'selected' : '' }}>{{ strtoupper($f) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Features</div>
        <div class="panel-body">
            @foreach(config('webengine.features') as $key => $val)
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="features.{{ $key }}" value="1" {{ $val ? 'checked' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $key)) }}
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Brute Force Protection</div>
        <div class="panel-body">
            <div class="form-group">
                <label>Max Attempts</label>
                <input type="number" class="form-control" name="brute_force.max_attempts" value="{{ config('webengine.brute_force.max_attempts') }}" min="1">
            </div>
            <div class="form-group">
                <label>Lockout Minutes</label>
                <input type="number" class="form-control" name="brute_force.lockout_minutes" value="{{ config('webengine.brute_force.lockout_minutes') }}" min="1">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>
@endsection
