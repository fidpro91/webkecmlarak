<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\DownloadCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Download::aktif()->with('category');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('judul', 'like', "%{$search}%");
        }

        if ($request->filled('kategori')) {
            $category = DownloadCategory::where('slug', $request->kategori)->first();
            if ($category) {
                $query->where('download_category_id', $category->id);
            }
        }

        $downloads = $query->paginate(10)->withQueryString();
        $categories = DownloadCategory::withCount(['downloads' => function ($q) {
            $q->where('status', true);
        }])->get();

        return view('frontend.download.index', compact('downloads', 'categories'));
    }

    public function preview(int $id): BinaryFileResponse
    {
        $download = Download::findOrFail($id);

        $path = Storage::disk('public')->path($download->file);

        if (!file_exists($path)) {
            abort(404, 'Berkas tidak ditemukan pada server.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($download->file) . '"',
        ]);
    }

    public function download(int $id): BinaryFileResponse|StreamedResponse
    {
        $download = Download::findOrFail($id);

        $path = Storage::disk('public')->path($download->file);

        if (!file_exists($path)) {
            abort(404, 'Berkas tidak ditemukan pada server.');
        }

        // Increment counter
        $download->increment('jumlah_unduhan');

        return response()->download($path, $download->judul . '.' . $download->tipe_file);
    }
}
