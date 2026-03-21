<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebEngine\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('news_date')->paginate(25);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'news_title'   => 'required|string|max:200',
            'news_content' => 'required|string',
            'news_author'  => 'nullable|string|max:100',
        ]);

        News::create([
            'news_title'    => $request->news_title,
            'news_content'  => $request->news_content,
            'news_author'   => $request->news_author ?: Auth::user()->memb___id,
            'news_date'     => now(),
            'news_category' => 0,
            'news_views'    => 0,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News published.');
    }

    public function edit(int $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'news_title'   => 'required|string|max:200',
            'news_content' => 'required|string',
            'news_author'  => 'nullable|string|max:100',
        ]);

        $news = News::findOrFail($id);
        $news->update([
            'news_title'   => $request->news_title,
            'news_content' => $request->news_content,
            'news_author'  => $request->news_author ?: $news->news_author,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News updated.');
    }

    public function destroy(int $id)
    {
        News::findOrFail($id)->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted.');
    }
}
