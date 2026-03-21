<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('news_date')->paginate(10);
        return view('public.news.index', compact('news'));
    }

    public function show(int $id)
    {
        $article = News::findOrFail($id);
        $article->increment('news_views');
        return view('public.news.show', compact('article'));
    }
}
