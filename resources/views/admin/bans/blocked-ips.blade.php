@extends('layouts.admin')
@section('title', 'Blocked IPs')

@section('content')
<h1 class="page-header">Blocked IPs</h1>

<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">Block New IP</div>
            <div class="panel-body">
                <form method="POST" action="{{ route('admin.bans.blocked-ips.store') }}">
                    @csrf
                    <div class="form-group{{ $errors->has('ip_address') ? ' has-error' : '' }}">
                        <label>IP Address</label>
                        <input type="text" class="form-control" name="ip_address" placeholder="x.x.x.x" required>
                        @error('ip_address')<span class="help-block">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <input type="text" class="form-control" name="ip_reason" placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-danger">Block IP</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <table class="table table-striped table-condensed">
            <thead><tr><th>IP</th><th>Reason</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($ips as $ip)
                <tr>
                    <td><code>{{ $ip->ip_address }}</code></td>
                    <td>{{ $ip->ip_reason ?: '—' }}</td>
                    <td>{{ $ip->ip_date }}</td>
                    <td>
                        <form action="{{ route('admin.bans.blocked-ips.destroy', $ip->ip_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Unblock this IP?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-warning">Unblock</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-muted text-center">No blocked IPs.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $ips->links() }}
    </div>
</div>
@endsection
