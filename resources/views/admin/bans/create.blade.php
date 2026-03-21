@extends('layouts.admin')
@section('title', 'Ban Account')

@section('content')
<h1 class="page-header">Ban Account</h1>

<div class="col-sm-6" style="padding-left:0;">
    <div class="panel panel-danger">
        <div class="panel-body">
            <form method="POST" action="{{ route('admin.bans.store') }}">
                @csrf
                <div class="form-group{{ $errors->has('ban_account') ? ' has-error' : '' }}">
                    <label>Account Username</label>
                    <input type="text" class="form-control" name="ban_account" value="{{ old('ban_account') }}" required>
                    @error('ban_account')<span class="help-block">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" class="form-control" name="ban_reason" value="{{ old('ban_reason') }}" placeholder="Optional">
                </div>
                <div class="form-group">
                    <label>Days (0 = permanent)</label>
                    <input type="number" class="form-control" name="ban_days" value="{{ old('ban_days', 0) }}" min="0" required>
                </div>
                <button type="submit" class="btn btn-danger">Ban Account</button>
                <a href="{{ route('admin.bans.index') }}" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
