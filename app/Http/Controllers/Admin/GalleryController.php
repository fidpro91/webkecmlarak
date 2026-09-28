<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $galleries = Gallery::query()
            ->when($request->filled('tipe'), fn($q) => $q->where('tipe', $request->tipe))
            ->latest()
            ->paginate(12);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create(): View
    {
        return view('admin.galleries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'in:foto,video'],
            'deskripsi' => ['nullable', 'string'],
            'file_foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'file_video_url' => ['nullable', 'string', 'url'],
        ]);

        if ($validated['tipe'] === 'foto') {
            if (!$request->hasFile('file_foto')) {
                return back()->withErrors(['file_foto' => 'File foto wajib diunggah.'])->withInput();
            }
            $validated['file'] = ImageService::compressAndStore($request->file('file_foto'), 'galleries', 1920, 1400);
        } else {
            if (empty($validated['file_video_url'])) {
                return back()->withErrors(['file_video_url' => 'URL Video (YouTube) wajib diisi.'])->withInput();
            }
            $validated['file'] = $validated['file_video_url'];
        }

        Gallery::create([
            'judul' => $validated['judul'],
            'tipe' => $validated['tipe'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file' => $validated['file'],
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Item galeri berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'in:foto,video'],
            'deskripsi' => ['nullable', 'string'],
            'file_foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'file_video_url' => ['nullable', 'string', 'url'],
        ]);

        $file = $gallery->file;

        if ($validated['tipe'] === 'foto') {
            if ($request->hasFile('file_foto')) {
                if ($gallery->file && !str_starts_with($gallery->file, 'http') && Storage::disk('public')->exists($gallery->file)) {
                    Storage::disk('public')->delete($gallery->file);
                }
                $file = ImageService::compressAndStore($request->file('file_foto'), 'galleries', 1920, 1400);
            }
        } else {
            if ($request->filled('file_video_url')) {
                $file = $validated['file_video_url'];
            }
        }

        $gallery->update([
            'judul' => $validated['judul'],
            'tipe' => $validated['tipe'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'file' => $file,
        ]);

        return redirect()->route('admin.galleries.index')->with('success', 'Item galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        if ($gallery->file && !str_starts_with($gallery->file, 'http') && Storage::disk('public')->exists($gallery->file)) {
            Storage::disk('public')->delete($gallery->file);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Item galeri berhasil dihapus.');
    }
}
