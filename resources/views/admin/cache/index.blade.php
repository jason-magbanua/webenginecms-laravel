@extends('layouts.admin')
@section('title', 'Cache Manager')

@section('content')
<h1 class="page-header">Cache Manager
    <form action="{{ route('admin.cache.clear-all') }}" method="POST" style="display:inline;" onsubmit="return confirm('Clear all cache?')">
        @csrf @method('DELETE')
        <button class="btn btn-danger btn-sm pull-right">Clear All</button>
    </form>
</h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>Cache Key</th><th>Status</th><th></th></tr></thead>
    <tbody>
        @foreach($keys as $entry)
        <tr>
            <td><code>{{ $entry['key'] }}</code></td>
            <td>
                @if($entry['exists'])
                    <span class="label label-success">Cached</span>
                @else
                    <span class="label label-default">Empty</span>
                @endif
            </td>
            <td>
                @if($entry['exists'])
                <form action="{{ route('admin.cache.clear', $entry['key']) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-warning">Clear</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
