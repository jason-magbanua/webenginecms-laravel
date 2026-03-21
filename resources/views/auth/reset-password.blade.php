@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div class="page-header"><h2>Reset Password</h2></div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-horizontal" action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    @error('token')<div class="alert alert-danger">{{ $message }}</div>@enderror
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">New Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password" required>
                            @error('password')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
