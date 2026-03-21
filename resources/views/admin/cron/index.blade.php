@extends('layouts.admin')
@section('title', 'Cron Jobs')

@section('content')
<h1 class="page-header">Cron Jobs</h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>Name</th><th>Status</th><th>Last Run</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($jobs as $job)
        <tr>
            <td>
                <strong>{{ $job->cron_name }}</strong>
                @if(isset($job->cron_description))
                    <br><small class="text-muted">{{ $job->cron_description }}</small>
                @endif
            </td>
            <td>
                @if($job->cron_status)
                    <span class="label label-success">Enabled</span>
                @else
                    <span class="label label-default">Disabled</span>
                @endif
            </td>
            <td>{{ $job->cron_last_run?->format('Y-m-d H:i') ?? '—' }}</td>
            <td>
                <form action="{{ route('admin.cron.toggle', $job->cron_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-xs {{ $job->cron_status ? 'btn-warning' : 'btn-success' }}">
                        {{ $job->cron_status ? 'Disable' : 'Enable' }}
                    </button>
                </form>
                <form action="{{ route('admin.cron.run', $job->cron_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Run this cron job now?')">
                    @csrf
                    <button class="btn btn-xs btn-primary">Run Now</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-muted text-center">No cron jobs found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
