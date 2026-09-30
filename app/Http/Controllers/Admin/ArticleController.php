<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\SocialMediaAccount;
use App\Services\GeminiAiService;
use App\Services\ImageService;
use App\Services\SocialMediaPostService;
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
        $socialAccounts = SocialMediaAccount::where('is_active', true)->get();
        return view('admin.articles.create', compact('categories', 'socialAccounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'hashtags' => ['nullable'],
            'auto_post_social' => ['nullable'],
            'social_channels' => ['nullable', 'array'],
        ]);

        $validated['slug'] = Str::slug($validated['judul']) . '-' . Str::random(5);
        $validated['user_id'] = $request->user()->id;
        $validated['hashtags'] = $this->parseHashtags($request->input('hashtags'));

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = ImageService::compressAndStore($request->file('gambar'), 'articles', 1400, 900);
        }

        $article = Article::create($validated);

        // Jika dipublikasikan dan switcher auto-post sosial media diaktifkan
        if ($validated['status'] === 'published' && $request->boolean('auto_post_social')) {
            SocialMediaPostService::postArticle($article, $request->input('social_channels', []));
        }

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berita berhasil diterbitkan.');
    }

    public function edit(Article $article): View
    {
        $categories = Category::all();
        $socialAccounts = SocialMediaAccount::where('is_active', true)->get();
        $socialLogs = $article->socialPostLogs()->with('account')->latest()->take(5)->get();
        return view('admin.articles.edit', compact('article', 'categories', 'socialAccounts', 'socialLogs'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'hashtags' => ['nullable'],
            'auto_post_social' => ['nullable'],
            'social_channels' => ['nullable', 'array'],
        ]);

        if ($article->judul !== $validated['judul']) {
            $validated['slug'] = Str::slug($validated['judul']) . '-' . Str::random(5);
        }

        if ($validated['status'] === 'published' && !$article->published_at) {
            $validated['published_at'] = now();
        }

        $validated['hashtags'] = $this->parseHashtags($request->input('hashtags'));

        if ($request->hasFile('gambar')) {
            if ($article->gambar && !str_starts_with($article->gambar, 'http') && Storage::disk('public')->exists($article->gambar)) {
                Storage::disk('public')->delete($article->gambar);
            }
            $validated['gambar'] = ImageService::compressAndStore($request->file('gambar'), 'articles', 1400, 900);
        }

        $article->update($validated);

        // Jika status published dan auto-post dicentang
        if ($validated['status'] === 'published' && $request->boolean('auto_post_social')) {
            SocialMediaPostService::postArticle($article, $request->input('social_channels', []));
        }

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berita berhasil diperbarui.');
    }

    /**
     * Parse input hashtags menjadi array hashtag yang valid dan unik.
     */
    private function parseHashtags(mixed $raw): array
    {
        if (empty($raw)) {
            return [];
        }

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $raw = $decoded;
            } else {
                $raw = explode(',', $raw);
            }
        }

        if (!is_array($raw)) {
            return [];
        }

        $clean = [];
        foreach ($raw as $tag) {
            $tag = trim((string)$tag);
            if (!empty($tag)) {
                $tag = preg_replace('/\s+/', '_', $tag);
                $clean[] = str_starts_with($tag, '#') ? $tag : ('#' . $tag);
            }
        }

        return array_values(array_unique($clean));
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

    public function generateAi(Request $request): \Illuminate\Http\JsonResponse
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(120);
        }

        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:5', 'max:2500'],
            'judul' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ], [
            'prompt.required' => 'Silakan tuliskan instruksi atau poin-poin berita untuk AI.',
            'prompt.min' => 'Instruksi minimal 5 karakter agar AI dapat memahami topik dengan baik.',
        ]);

        $kategoriNama = null;
        if (!empty($validated['category_id'])) {
            $cat = Category::find($validated['category_id']);
            $kategoriNama = $cat?->nama;
        }

        $result = GeminiAiService::generateArticle(
            prompt: $validated['prompt'],
            judul: $validated['judul'] ?? null,
            kategori: $kategoriNama
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'success' => true,
            'content' => $result['content'],
            'message' => 'Naskah artikel berhasil dibuat oleh AI Agent!',
        ]);
    }
}
