@extends('layouts.admin')

@section('title', 'Tambah Menu')
@section('page_title', 'Tambah Menu Navigasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Menu Navigasi</h2>
            <p class="text-xs text-slate-500">Buat link baru atau buat halaman statis dinamis</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm" x-data="{ tipe: '{{ old('tipe', 'page') }}' }">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="nama_menu" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Menu *</label>
                    <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu') }}" required
                           placeholder="Contoh: Visi & Misi atau Regulasi Desa"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="parent_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Induk Menu (Parent)</label>
                    <select name="parent_id" id="parent_id" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">-- Menu Utama (Tanpa Induk) --</option>
                        @foreach($parentMenus as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                Submenu dari: {{ $parent->nama_menu }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="tipe" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Menu *</label>
                    <select name="tipe" id="tipe" x-model="tipe" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="page">Halaman Statis (Page)</option>
                        <option value="module">Modul Internal (/berita, dll)</option>
                        <option value="external_link">Link Eksternal (URL Lain)</option>
                    </select>
                </div>

                <div>
                    <label for="urutan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Urutan Tampil *</label>
                    <input type="number" name="urutan" id="urutan" value="{{ old('urutan', 1) }}" min="0" required
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status *</label>
                    <select name="status" id="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Field URL (Untuk tipe module & external_link) -->
            <div x-show="tipe !== 'page'" x-transition class="space-y-2">
                <label for="url" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Target URL / Rute *</label>
                <input type="text" name="url" id="url" value="{{ old('url') }}"
                       placeholder="Contoh: /data-desa atau https://ponorogo.go.id"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                <p class="text-[11px] text-slate-400">Gunakan tanda awalan garis miring <code>/</code> untuk rute internal (contoh: <code>/profil</code>, <code>/galeri</code>).</p>
            </div>

            <!-- Field Konten Halaman (Khusus tipe page) -->
            <div x-show="tipe === 'page'" x-transition class="space-y-5 pt-4 border-t border-slate-100">
                <div>
                    <label for="konten" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Konten Halaman (HTML / Teks) *</label>
                    <textarea name="konten" id="konten" rows="8" placeholder="Tuliskan isi informasi halaman statis di sini..."
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-sans">{{ old('konten') }}</textarea>
                </div>

                <div>
                    <label for="gambar_page" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Foto Sampul Halaman (Opsional)</label>
                    <input type="file" name="gambar_page" id="gambar_page" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.menus.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
