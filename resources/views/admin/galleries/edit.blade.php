@extends('layouts.admin')

@section('title', 'Edit Media Galeri')
@section('page_title', 'Edit Media Galeri')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Media Galeri</h2>
            <p class="text-xs text-slate-500">Perbarui rincian media dokumentasi</p>
        </div>
        <a href="{{ route('admin.galleries.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{ tipe: '{{ old('tipe', $gallery->tipe) }}' }">
        <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Media *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $gallery->judul) }}" required
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="tipe" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Media *</label>
                <select name="tipe" id="tipe" x-model="tipe" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="foto">Foto Dokumentasi (Upload Gambar)</option>
                    <option value="video">Video Kegiatan (URL YouTube)</option>
                </select>
            </div>

            <!-- Field Upload Foto -->
            <div x-show="tipe === 'foto'" x-transition class="space-y-3">
                @if($gallery->tipe === 'foto')
                    <div>
                        <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Foto Saat Ini:</span>
                        <img src="{{ $gallery->file_url }}" alt="{{ $gallery->judul }}" class="w-48 h-28 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif

                <div>
                    <label for="file_foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ganti Foto (Opsional)</label>
                    <input type="file" name="file_foto" id="file_foto" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
            </div>

            <!-- Field URL Video -->
            <div x-show="tipe === 'video'" x-transition class="space-y-2">
                <label for="file_video_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tautan Video YouTube *</label>
                <input type="url" name="file_video_url" id="file_video_url" value="{{ old('file_video_url', $gallery->tipe === 'video' ? $gallery->file : '') }}"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan / Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi', $gallery->deskripsi) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.galleries.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Perbarui Media
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
