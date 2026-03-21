@extends('layouts.app')
@section('title', 'Change Email')

@section('content')
<div class="page-header"><h2>Change Email</h2></div>

<div class="row">
    <div class="col-md-6 col-md-offset-2">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="panel panel-default">
            <div class="panel-body">
                <p class="text-muted">Current email: <strong>{{ Auth::user()->mail_addr }}</strong></p>
                <form class="form-horizontal" action="{{ route('usercp.myemail') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group{{ $errors->has('new_email') ? ' has-error' : '' }}">
                        <label class="col-sm-4 control-label">New Email Address</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" name="new_email" required>
                            @error('new_email')<span class="help-block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-4 col-sm-8">
                            <button type="submit" class="btn btn-primary">Update Email</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
