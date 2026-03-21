@extends('layouts.app')

@section('title', $article->news_title)

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ $article->news_title }}</h3>
    </div>
    <div class="panel-body">
        {!! $article->news_content !!}
    </div>
    <div class="panel-footer text-right text-muted small">
        By {{ $article->news_author }} &mdash;
        {{ $article->news_date ? $article->news_date->format('l, F jS Y') : '' }}
        &mdash; {{ number_format($article->news_views) }} views
    </div>
</div>

<a href="{{ route('news.index') }}" class="btn btn-default">&larr; Back to News</a>
@endsection
