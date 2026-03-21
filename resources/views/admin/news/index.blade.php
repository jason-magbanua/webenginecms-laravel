@extends('layouts.admin')
@section('title', 'Manage News')

@section('content')
<h1 class="page-header">Manage News <a href="{{ route('admin.news.create') }}" class="btn btn-success btn-sm pull-right">+ Publish</a></h1>

<table class="table table-striped table-condensed">
    <thead><tr><th>ID</th><th>Title</th><th>Author</th><th>Date</th><th>Views</th><th></th></tr></thead>
    <tbody>
        @forelse($news as $item)
        <tr>
            <td>{{ $item->news_id }}</td>
            <td>{{ $item->news_title }}</td>
            <td>{{ $item->news_author }}</td>
            <td>{{ $item->news_date?->format('Y-m-d H:i') }}</td>
            <td>{{ number_format($item->news_views) }}</td>
            <td>
                <a href="{{ route('admin.news.edit', $item->news_id) }}" class="btn btn-xs btn-primary">Edit</a>
                <form action="{{ route('admin.news.destroy', $item->news_id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this news?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">No news found.</td></tr>
        @endforelse
    </tbody>
</table>
{{ $news->links() }}
@endsection
