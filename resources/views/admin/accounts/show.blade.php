@extends('layouts.admin')
@section('title', 'Account: ' . $account->memb___id)

@section('content')
<h1 class="page-header">Account: {{ $account->memb___id }}</h1>

<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Account Info</div>
            <table class="table" style="margin:0;">
                <tr><td><strong>ID</strong></td><td>{{ $account->memb_guid }}</td></tr>
                <tr><td><strong>Username</strong></td><td>{{ $account->memb___id }}</td></tr>
                <tr><td><strong>Email</strong></td><td>{{ $account->mail_addr }}</td></tr>
                <tr><td><strong>Status</strong></td><td>{{ $account->bloc_code == 1 ? 'Banned' : 'Active' }}</td></tr>
                <tr><td><strong>Account Level</strong></td><td>{{ $account->AccountLevel }}</td></tr>
                <tr><td><strong>IP</strong></td><td><a href="{{ route('admin.accounts.by-ip', $account->IP ?? '0.0.0.0') }}">{{ $account->IP }}</a></td></tr>
            </table>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Edit Account</div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.accounts.show', $account->memb_guid) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Action</label>
                        <select name="action" class="form-control">
                            <option value="changepassword">Change Password</option>
                            <option value="changeemail">Change Email</option>
                            <option value="changebloc">Set Bloc Code (0=active, 1=banned)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <input type="text" name="value" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Apply</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">Characters ({{ $characters->count() }})</div>
            <table class="table table-condensed" style="margin:0;">
                <tr><th>Name</th><th>Class</th><th>Level</th><th>Resets</th><th>Status</th></tr>
                @forelse($characters as $char)
                <tr>
                    <td>{{ $char->Name }}</td>
                    <td>{{ \App\Support\MuHelper::className($char->Class) }}</td>
                    <td>{{ $char->cLevel }}</td>
                    <td>{{ $char->RESETS }}</td>
                    <td>{{ in_array($char->Name, $onlineChars) ? '<span class="label label-success">Online</span>' : '<span class="label label-default">Offline</span>' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted text-center">No characters.</td></tr>
                @endforelse
            </table>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Bans</div>
            <table class="table table-condensed" style="margin:0;">
                <tr><th>Type</th><th>Reason</th><th>By</th><th>Expires</th></tr>
                @forelse($bans as $ban)
                <tr>
                    <td>{{ $ban->ban_type }}</td>
                    <td>{{ $ban->ban_reason }}</td>
                    <td>{{ $ban->ban_by }}</td>
                    <td>{{ $ban->ban_expire ? $ban->ban_expire->format('Y-m-d') : 'Permanent' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-muted text-center">No bans.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
</div>
@endsection
