<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::with(['category', 'author']);

        if ($request->filled('q')) {
            $query->where('judul', 'like', "%{$request->q}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $articles = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.articles.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $validated['slug'] = Str::slug($validated['judul']) . '-' . Str::random(5);
        $validated['user_id'] = $request->user()->id;

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = ImageService::compressAndStore($request->file('gambar'), 'articles', 1400, 900);
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berita berhasil diterbitkan.');
    }

    public function edit(Article $article): View
    {
        $categories = Category::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        if ($article->judul !== $validated['judul']) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . Str::random(5);
        }

        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('gambar')) {
            if ($article->gambar && !str_starts_with($article->gambar, 'http') && Storage::disk('public')->exists($article->gambar)) {
                Storage::disk('public')->delete($article->gambar);
            }
            $validated['gambar'] = ImageService::compressAndStore($request->file('gambar'), 'articles', 1400, 900);
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berita berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        if ($article->gambar && !str_starts_with($article->gambar, 'http') && Storage::disk('public')->exists($article->gambar)) {
            Storage::disk('public')->delete($article->gambar);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berita berhasil dihapus.');
    }

    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
        ]);

        $path = ImageService::compressAndStore($request->file('file'), 'articles/content', 1400, 1400);
        $url = asset('storage/' . $path);

        return response()->json([
            'location' => $url,
        ]);
    }
}
