<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::published()->with(['category', 'author']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $category = Category::where('slug', $request->kategori)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($request->filled('tag')) {
            $tag = trim((string)$request->tag);
            $cleanTag = ltrim($tag, '#');
            $query->where(function ($q) use ($cleanTag) {
                $q->where('hashtags', 'like', "%\"#{$cleanTag}\"%")
                  ->orWhere('hashtags', 'like', "%\"{$cleanTag}\"%")
                  ->orWhere('hashtags', 'like', "%{$cleanTag}%");
            });
        }

        $activeTag = $request->tag ? (str_starts_with($request->tag, '#') ? $request->tag : '#' . $request->tag) : null;

        $articles = $query->paginate(9)->withQueryString();
        $categories = Category::withCount(['articles' => function ($q) {
            $q->where('status', 'published');
        }])->get();
        $recentArticles = Article::published()->take(5)->get();

        return view('frontend.articles.index', compact('articles', 'categories', 'recentArticles', 'activeTag'));
    }

    public function show(string $slug): View
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'author'])
            ->firstOrFail();

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->take(3)
            ->get();

        $categories = Category::withCount(['articles' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        $recentArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->take(5)
            ->get();

        if ($recentArticles->isEmpty()) {
            $recentArticles = Article::published()->take(5)->get();
        }

        $popularArticles = $recentArticles;

        return view('frontend.articles.show', compact('article', 'relatedArticles', 'categories', 'recentArticles', 'popularArticles'));
    }
}
