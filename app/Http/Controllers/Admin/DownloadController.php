<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\DownloadCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DownloadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Download::with('category');

        if ($request->filled('q')) {
            $query->where('judul', 'like', "%{$request->q}%");
        }

        if ($request->filled('category_id')) {
            $query->where('download_category_id', $request->category_id);
        }

        $downloads = $query->latest()->paginate(10)->withQueryString();
        $categories = DownloadCategory::all();

        return view('admin.downloads.index', compact('downloads', 'categories'));
    }

    public function create(): View
    {
        $categories = DownloadCategory::all();
        return view('admin.downloads.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'download_category_id' => ['required', 'exists:download_categories,id'],
            'status' => ['required', 'boolean'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar', 'max:10240'],
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $sizeBytes = $file->getSize();
        $sizeFormatted = Download::formatBytes($sizeBytes);

        $path = $file->store('downloads', 'public');

        Download::create([
            'judul' => $validated['judul'],
            'download_category_id' => $validated['download_category_id'],
            'status' => $validated['status'],
            'file' => $path,
            'tipe_file' => $ext,
            'ukuran_file' => $sizeFormatted,
            'jumlah_unduhan' => 0,
        ]);

        return redirect()->route('admin.downloads.index')->with('success', 'Berkas unduhan berhasil diunggah.');
    }

    public function edit(Download $download): View
    {
        $categories = DownloadCategory::all();
        return view('admin.downloads.edit', compact('download', 'categories'));
    }

    public function update(Request $request, Download $download): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'download_category_id' => ['required', 'exists:download_categories,id'],
            'status' => ['required', 'boolean'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar', 'max:10240'],
        ]);

        $updateData = [
            'judul' => $validated['judul'],
            'download_category_id' => $validated['download_category_id'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('file')) {
            if ($download->file && Storage::disk('public')->exists($download->file)) {
                Storage::disk('public')->delete($download->file);
            }

            $file = $request->file('file');
            $updateData['file'] = $file->store('downloads', 'public');
            $updateData['tipe_file'] = strtolower($file->getClientOriginalExtension());
            $updateData['ukuran_file'] = Download::formatBytes($file->getSize());
        }

        $download->update($updateData);

        return redirect()->route('admin.downloads.index')->with('success', 'Data berkas unduhan berhasil diperbarui.');
    }

    public function destroy(Download $download): RedirectResponse
    {
        if ($download->file && Storage::disk('public')->exists($download->file)) {
            Storage::disk('public')->delete($download->file);
        }

        $download->delete();

        return redirect()->route('admin.downloads.index')->with('success', 'Berkas unduhan berhasil dihapus.');
    }
}
