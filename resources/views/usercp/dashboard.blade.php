@extends('layouts.app')
@section('title', 'My Account')

@section('content')
<div class="page-header"><h2>My Account</h2></div>

<table class="table">
    <tr>
        <td><strong>Status</strong></td>
        <td>
            @if($account->bloc_code == 1)
                <span class="label label-danger">Banned</span>
            @else
                <span class="label label-default">Active</span>
            @endif
        </td>
    </tr>
    <tr>
        <td><strong>Username</strong></td>
        <td>{{ $account->memb___id }}</td>
    </tr>
    <tr>
        <td><strong>Email</strong></td>
        <td>
            {{ $account->mail_addr }}
            <a href="{{ route('usercp.myemail') }}" class="btn btn-xs btn-primary pull-right">Change</a>
        </td>
    </tr>
    <tr>
        <td><strong>Password</strong></td>
        <td>
            &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
            <a href="{{ route('usercp.mypassword') }}" class="btn btn-xs btn-primary pull-right">Change</a>
        </td>
    </tr>
    <tr>
        <td><strong>Online Status</strong></td>
        <td>
            @if($onlineStatus)
                <span class="label label-success">Online</span>
            @else
                <span class="label label-danger">Offline</span>
            @endif
        </td>
    </tr>
    @foreach($creditsData as $credit)
    <tr>
        <td><strong>{{ $credit['title'] }}</strong></td>
        <td>{{ number_format($credit['amount']) }}</td>
    </tr>
    @endforeach
</table>

@if($characterList->isNotEmpty())
<div class="page-header"><h3>Characters</h3></div>
<div class="row text-center">
    @foreach($characterList as $char)
    <div class="col-xs-3">
        <p>
            <a href="{{ route('profile.player', $char->Name) }}">{{ $char->Name }}</a>
            @if(in_array($char->Name, $onlineCharacters))
                <span class="label label-success" style="font-size:9px;">Online</span>
            @else
                <span class="label label-default" style="font-size:9px;">Offline</span>
            @endif
        </p>
        <p class="text-muted small">{{ \App\Support\MuHelper::className($char->Class) }}</p>
        <p>Level {{ $char->cLevel + ($char->mLevel ?? 0) }}</p>
        <p class="text-muted small">{{ \App\Support\MuHelper::mapName($char->MapNumber) }} ({{ $char->MapPosX }}, {{ $char->MapPosY }})</p>
    </div>
    @endforeach
</div>
@else
<div class="alert alert-info">No characters found on this account.</div>
@endif
@endsection
