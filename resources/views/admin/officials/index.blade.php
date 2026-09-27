@extends('layouts.admin')

@section('title', 'Struktur Pejabat')
@section('page_title', 'Struktur Organisasi & Pejabat')

@section('content')
<div class="space-y-8 max-w-5xl">
    <!-- 1. Card Pengelolaan Bagan Struktur Organisasi -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8 space-y-6"
         x-data="{ fileChosen: false, fileName: '', filePreview: '' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl shadow-sm">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Bagan Struktur Organisasi Kecamatan</h2>
                        @if($hasCustomBagan)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                <i class="fa-solid fa-check text-[10px] mr-1"></i> Bagan Kustom
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600">
                                <i class="fa-solid fa-circle-nodes text-[10px] mr-1"></i> Standar Sistem
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">Gambar bagan hierarki yang tampil pada halaman profil publik kecamatan</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $baganStrukturUrl }}" target="_blank" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                    <i class="fa-solid fa-arrow-up-right-from-square text-emerald-600"></i> Buka Gambar Penuh
                </a>
                <a href="{{ route('profile') }}#struktur" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                    <i class="fa-solid fa-globe text-emerald-600"></i> Lihat di Website
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Kolom Preview Gambar Bagan -->
            <div class="lg:col-span-6 space-y-3">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Tampilan Bagan Saat Ini
                </span>
                
                <div class="relative bg-slate-50 rounded-2xl border border-slate-200/90 p-3 overflow-hidden group shadow-inner">
                    <img src="{{ $baganStrukturUrl }}" 
                         alt="Bagan Struktur Organisasi" 
                         class="w-full h-auto max-h-[320px] object-contain rounded-xl mx-auto transition duration-300 group-hover:scale-[1.01]">
                    
                    <a href="{{ $baganStrukturUrl }}" target="_blank" 
                       class="absolute inset-0 bg-slate-950/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl flex items-center justify-center">
                        <span class="px-4 py-2 rounded-full bg-slate-900/80 text-white text-xs font-bold shadow-lg backdrop-blur-sm flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass-plus text-emerald-400"></i> Perbesar Gambar
                        </span>
                    </a>
                </div>

                @if($hasCustomBagan)
                    <div class="pt-1 flex items-center justify-between">
                        <span class="text-[11px] text-slate-500">
                            <i class="fa-solid fa-image text-emerald-600 mr-1"></i> Gambar diunggah oleh admin
                        </span>
                        <form action="{{ route('admin.officials.delete-bagan') }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus bagan kustom ini dan kembali ke bagan standar bawaan sistem?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1">
                                <i class="fa-solid fa-rotate-left"></i> Reset ke Bagan Standar
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-[11px] text-slate-500 italic">
                        <i class="fa-solid fa-circle-info text-slate-400 mr-1"></i> Saat ini menampilkan bagan diagram SVG vektor resmi bawaan sistem.
                    </p>
                @endif
            </div>

            <!-- Kolom Form Upload Bagan Baru -->
            <div class="lg:col-span-6 space-y-4">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Unggah / Ganti Gambar Bagan
                </span>

                <form action="{{ route('admin.officials.update-bagan') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <div class="border-2 border-dashed border-slate-200 hover:border-emerald-500 rounded-2xl p-6 text-center transition bg-slate-50/50 hover:bg-emerald-50/20 relative">
                            <input type="file" 
                                   name="bagan_struktur" 
                                   id="bagan_struktur" 
                                   accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                   required
                                   @change="
                                       fileChosen = true; 
                                       fileName = $event.target.files[0].name;
                                       const reader = new FileReader();
                                       reader.onload = (e) => { filePreview = e.target.result; };
                                       reader.readAsDataURL($event.target.files[0]);
                                   "
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            
                            <template x-if="!fileChosen">
                                <div class="space-y-2 pointer-events-none">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center text-xl">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div class="text-xs font-bold text-slate-700">
                                        Pilih berkas gambar atau seret ke sini
                                    </div>
                                    <p class="text-[11px] text-slate-400">
                                        PNG, JPG, JPEG, WEBP, atau SVG (Maks. 10 MB)
                                    </p>
                                </div>
                            </template>

                            <template x-if="fileChosen">
                                <div class="space-y-3 pointer-events-none">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white mx-auto flex items-center justify-center text-lg shadow-sm">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800 truncate" x-text="fileName"></p>
                                        <span class="text-[10px] text-emerald-600 font-semibold">Siap diunggah</span>
                                    </div>
                                    <template x-if="filePreview">
                                        <div class="mt-2 max-w-xs mx-auto rounded-lg overflow-hidden border border-slate-200">
                                            <img :src="filePreview" class="h-24 w-full object-contain bg-white">
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="rounded-xl bg-amber-50/80 border border-amber-200/80 p-3 text-[11px] text-amber-900 leading-relaxed">
                        <div class="font-bold mb-1 flex items-center gap-1.5 text-amber-800">
                            <i class="fa-solid fa-lightbulb"></i> Petunjuk Gambar Bagan:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-amber-800/90">
                            <li>Disarankan gambar orientasi melebar (landscape / mendatar).</li>
                            <li>Gunakan gambar beresolusi tinggi (misal 1920x1080px atau format SVG) agar teks nama pejabat dan garis koordinasi terbaca jelas oleh pengunjung.</li>
                        </ul>
                    </div>

                    <button type="submit" 
                            class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-sm transition transform active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Gambar Bagan Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Jajaran Pejabat & Pegawai Kecamatan -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Jajaran Pejabat & Pegawai Kecamatan</h2>
            <p class="text-xs text-slate-500">Kelola daftar aparatur personal dan urutan tampilan struktur organisasi di halaman profil</p>
        </div>
        <a href="{{ route('admin.officials.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Pejabat Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden max-w-4xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 text-center">Urutan</th>
                        <th class="py-3.5 px-4">Foto</th>
                        <th class="py-3.5 px-4">Nama Lengkap</th>
                        <th class="py-3.5 px-4">Jabatan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($officials as $off)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700">{{ $off->urutan }}</td>
                            <td class="py-3.5 px-4">
                                <img src="{{ $off->foto_url }}" alt="{{ $off->nama }}" class="w-12 h-12 rounded-full object-cover shadow-sm border border-slate-200">
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                {{ $off->nama }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-emerald-700">
                                {{ $off->jabatan }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.officials.edit', $off->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.officials.destroy', $off->id) }}" method="POST" onsubmit="return confirm('Hapus pejabat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Belum ada pejabat yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
