@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="page-header"><h2>Change Password</h2></div>

<div class="row">
    <div class="col-md-6 col-md-offset-2">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="panel panel-default">
            <div class="panel-body">
                <form class="form-horizontal" action="{{ route('usercp.mypassword') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">Current Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="current_password" required>
                            @error('current_password')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('new_password') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">New Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="new_password" required>
                            @error('new_password')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Confirm New Password</label>
                        <div class="col-sm-8">
                            <input type="password" class="form-control" name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
