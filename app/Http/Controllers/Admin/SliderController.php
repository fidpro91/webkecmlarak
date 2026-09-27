<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::orderBy('urutan', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create(): View
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'urutan' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'gambar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('sliders', 'public');
            $validated['gambar'] = $path;
        }

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider beranda berhasil ditambahkan.');
    }

    public function edit(Slider $slider): View
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
            'urutan' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('gambar')) {
            // Delete old file if local
            if ($slider->gambar && !str_starts_with($slider->gambar, 'http') && Storage::disk('public')->exists($slider->gambar)) {
                Storage::disk('public')->delete($slider->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('sliders', 'public');
        }

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider beranda berhasil diperbarui.');
    }

    public function destroy(Slider $slider): RedirectResponse
    {
        if ($slider->gambar && !str_starts_with($slider->gambar, 'http') && Storage::disk('public')->exists($slider->gambar)) {
            Storage::disk('public')->delete($slider->gambar);
        }

        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider beranda berhasil dihapus.');
    }
}
