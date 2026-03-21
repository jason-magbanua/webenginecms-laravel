@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="page-header"><h2>Login</h2></div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form class="form-horizontal" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                            @error('username')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password" required>
                            @error('password')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if(config('webengine.features.registration'))
        <p class="text-center">Don't have an account? <a href="{{ route('register') }}">Register here</a>.</p>
        @endif
    </div>
</div>
@endsection
