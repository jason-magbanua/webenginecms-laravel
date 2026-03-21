@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="page-header"><h2>Create Account</h2></div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-horizontal" action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Username</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                            <span class="help-block">4–10 alphanumeric characters.</span>
                            @error('username')<span class="help-block text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password" required>
                            <span class="help-block">4–10 characters.</span>
                            @error('password')<span class="help-block text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Email</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            @error('email')<span class="help-block text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-success">Create Account</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <p class="text-center">Already have an account? <a href="{{ route('login') }}">Login here</a>.</p>
    </div>
</div>
@endsection
