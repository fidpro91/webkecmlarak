<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
            'bagan_struktur' => ['required', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:10240'],
        ], [
            'bagan_struktur.required' => 'Silakan pilih berkas gambar bagan struktur organisasi.',
            'bagan_struktur.image' => 'Berkas yang diunggah harus berupa file gambar.',
            'bagan_struktur.mimes' => 'Format gambar harus berupa PNG, JPG, JPEG, WEBP, atau SVG.',
            'bagan_struktur.max' => 'Ukuran berkas gambar maksimal adalah 10 MB.',
        ]);

        $currentBagan = Setting::get('bagan_struktur_organisasi');
        if ($currentBagan && str_starts_with($currentBagan, '/storage/settings/') && Storage::disk('public')->exists(str_replace('/storage/', '', $currentBagan))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $currentBagan));
        }

        $path = $request->file('bagan_struktur')->store('settings', 'public');
        Setting::set('bagan_struktur_organisasi', Storage::url($path));
        Setting::clearCache();

        return back()->with('success', 'Gambar bagan struktur organisasi berhasil diperbarui.');
    }

    public function deleteBagan(): RedirectResponse
    {
        $currentBagan = Setting::get('bagan_struktur_organisasi');
        if ($currentBagan && str_starts_with($currentBagan, '/storage/settings/') && Storage::disk('public')->exists(str_replace('/storage/', '', $currentBagan))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $currentBagan));
        }

        Setting::set('bagan_struktur_organisasi', null);
        Setting::clearCache();

        return back()->with('success', 'Bagan struktur organisasi berhasil direset ke bagan standar sistem.');
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
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('officials', 'public');
        }

        Official::create($validated);

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat/pegawai berhasil ditambahkan.');
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
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('foto')) {
            if ($official->foto && !str_starts_with($official->foto, 'http') && Storage::disk('public')->exists($official->foto)) {
                Storage::disk('public')->delete($official->foto);
            }
            $validated['foto'] = $request->file('foto')->store('officials', 'public');
        }

        $official->update($validated);

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat/pegawai berhasil diperbarui.');
    }

    public function destroy(Official $official): RedirectResponse
    {
        if ($official->foto && !str_starts_with($official->foto, 'http') && Storage::disk('public')->exists($official->foto)) {
            Storage::disk('public')->delete($official->foto);
        }

        $official->delete();

        return redirect()->route('admin.officials.index')->with('success', 'Data pejabat/pegawai berhasil dihapus.');
    }
}
