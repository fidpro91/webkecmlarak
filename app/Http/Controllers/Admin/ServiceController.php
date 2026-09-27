<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()
            ->when($request->filled('q'), fn($q) => $q->where('nama_layanan', 'like', "%{$request->q}%"))
            ->orderBy('nama_layanan')
            ->paginate(10);

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255', 'unique:services,nama_layanan'],
            'syarat' => ['required', 'string'],
            'prosedur' => ['required', 'string'],
            'file_sop' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $validated['slug'] = Str::slug($validated['nama_layanan']);

        if ($request->hasFile('file_sop')) {
            $validated['file_sop'] = $request->file('file_sop')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Layanan publik berhasil ditambahkan.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255', 'unique:services,nama_layanan,' . $service->id],
            'syarat' => ['required', 'string'],
            'prosedur' => ['required', 'string'],
            'file_sop' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        if ($service->nama_layanan !== $validated['nama_layanan']) {
            $validated['slug'] = Str::slug($validated['nama_layanan']);
        }

        if ($request->hasFile('file_sop')) {
            if ($service->file_sop && !str_starts_with($service->file_sop, 'http') && Storage::disk('public')->exists($service->file_sop)) {
                Storage::disk('public')->delete($service->file_sop);
            }
            $validated['file_sop'] = $request->file('file_sop')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Layanan publik berhasil diperbarui.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->file_sop && !str_starts_with($service->file_sop, 'http') && Storage::disk('public')->exists($service->file_sop)) {
            Storage::disk('public')->delete($service->file_sop);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Layanan publik berhasil dihapus.');
    }
}
