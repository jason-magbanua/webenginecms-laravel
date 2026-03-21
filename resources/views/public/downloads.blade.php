@extends('layouts.app')
@section('title', 'Downloads')

@section('content')
<div class="page-header"><h2>Downloads</h2></div>

@if($clients->count())
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Game Clients</h3></div>
    <div class="panel-body" style="padding:0;">
        <table class="table" style="margin:0;">
            @foreach($clients as $d)
            <tr>
                <td width="60%">
                    <strong>{{ $d->download_name }}</strong><br>
                    <span class="text-muted small">{{ $d->download_description }}</span>
                </td>
                <td width="20%" class="text-center text-muted">{{ round($d->download_size ?? 0, 2) }} MB</td>
                <td width="20%" class="text-center">
                    <a href="{{ $d->download_url }}" class="btn btn-primary btn-xs" target="_blank">Download</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endif

@if($patches->count())
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Patches</h3></div>
    <div class="panel-body" style="padding:0;">
        <table class="table" style="margin:0;">
            @foreach($patches as $d)
            <tr>
                <td width="60%">
                    <strong>{{ $d->download_name }}</strong><br>
                    <span class="text-muted small">{{ $d->download_description }}</span>
                </td>
                <td width="20%" class="text-center text-muted">{{ round($d->download_size ?? 0, 2) }} MB</td>
                <td width="20%" class="text-center">
                    <a href="{{ $d->download_url }}" class="btn btn-primary btn-xs" target="_blank">Download</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endif

@if($tools->count())
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Tools</h3></div>
    <div class="panel-body" style="padding:0;">
        <table class="table" style="margin:0;">
            @foreach($tools as $d)
            <tr>
                <td width="60%">
                    <strong>{{ $d->download_name }}</strong><br>
                    <span class="text-muted small">{{ $d->download_description }}</span>
                </td>
                <td width="20%" class="text-center text-muted">{{ round($d->download_size ?? 0, 2) }} MB</td>
                <td width="20%" class="text-center">
                    <a href="{{ $d->download_url }}" class="btn btn-primary btn-xs" target="_blank">Download</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endif

@if($clients->isEmpty() && $patches->isEmpty() && $tools->isEmpty())
    <div class="alert alert-info">No downloads available yet.</div>
@endif
@endsection
