@extends('layouts.admin')
@section('title', 'Publish News')

@section('content')
<h1 class="page-header">Publish News</h1>

<form action="{{ route('admin.news.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label>Title</label>
        <input type="text" class="form-control" name="news_title" value="{{ old('news_title') }}" required>
    </div>
    <div class="form-group">
        <label>Content</label>
        <textarea name="news_content" class="form-control" rows="12" required>{{ old('news_content') }}</textarea>
    </div>
    <div class="form-group">
        <label>Author</label>
        <input type="text" class="form-control" name="news_author" value="{{ old('news_author', Auth::user()->memb___id) }}">
    </div>
    <button type="submit" class="btn btn-success">Publish</button>
    <a href="{{ route('admin.news.index') }}" class="btn btn-default">Cancel</a>
</form>
@endsection
