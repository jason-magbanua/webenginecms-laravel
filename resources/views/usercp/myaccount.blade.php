@extends('layouts.app')
@section('title', 'My Account')

@section('content')
<div class="page-header"><h2>My Account</h2></div>
<p class="text-muted">Manage your account settings below.</p>
<div class="list-group">
    <a href="{{ route('usercp.dashboard') }}" class="list-group-item">
        <strong>Account Overview</strong>
        <span class="text-muted pull-right">View status, characters &amp; credits</span>
    </a>
    <a href="{{ route('usercp.mypassword') }}" class="list-group-item">
        <strong>Change Password</strong>
        <span class="text-muted pull-right">Update your login password</span>
    </a>
    <a href="{{ route('usercp.myemail') }}" class="list-group-item">
        <strong>Change Email</strong>
        <span class="text-muted pull-right">Update your email address</span>
    </a>
</div>
@endsection
