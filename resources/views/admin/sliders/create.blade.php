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

            <!-- Pilihan Tampilan Slider -->
            <div x-data="{ selectedLayout: '{{ old('layout_style', 'classic') }}', focalPoint: '{{ old('focal_point', 'center') }}' }" class="space-y-3 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tampilan Slider (Layout Style) *
                    </label>
                    <p class="text-xs text-slate-500">
                        Pilih tata letak overlay dan posisi foto sesuai dengan karakteristik foto yang diunggah.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <!-- Opsi 1: Klasik -->
                    <label class="relative flex flex-col p-3 rounded-2xl border-2 cursor-pointer transition select-none"
                           :class="selectedLayout === 'classic' ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="layout_style" value="classic" x-model="selectedLayout" class="sr-only">
                        
                        <!-- Mini Preview CSS / SVG -->
                        <div class="w-full h-20 rounded-xl overflow-hidden relative mb-2.5 bg-slate-800 border border-slate-700 flex items-center">
                            <div class="absolute inset-0 bg-gradient-to-tr from-slate-700 to-slate-600"></div>
                            <svg class="absolute bottom-0 right-0 w-24 h-14 text-slate-500/50" viewBox="0 0 100 60" fill="currentColor">
                                <path d="M0,60 L20,35 L40,50 L65,20 L100,60 Z"></path>
                            </svg>
                            <div class="absolute inset-0" style="background: linear-gradient(90deg, #3d0710 0%, #3d0710 32%, rgba(61,7,16,0.7) 48%, rgba(61,7,16,0.25) 62%, transparent 75%);"></div>
                            <div class="relative z-10 p-2 space-y-1 w-2/3">
                                <div class="w-12 h-1.5 rounded-full bg-amber-400"></div>
                                <div class="w-20 h-2 rounded bg-white"></div>
                                <div class="w-16 h-1 rounded bg-rose-200/80"></div>
                            </div>
                        </div>

                        <div class="flex items-start justify-between gap-1 mb-1">
                            <span class="font-bold text-xs text-slate-900">Klasik</span>
                            <span class="w-4 h-4 rounded-full border flex items-center justify-center text-[10px]"
                                  :class="selectedLayout === 'classic' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 text-transparent'">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-tight">
                            Overlay marun pekat dari kiri, untuk foto pemandangan/kegiatan.
                        </p>
                    </label>

                    <!-- Opsi 2: Gradien Lembut -->
                    <label class="relative flex flex-col p-3 rounded-2xl border-2 cursor-pointer transition select-none"
                           :class="selectedLayout === 'gradient_soft' ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="layout_style" value="gradient_soft" x-model="selectedLayout" class="sr-only">
                        
                        <!-- Mini Preview CSS / SVG -->
                        <div class="w-full h-20 rounded-xl overflow-hidden relative mb-2.5 bg-slate-800 border border-slate-700 flex items-center">
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-700 via-slate-600 to-slate-500"></div>
                            <svg class="absolute bottom-0 right-1 w-28 h-16 text-slate-400/50" viewBox="0 0 100 60" fill="currentColor">
                                <circle cx="70" cy="22" r="14"></circle>
                                <path d="M40,60 C40,42 55,38 70,38 C85,38 100,42 100,60 Z"></path>
                            </svg>
                            <div class="absolute inset-0" style="background: linear-gradient(90deg, #3d0710 0%, rgba(61,7,16,0.95) 24%, rgba(61,7,16,0.55) 36%, transparent 52%);"></div>
                            <div class="relative z-10 p-2 space-y-1 w-1/2">
                                <div class="w-10 h-1.5 rounded-full bg-amber-400"></div>
                                <div class="w-16 h-2 rounded bg-white"></div>
                                <div class="w-12 h-1 rounded bg-rose-200/80"></div>
                            </div>
                        </div>

                        <div class="flex items-start justify-between gap-1 mb-1">
                            <span class="font-bold text-xs text-slate-900">Gradien Lembut</span>
                            <span class="w-4 h-4 rounded-full border flex items-center justify-center text-[10px]"
                                  :class="selectedLayout === 'gradient_soft' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 text-transparent'">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-tight">
                            Overlay dipersempit dan foto digeser ke kanan.
                        </p>
                    </label>

                    <!-- Opsi 3: Split Miring -->
                    <label class="relative flex flex-col p-3 rounded-2xl border-2 cursor-pointer transition select-none"
                           :class="selectedLayout === 'split_diagonal' ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-slate-200 hover:border-slate-300 bg-white'">
                        <input type="radio" name="layout_style" value="split_diagonal" x-model="selectedLayout" class="sr-only">
                        
                        <!-- Mini Preview CSS / SVG -->
                        <div class="w-full h-20 rounded-xl overflow-hidden relative mb-2.5 bg-slate-800 border border-slate-700 flex items-center">
                            <div class="absolute inset-0 bg-slate-600"></div>
                            <svg class="absolute inset-y-1 right-2 w-20 h-18 text-amber-200/60" viewBox="0 0 100 80" fill="currentColor">
                                <circle cx="35" cy="25" r="10"></circle>
                                <path d="M20,60 C20,45 28,40 35,40 C42,40 50,45 50,60 Z"></path>
                                <circle cx="65" cy="25" r="10"></circle>
                                <path d="M50,60 C50,45 58,40 65,40 C72,40 80,45 80,60 Z"></path>
                            </svg>
                            <div class="absolute inset-y-0 left-0 w-[55%] bg-[#3d0710]" style="clip-path: polygon(0 0, 100% 0, 84% 100%, 0 100%);">
                                <div class="p-2 space-y-1">
                                    <div class="w-8 h-1.5 rounded-full bg-amber-400"></div>
                                    <div class="w-14 h-2 rounded bg-white"></div>
                                    <div class="w-10 h-1 rounded bg-rose-200/80"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start justify-between gap-1 mb-1">
                            <span class="font-bold text-xs text-slate-900">Split Miring</span>
                            <span class="w-4 h-4 rounded-full border flex items-center justify-center text-[10px]"
                                  :class="selectedLayout === 'split_diagonal' ? 'border-emerald-600 bg-emerald-600 text-white' : 'border-slate-300 text-transparent'">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        </div>
                        <span class="inline-block px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-100 text-amber-800 border border-amber-200 mb-1 w-max">
                            Direkomendasikan untuk foto bersama
                        </span>
                        <p class="text-[11px] text-slate-500 leading-tight">
                            Panel teks marun solid dengan tepi miring, foto utuh di kanan.
                        </p>
                    </label>
                </div>

                <!-- Opsi Fokus Foto (Focal Point) -->
                <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-t border-slate-100 text-xs text-slate-600">
                    <span class="font-medium text-[11px] text-slate-500 flex items-center gap-1.5">
                        <i class="fa-solid fa-crosshairs text-slate-400"></i> Posisi Fokus Foto:
                    </span>
                    <div class="inline-flex rounded-xl bg-slate-100 p-1 text-xs">
                        <label class="px-2.5 py-1 rounded-lg cursor-pointer transition font-medium select-none"
                               :class="focalPoint === 'left' ? 'bg-white text-emerald-800 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900'">
                            <input type="radio" name="focal_point" value="left" x-model="focalPoint" class="sr-only"> Kiri
                        </label>
                        <label class="px-2.5 py-1 rounded-lg cursor-pointer transition font-medium select-none"
                               :class="focalPoint === 'center' ? 'bg-white text-emerald-800 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900'">
                            <input type="radio" name="focal_point" value="center" x-model="focalPoint" class="sr-only"> Tengah
                        </label>
                        <label class="px-2.5 py-1 rounded-lg cursor-pointer transition font-medium select-none"
                               :class="focalPoint === 'right' ? 'bg-white text-emerald-800 shadow-sm font-bold' : 'text-slate-600 hover:text-slate-900'">
                            <input type="radio" name="focal_point" value="right" x-model="focalPoint" class="sr-only"> Kanan
                        </label>
                    </div>
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
