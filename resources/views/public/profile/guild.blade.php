@extends('layouts.app')
@section('title', $guild->G_Name . ' — Guild Profile')

@section('content')
<div class="page-header"><h2>Guild Profile</h2></div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ $guild->G_Name }}</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6">
                        <table class="table table-condensed">
                            <tr><td><strong>Master</strong></td><td><a href="{{ route('profile.player', $guild->G_Master) }}">{{ $guild->G_Master }}</a></td></tr>
                            <tr><td><strong>Score</strong></td><td>{{ number_format($guild->G_Score) }}</td></tr>
                            <tr><td><strong>Members</strong></td><td>{{ $members->count() }}</td></tr>
                            @if($guild->G_Notice)
                            <tr><td><strong>Notice</strong></td><td>{{ $guild->G_Notice }}</td></tr>
                            @endif
                        </table>
                    </div>
                </div>
                <hr>
                <h4>Members</h4>
                <div class="row">
                    @foreach($members as $member)
                    <div class="col-xs-3" style="margin-bottom:5px;">
                        <a href="{{ route('profile.player', $member->Name) }}">{{ $member->Name }}</a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
