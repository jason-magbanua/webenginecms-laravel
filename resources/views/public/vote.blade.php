@extends('layouts.app')
@section('title', 'Vote')

@section('content')
<div class="page-header"><h2>Vote for Us</h2></div>

<p class="text-muted">Support our server by voting on these sites. Login to claim your reward after voting.</p>

@if($sites->isEmpty())
    <div class="alert alert-info">No vote sites configured.</div>
@else
<div class="row">
    @foreach($sites as $site)
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">{{ $site->site_name }}</h3></div>
            <div class="panel-body">
                <p>Reward: <strong>{{ $site->site_credits }} credits</strong></p>
                <a href="{{ $site->site_url }}" class="btn btn-primary btn-block" target="_blank">Vote Now</a>
                @auth
                <a href="{{ route('usercp.vote') }}" class="btn btn-success btn-block" style="margin-top:5px;">Claim Reward</a>
                @endauth
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
