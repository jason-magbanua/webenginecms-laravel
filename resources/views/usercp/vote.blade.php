@extends('layouts.app')
@section('title', 'Claim Vote Reward')

@section('content')
<div class="page-header"><h2>Claim Vote Reward</h2></div>

@if($sites->isEmpty())
    <div class="alert alert-info">No vote sites configured.</div>
@else
<div class="panel panel-default">
    <div class="panel-heading"><h3 class="panel-title">Select a Site to Claim</h3></div>
    <div class="panel-body">
        <p class="text-muted">You must have already voted on the site before claiming your reward.</p>
        <form action="{{ route('usercp.vote.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Vote Site</label>
                <select name="site_id" class="form-control">
                    @foreach($sites as $site)
                    <option value="{{ $site->site_id }}">{{ $site->site_name }} ({{ $site->site_credits }} credits)</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Claim Reward</button>
        </form>
    </div>
</div>
@endif
@endsection
