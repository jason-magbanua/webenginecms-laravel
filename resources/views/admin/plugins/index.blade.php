@extends('layouts.admin')
@section('title', 'Plugins')

@section('content')
<h1 class="page-header">Plugin Manager</h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>Plugin</th><th>Slug</th><th>Version</th><th>Status</th><th>Installed</th><th></th></tr></thead>
    <tbody>
        @forelse($plugins as $plugin)
        <tr>
            <td>{{ $plugin->plugin_name }}</td>
            <td><code>{{ $plugin->plugin_slug }}</code></td>
            <td>{{ $plugin->plugin_version }}</td>
            <td>
                @if($plugin->plugin_status)
                    <span class="label label-success">Enabled</span>
                @else
                    <span class="label label-default">Disabled</span>
                @endif
            </td>
            <td>{{ $plugin->plugin_installed_at?->format('Y-m-d') ?? '—' }}</td>
            <td>
                <form action="{{ route('admin.plugins.toggle', $plugin->plugin_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-xs {{ $plugin->plugin_status ? 'btn-warning' : 'btn-success' }}">
                        {{ $plugin->plugin_status ? 'Disable' : 'Enable' }}
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-muted text-center">No plugins installed.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
