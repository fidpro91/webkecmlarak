<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\Setting;
use App\Services\BaganSvgService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OfficialController extends Controller
{
    public function index(): View
    {
        $officials = Official::orderBy('urutan', 'asc')->get();
        $baganStrukturUrl = Setting::baganStrukturUrl();
        $hasCustomBagan = !empty(Setting::get('bagan_struktur_organisasi'));

        return view('admin.officials.index', compact('officials', 'baganStrukturUrl', 'hasCustomBagan'));
    }

    public function updateBagan(Request $request): RedirectResponse
    {
        $request->validate([
            'bagan_struktur' => ['required', 'file', 'mimes:svg', 'max:5120'],
        ], [
            'bagan_struktur.required' => 'Silakan pilih berkas SVG bagan struktur organisasi.',
            'bagan_struktur.file' => 'Berkas yang diunggah tidak valid.',
            'bagan_struktur.mimes' => 'Berkas harus berupa gambar vektor berformat SVG (.svg). Format bitmap (PNG/JPG) tidak didukung agar sistem dapat memetakan posisi jabatan secara otomatis.',
            'bagan_struktur.max' => 'Ukuran berkas SVG maksimal adalah 5 MB.',
        ]);

        $result = BaganSvgService::processUploadedSvg($request->file('bagan_struktur'));

        if (!$result['success']) {
            return back()->withErrors(['bagan_struktur' => $result['message']]);
        }

        return back()->with('success', $result['message']);
    }

    public function deleteBagan(): RedirectResponse
    {
        BaganSvgService::resetToStandard();

        return back()->with('success', 'Bagan struktur organisasi berhasil direset ke bagan standar resmi dan telah disinkronkan.');
    }

    public function syncBagan(): RedirectResponse
    {
        $success = BaganSvgService::sync();

        if ($success) {
            return back()->with('success', 'Bagan struktur organisasi berhasil disinkronkan dan diregenerasi dengan data jajaran pejabat terkini.');
        }

        return back()->with('error', 'Gagal meregenerasi bagan SVG. Silakan periksa log sistem.');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        BaganSvgService::sync(true);
        $path = BaganSvgService::getPublicSvgPath();

        return response()->download($path, 'template-bagan-struktur-organisasi-kecamatan.svg', [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    public function create(): View
    {
        return view('admin.officials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150'],
            'urutan' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = ImageService::compressAndStore($request->file('foto'), 'officials', 800, 800);
        }

        $official = Official::create($validated);

        // Otomatis sinkronkan bagan SVG dan nama camat
        BaganSvgService::sync();

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat/pegawai berhasil ditambahkan dan bagan struktur organisasi otomatis diperbarui.');
    }

    public function edit(Official $official): View
    {
        return view('admin.officials.edit', compact('official'));
    }

    public function update(Request $request, Official $official): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:150'],
            'urutan' => ['required', 'integer', 'min:0'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        if ($request->hasFile('foto')) {
            if ($official->foto && !str_starts_with($official->foto, 'http') && Storage::disk('public')->exists($official->foto)) {
                Storage::disk('public')->delete($official->foto);
            }
            $validated['foto'] = ImageService::compressAndStore($request->file('foto'), 'officials', 800, 800);
        }

        $official->update($validated);

        // Otomatis sinkronkan bagan SVG dan nama camat
        BaganSvgService::sync();

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat/pegawai berhasil diperbarui dan bagan struktur organisasi otomatis disinkronkan.');
    }

    public function destroy(Official $official): RedirectResponse
    {
        if ($official->foto && !str_starts_with($official->foto, 'http') && Storage::disk('public')->exists($official->foto)) {
            Storage::disk('public')->delete($official->foto);
        }

        $official->delete();

        // Otomatis regenerasi bagan SVG
        BaganSvgService::sync();

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat/pegawai berhasil dihapus dan bagan struktur organisasi otomatis disinkronkan.');
    }
}
