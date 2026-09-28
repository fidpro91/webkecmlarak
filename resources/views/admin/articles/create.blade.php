@extends('layouts.admin')

@section('title', 'Tulis Berita')
@section('page_title', 'Tulis Artikel Berita Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Buat Berita / Siaran Pers</h2>
            <p class="text-xs text-slate-500">Tuliskan informasi kegiatan kedinasan atau dinamika kemasyarakatan</p>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Artikel *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                       placeholder="Contoh: Camat Mlarak Hadiri Penyaluran BLT Dana Desa di Balai Desa Bajang"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Berita *</label>
                    <select name="category_id" id="category_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Publikasi *</label>
                    <select name="status" id="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Publikasikan Langsung (Tayang)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="gambar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Foto Utama / Thumbnail Artikel</label>
                <input type="file" name="gambar" id="gambar" accept="image/*"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Rekomendasi rasio 16:9 (Landscape), resolusi minimal 800x450px.</p>
            </div>

            <div>
                <label for="konten" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Konten Berita *</label>
                <textarea name="konten" id="konten" rows="14" placeholder="Tuliskan isi berita secara lengkap di sini..."
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-sans leading-relaxed">{{ old('konten') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Gunakan toolbar editor di atas untuk mengatur format teks, judul sub-bab, daftar/poin, tabel, link, atau menyisipkan foto liputan.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.articles.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan & Publikasikan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@include('admin.articles._tinymce')
