<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DownloadCategoryController extends Controller
{
    public function index(): View
    {
        $categories = DownloadCategory::withCount('downloads')->orderBy('nama')->get();
        return view('admin.download_categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.download_categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:download_categories,nama'],
        ]);

        DownloadCategory::create([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
        ]);

        return redirect()->route('admin.download-categories.index')->with('success', 'Kategori unduhan berhasil ditambahkan.');
    }

    public function edit(DownloadCategory $downloadCategory): View
    {
        return view('admin.download_categories.edit', compact('downloadCategory'));
    }

    public function update(Request $request, DownloadCategory $downloadCategory): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:download_categories,nama,' . $downloadCategory->id],
        ]);

        $downloadCategory->update([
            'nama' => $validated['nama'],
            'slug' => Str::slug($validated['nama']),
        ]);

        return redirect()->route('admin.download-categories.index')->with('success', 'Kategori unduhan berhasil diperbarui.');
    }

    public function destroy(DownloadCategory $downloadCategory): RedirectResponse
    {
        if ($downloadCategory->downloads()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki berkas terkait.');
        }

        $downloadCategory->delete();

        return redirect()->route('admin.download-categories.index')->with('success', 'Kategori unduhan berhasil dihapus.');
    }
}
