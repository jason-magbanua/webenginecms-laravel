@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="row">

    {{-- News Block --}}
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Latest News
                    <a href="{{ route('news.index') }}" class="btn btn-primary btn-xs pull-right">View All</a>
                </h3>
            </div>
            <div class="panel-body" style="padding:0;">
                @forelse($news as $article)
                <div class="list-group-item" style="border-left:none;border-right:none;{{ $loop->first ? 'border-top:none;' : '' }}">
                    <div class="row">
                        <div class="col-xs-8">
                            <a href="{{ route('news.show', $article->news_id) }}">
                                <strong>{{ $article->news_title }}</strong>
                            </a>
                        </div>
                        <div class="col-xs-4 text-right text-muted small">
                            {{ $article->news_date ? $article->news_date->format('Y/m/d') : '' }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="panel-body text-muted">No news available.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="col-md-4">
        @guest
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Login
                    <a href="{{ route('password.request') }}" class="btn btn-default btn-xs pull-right">Forgot?</a>
                </h3>
            </div>
            <div class="panel-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="memb___id" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <hr style="margin:10px 0;">
                <a href="{{ route('register') }}" class="btn btn-success btn-block">Create Account</a>
            </div>
        </div>
        @else
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Welcome, {{ auth()->user()->memb___id }}
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-default btn-xs pull-right">Logout</button>
                    </form>
                </h3>
            </div>
            <div class="panel-body">
                <a href="{{ route('usercp.index') }}" class="btn btn-primary btn-block">User Panel</a>
            </div>
        </div>
        @endguest
    </div>

</div>

<div class="row" style="margin-top:20px;">

    {{-- Top Level --}}
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Top Level
                    <a href="{{ route('rankings.level') }}" class="btn btn-default btn-xs pull-right">+</a>
                </h3>
            </div>
            <div class="panel-body" style="padding:0;">
                @if($topLevel->count())
                <table class="table table-condensed table-striped" style="margin:0;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Character</th>
                            <th>Class</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topLevel as $i => $char)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><a href="{{ route('profile.player', $char->Name) }}">{{ $char->Name }}</a></td>
                            <td>{{ \App\Support\MuHelper::className($char->Class) }}</td>
                            <td>{{ number_format($char->cLevel) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted" style="padding:10px;">No data available.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Top Guilds --}}
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Top Guilds
                    <a href="{{ route('rankings.guilds') }}" class="btn btn-default btn-xs pull-right">+</a>
                </h3>
            </div>
            <div class="panel-body" style="padding:0;">
                @if($topGuilds->count())
                <table class="table table-condensed table-striped" style="margin:0;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guild</th>
                            <th>Master</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topGuilds as $i => $guild)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><a href="{{ route('profile.guild', $guild->G_Name) }}">{{ $guild->G_Name }}</a></td>
                            <td><a href="{{ route('profile.player', $guild->G_Master) }}">{{ $guild->G_Master }}</a></td>
                            <td>{{ number_format($guild->G_Score) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted" style="padding:10px;">No data available.</p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
