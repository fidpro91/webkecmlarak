@extends('layouts.admin')

@section('title', 'Edit Layanan')
@section('page_title', 'Edit Layanan Publik (PATEN)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Layanan Publik</h2>
            <p class="text-xs text-slate-500">Perbarui syarat permohonan atau alur prosedur</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_layanan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Layanan *</label>
                <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan', $service->nama_layanan) }}" required
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="syarat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Persyaratan Permohonan *</label>
                <textarea name="syarat" id="syarat" rows="5" required
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('syarat', $service->syarat) }}</textarea>
            </div>

            <div>
                <label for="prosedur" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Prosedur / Alur Pelayanan *</label>
                <textarea name="prosedur" id="prosedur" rows="5" required
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('prosedur', $service->prosedur) }}</textarea>
            </div>

            <div>
                @if($service->file_sop)
                    <div class="mb-3 flex items-center gap-2 text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200">
                        <i class="fa-solid fa-file-pdf text-rose-500 text-lg"></i>
                        <span>Berkas SOP saat ini: <strong>{{ basename($service->file_sop) }}</strong></span>
                    </div>
                @endif

                <label for="file_sop" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ganti Berkas SOP (Opsional)</label>
                <input type="file" name="file_sop" id="file_sop" accept=".pdf,.doc,.docx"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengubah berkas SOP saat ini.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.services.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Perbarui Layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
