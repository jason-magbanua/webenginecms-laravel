@extends('layouts.admin')
@section('title', 'Edit News')

@section('content')
<h1 class="page-header">Edit News</h1>

<form action="{{ route('admin.news.update', $news->news_id) }}" method="POST">
    @csrf @method('PUT')
    <div class="form-group">
        <label>Title</label>
        <input type="text" class="form-control" name="news_title" value="{{ old('news_title', $news->news_title) }}" required>
    </div>
    <div class="form-group">
        <label>Content</label>
        <textarea name="news_content" class="form-control" rows="12" required>{{ old('news_content', $news->news_content) }}</textarea>
    </div>
    <div class="form-group">
        <label>Author</label>
        <input type="text" class="form-control" name="news_author" value="{{ old('news_author', $news->news_author) }}">
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.news.index') }}" class="btn btn-default">Cancel</a>
</form>
@endsection
