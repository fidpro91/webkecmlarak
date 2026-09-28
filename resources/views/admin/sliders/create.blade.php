@extends('layouts.admin')

@section('title', 'Tambah Slider')
@section('page_title', 'Tambah Slider Beranda')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Slider Baru</h2>
            <p class="text-xs text-slate-500">Unggah foto dan atur judul slider carousel</p>
        </div>
        <a href="{{ route('admin.sliders.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Slider *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                       placeholder="Contoh: Pelayanan PATEN Terpadu Kecamatan Mlarak"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Uraian singkat pendukung..."
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="urutan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil *</label>
                    <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Publikasi *</label>
                    <select name="status" id="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Aktif (Tampilkan)</option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                    </select>
                </div>
            </div>

            <div x-data="{ previewUrl: null }">
                <label for="gambar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Gambar Slider *</label>
                
                <template x-if="previewUrl">
                    <div class="mb-3">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 mb-2">
                            <i class="fa-solid fa-circle-check"></i> Pratinjau Gambar:
                        </span>
                        <img :src="previewUrl" alt="Pratinjau gambar" class="w-full max-w-lg h-44 object-cover rounded-xl shadow-md border-2 border-emerald-500">
                    </div>
                </template>

                <input type="file" name="gambar" id="gambar" required accept="image/jpeg,image/png,image/webp,image/jpg"
                       @change="
                           const file = $event.target.files[0];
                           if (file) {
                               if (file.size > 10 * 1024 * 1024) {
                                   alert('Ukuran file terlalu besar (' + (file.size / (1024 * 1024)).toFixed(2) + ' MB). Maksimal ukuran gambar slider adalah 10 MB.');
                                   $event.target.value = '';
                                   previewUrl = null;
                                   return;
                               }
                               previewUrl = URL.createObjectURL(file);
                           } else {
                               previewUrl = null;
                           }
                       "
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Format: JPG, JPEG, PNG, WEBP. Rekomendasi resolusi landscape 1600x700px (Maksimal 10 MB).</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.sliders.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Slider
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
