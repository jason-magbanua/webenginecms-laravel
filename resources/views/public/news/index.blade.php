@extends('layouts.app')

@section('title', 'News')

@section('content')
<div class="page-header"><h2>News</h2></div>

@forelse($news as $article)
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a href="{{ route('news.show', $article->news_id) }}">{{ $article->news_title }}</a>
        </h3>
    </div>
    <div class="panel-body">
        {!! Str::limit(strip_tags($article->news_content), 300) !!}
        <a href="{{ route('news.show', $article->news_id) }}" class="btn btn-link btn-xs">Read more &raquo;</a>
    </div>
    <div class="panel-footer text-right text-muted small">
        By {{ $article->news_author }} &mdash;
        {{ $article->news_date ? $article->news_date->format('l, F jS Y') : '' }}
    </div>
</div>
@empty
<div class="alert alert-info">No news articles yet.</div>
@endforelse

{{ $news->links() }}
@endsection
