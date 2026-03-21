@extends('layouts.app')
@section('title', 'Forgot Password')

@section('content')
<div class="page-header"><h2>Forgot Password</h2></div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="panel panel-default">
            <div class="panel-body">
                <p class="text-muted">Enter your registered email to receive a password reset link.</p>
                <form class="form-horizontal" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Email Address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" name="email" required autofocus>
                            @error('email')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center"><a href="{{ route('login') }}">&larr; Back to Login</a></p>
    </div>
</div>
@endsection
