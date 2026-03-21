@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h1 class="page-header">Dashboard</h1>

<div class="row">
    <div class="col-sm-2">
        <div class="panel panel-primary text-center">
            <div class="panel-body"><h3>{{ number_format($stats['total_accounts']) }}</h3><p>Accounts</p></div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="panel panel-info text-center">
            <div class="panel-body"><h3>{{ number_format($stats['total_characters']) }}</h3><p>Characters</p></div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="panel panel-success text-center">
            <div class="panel-body"><h3>{{ number_format($stats['online_characters']) }}</h3><p>Online</p></div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="panel panel-warning text-center">
            <div class="panel-body"><h3>{{ number_format($stats['total_news']) }}</h3><p>News</p></div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="panel panel-danger text-center">
            <div class="panel-body"><h3>{{ number_format($stats['active_bans']) }}</h3><p>Active Bans</p></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">System Info</div>
            <div class="list-group" style="margin:0;">
                <div class="list-group-item">OS <span class="pull-right text-muted">{{ $os }}</span></div>
                <div class="list-group-item">PHP <span class="pull-right text-muted">{{ $php_version }}</span></div>
                <div class="list-group-item">Laravel <span class="pull-right text-muted">{{ $laravel_version }}</span></div>
                <div class="list-group-item">Server <span class="pull-right text-muted">{{ config('webengine.server_name') }}</span></div>
                <div class="list-group-item">Season <span class="pull-right text-muted">{{ config('webengine.server_season') }}</span></div>
                <div class="list-group-item">Files <span class="pull-right text-muted">{{ config('webengine.server_files') }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
