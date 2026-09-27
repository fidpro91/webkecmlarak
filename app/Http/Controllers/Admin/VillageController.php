<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VillageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Village::query();

        if ($request->filled('q')) {
            $query->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('kepala_desa', 'like', "%{$request->q}%");
        }

        $villages = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();

        return view('admin.villages.index', compact('villages'));
    }

    public function create(): View
    {
        return view('admin.villages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:villages,nama'],
            'kepala_desa' => ['nullable', 'string', 'max:100'],
            'jumlah_penduduk' => ['required', 'integer', 'min:0'],
            'luas_wilayah' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        $validated['slug'] = Str::slug($validated['nama']);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('villages', 'public');
        }

        Village::create($validated);

        return redirect()->route('admin.villages.index')->with('success', 'Data desa berhasil ditambahkan.');
    }

    public function edit(Village $village): View
    {
        return view('admin.villages.edit', compact('village'));
    }

    public function update(Request $request, Village $village): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:villages,nama,' . $village->id],
            'kepala_desa' => ['nullable', 'string', 'max:100'],
            'jumlah_penduduk' => ['required', 'integer', 'min:0'],
            'luas_wilayah' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($village->nama !== $validated['nama']) {
            $validated['slug'] = Str::slug($validated['nama']);
        }

        if ($request->hasFile('foto')) {
            if ($village->foto && !str_starts_with($village->foto, 'http') && Storage::disk('public')->exists($village->foto)) {
                Storage::disk('public')->delete($village->foto);
            }
            $validated['foto'] = $request->file('foto')->store('villages', 'public');
        }

        $village->update($validated);

        return redirect()->route('admin.villages.index')->with('success', 'Data desa berhasil diperbarui.');
    }

    public function destroy(Village $village): RedirectResponse
    {
        if ($village->foto && !str_starts_with($village->foto, 'http') && Storage::disk('public')->exists($village->foto)) {
            Storage::disk('public')->delete($village->foto);
        }

        $village->delete();

        return redirect()->route('admin.villages.index')->with('success', 'Data desa berhasil dihapus.');
    }
}
