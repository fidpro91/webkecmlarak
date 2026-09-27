@extends('layouts.admin')

@section('title', 'Tambah Layanan')
@section('page_title', 'Tambah Layanan Publik (PATEN)')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Jenis Layanan Baru</h2>
            <p class="text-xs text-slate-500">Definisikan syarat berkas dan prosedur permohonan</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="nama_layanan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Layanan *</label>
                <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan') }}" required
                       placeholder="Contoh: Rekomendasi Izin Usaha Mikro dan Kecil (IUMK)"
                       class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            </div>

            <div>
                <label for="syarat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Persyaratan Permohonan *</label>
                <textarea name="syarat" id="syarat" rows="5" required
                          placeholder="Uraikan poin-poin persyaratan dokumen yang harus dibawa pemohon..."
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('syarat') }}</textarea>
            </div>

            <div>
                <label for="prosedur" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Prosedur / Alur Pelayanan *</label>
                <textarea name="prosedur" id="prosedur" rows="5" required
                          placeholder="Jelaskan alur tahapan mulai dari loket pendaftaran hingga penerbitan surat..."
                          class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('prosedur') }}</textarea>
            </div>

            <div>
                <label for="file_sop" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Upload Berkas Dokumen SOP Resmi (PDF/DOC)</label>
                <input type="file" name="file_sop" id="file_sop" accept=".pdf,.doc,.docx"
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Maksimal 10MB. Format PDF disarankan agar bisa langsung dipratinjau warga.</p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.services.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                    Simpan Layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
