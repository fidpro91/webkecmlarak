@extends('layouts.admin')

@section('title', 'Tambah Media Galeri')
@section('page_title', 'Tambah Media Galeri Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Media Galeri</h2>
            <p class="text-xs text-slate-500">Unggah foto dokumentasi atau sematkan tautan video YouTube</p>
        </div>
        <a href="{{ route('admin.galleries.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{ tipe: '{{ old('tipe', 'foto') }}' }">
        <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Media *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                       placeholder="Contoh: Apel Gabungan Kesiapsiagaan Bencana di Lapangan Mlarak"
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
            <div x-show="tipe === 'foto'" x-transition class="space-y-2">
                <label for="file_foto" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Upload Foto *</label>
                <input type="file" name="file_foto" id="file_foto" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400">Format: JPG, PNG, WEBP (Maksimal 5MB).</p>
            </div>

            <!-- Field URL Video -->
            <div x-show="tipe === 'video'" x-transition class="space-y-2">
                <label for="file_video_url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tautan Video YouTube *</label>
                <input type="url" name="file_video_url" id="file_video_url" value="{{ old('file_video_url') }}"
                       placeholder="https://www.youtube.com/watch?v=..."
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                <p class="text-[11px] text-slate-400">Salin URL lengkap video YouTube dokumentasi kegiatan.</p>
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan / Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Uraian singkat mengenai kegiatan atau momen ini..."
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.galleries.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Media
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
