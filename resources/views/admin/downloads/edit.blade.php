@extends('layouts.admin')

@section('title', 'Edit Berkas')
@section('page_title', 'Edit Data Berkas Unduhan')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Berkas Unduhan</h2>
            <p class="text-xs text-slate-500">Perbarui judul, kategori, atau ganti file dokumen</p>
        </div>
        <a href="{{ route('admin.downloads.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.downloads.update', $download->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="judul" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Judul Dokumen *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $download->judul) }}" required
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="download_category_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Berkas *</label>
                    <select name="download_category_id" id="download_category_id" required class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('download_category_id', $download->download_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status Unduhan *</label>
                    <select name="status" id="status" class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                        <option value="1" {{ old('status', $download->status) ? 'selected' : '' }}>Aktif (Tersedia Publik)</option>
                        <option value="0" {{ !old('status', $download->status) ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                    </select>
                </div>
            </div>

            <div>
                <div class="mb-3 p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs text-slate-600">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-file text-slate-500"></i> Berkas tersimpan: <strong>{{ basename($download->file) }}</strong> ({{ $download->ukuran_file }})
                    </span>
                    <span class="font-bold text-emerald-700">Diunduh {{ $download->jumlah_unduhan }}x</span>
                </div>

                <label for="file" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ganti File Dokumen (Opsional)</label>
                <input type="file" name="file" id="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika tidak ingin mengganti file yang sudah ada.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.downloads.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Perbarui Berkas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
