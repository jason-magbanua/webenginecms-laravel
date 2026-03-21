<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title', config('webengine.server_name')) — {{ config('webengine.server_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet">
    @stack('styles')
</head>
<body>

{{-- Top Bar --}}
<div class="global-top-bar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 text-left">
                <span class="text-muted small">{{ config('webengine.server_name') }}</span>
            </div>
            <div class="col-xs-6 text-right">
                @auth
                    <a href="{{ route('usercp.dashboard') }}">User Panel</a>
                    <span class="text-muted">|</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-link btn-xs" style="padding:0">Logout</button>
                    </form>
                @else
                    <a href="{{ route('register') }}">Register</a>
                    <span class="text-muted">|</span>
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- Navbar --}}
<nav class="navbar navbar-default navbar-static-top" style="margin-bottom:0;">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav">
                <span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('webengine.server_name') }}</a>
        </div>
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">Home</a>
                </li>
                <li class="{{ request()->routeIs('news.*') ? 'active' : '' }}">
                    <a href="{{ route('news.index') }}">News</a>
                </li>
                <li class="dropdown {{ request()->routeIs('rankings.*') ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Rankings <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('rankings.level') }}">Level</a></li>
                        <li><a href="{{ route('rankings.resets') }}">Resets</a></li>
                        <li><a href="{{ route('rankings.grandresets') }}">Grand Resets</a></li>
                        <li><a href="{{ route('rankings.master') }}">Master Level</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ route('rankings.guilds') }}">Guilds</a></li>
                        <li><a href="{{ route('rankings.killers') }}">PK Killers</a></li>
                        <li><a href="{{ route('rankings.votes') }}">Votes</a></li>
                        <li><a href="{{ route('rankings.gens') }}">Gens</a></li>
                        <li><a href="{{ route('rankings.online') }}">Online</a></li>
                    </ul>
                </li>
                <li class="{{ request()->routeIs('castle-siege') ? 'active' : '' }}">
                    <a href="{{ route('castle-siege') }}">Castle Siege</a>
                </li>
                <li class="{{ request()->routeIs('donation') ? 'active' : '' }}">
                    <a href="{{ route('donation') }}">Donate</a>
                </li>
                <li class="{{ request()->routeIs('downloads') ? 'active' : '' }}">
                    <a href="{{ route('downloads') }}">Downloads</a>
                </li>
                <li class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
                    <a href="{{ route('contact.index') }}">Contact</a>
                </li>
            </ul>
            @auth
            <ul class="nav navbar-nav navbar-right">
                <li class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    @if(in_array(auth()->user()->memb___id, config('webengine.admin_usernames', [])))
                    <a href="{{ route('admin.index') }}">Admin Panel</a>
                    @endif
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

{{-- Main Content --}}
<div class="container-fluid" style="margin-top: 20px;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

{{-- Footer --}}
<footer class="footer" style="margin-top:40px; padding:20px 0; border-top:1px solid #ddd; text-align:center;">
    <p class="text-muted small">
        &copy; {{ date('Y') }} {{ config('webengine.server_name') }} &mdash;
        Powered by <a href="https://webenginecms.org" target="_blank">WebEngine CMS</a>
    </p>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
@stack('scripts')
</body>
</html>
