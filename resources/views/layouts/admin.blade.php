<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin &mdash; @yield('title', 'Panel') &mdash; {{ config('webengine.server_name') }}</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { padding-top: 0; background: #f5f5f5; }
        .admin-sidebar { min-height: 100vh; background: #2c3e50; padding-top: 20px; }
        .admin-sidebar a { color: #bdc3c7; display: block; padding: 8px 20px; text-decoration: none; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background: #3d5166; color: #fff; }
        .admin-sidebar .nav-header { color: #7f8c8d; font-size: 11px; text-transform: uppercase; padding: 15px 20px 5px; }
        .admin-content { padding: 20px; }
        .admin-topbar { background: #2c3e50; color: #fff; padding: 10px 20px; }
    </style>
</head>
<body>
<div class="row" style="margin:0;">
    <div class="col-sm-2 admin-sidebar" style="padding:0;">
        <div style="padding:15px 20px; border-bottom:1px solid #3d5166; margin-bottom:10px;">
            <strong style="color:#fff;">Admin Panel</strong><br>
            <small style="color:#7f8c8d;">{{ config('webengine.server_name') }}</small>
        </div>
        <span class="nav-header">Main</span>
        <a href="{{ route('admin.index') }}">Dashboard</a>
        <span class="nav-header">Content</span>
        <a href="{{ route('admin.news.index') }}">News</a>
        <span class="nav-header">Players</span>
        <a href="{{ route('admin.accounts.index') }}">Accounts</a>
        <a href="{{ route('admin.accounts.online') }}">Online</a>
        <a href="{{ route('admin.accounts.registrations') }}">Registrations</a>
        <a href="{{ route('admin.characters.index') }}">Characters</a>
        <span class="nav-header">Moderation</span>
        <a href="{{ route('admin.bans.index') }}">Bans</a>
        <a href="{{ route('admin.bans.blocked-ips') }}">Blocked IPs</a>
        <span class="nav-header">Economy</span>
        <a href="{{ route('admin.credits.index') }}">Credits</a>
        <a href="{{ route('admin.credits.paypal') }}">PayPal Logs</a>
        <a href="{{ route('admin.credits.top-voters') }}">Top Voters</a>
        <span class="nav-header">System</span>
        <a href="{{ route('admin.cache.index') }}">Cache</a>
        <a href="{{ route('admin.cron.index') }}">Cron Jobs</a>
        <a href="{{ route('admin.plugins.index') }}">Plugins</a>
        <a href="{{ route('admin.settings.index') }}">Settings</a>
        <span class="nav-header">Site</span>
        <a href="{{ route('home') }}" target="_blank">View Site</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background:none;border:none;color:#bdc3c7;padding:8px 20px;width:100%;text-align:left;cursor:pointer;">Logout</button>
        </form>
    </div>
    <div class="col-sm-10" style="padding:0;">
        <div class="admin-topbar">
            <span>Logged in as <strong>{{ Auth::user()->memb___id }}</strong></span>
        </div>
        <div class="admin-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
