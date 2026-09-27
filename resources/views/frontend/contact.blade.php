@extends('layouts.frontend')

@section('title', 'Kontak & Pengaduan Warga')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#5c0c16] py-16 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-2 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Kontak</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Hubungi Kami & Layanan Pengaduan</h1>
            <p class="mt-2 text-sm text-rose-100/90 max-w-xl">
                Sampaikan aspirasi, saran, pertanyaan, atau keluhan pelayanan publik secara langsung kepada aparatur Kecamatan Mlarak.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left Column: Form Pengaduan -->
            <div class="lg:col-span-7 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div>
                    <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block mb-1">Formulir Resmi</span>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kirim Pesan / Pengaduan</h2>
                    <p class="text-xs text-slate-500 mt-1">Data Anda akan dijaga kerahasiaannya dan ditindaklanjuti oleh petugas terkait.</p>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap *</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                   placeholder="Masukkan nama lengkap Anda"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 @error('nama') border-rose-500 @enderror">
                            @error('nama')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   placeholder="nama@email.com"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-rose-500 @enderror">
                            @error('email')
                                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Subjek -->
                    <div>
                        <label for="subjek" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subjek / Perihal *</label>
                        <input type="text" name="subjek" id="subjek" value="{{ old('subjek') }}" required
                               placeholder="Contoh: Pengaduan Layanan Rekomendasi KTP Desa Candi"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 @error('subjek') border-rose-500 @enderror">
                        @error('subjek')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pesan -->
                    <div>
                        <label for="pesan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Isi Pesan / Pengaduan *</label>
                        <textarea name="pesan" id="pesan" rows="5" required
                                  placeholder="Uraikan permohonan informasi atau keluhan pelayanan Anda secara jelas dan santun..."
                                  class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 @error('pesan') border-rose-500 @enderror">{{ old('pesan') }}</textarea>
                        @error('pesan')
                            <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-md transition transform active:scale-95 inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pesan Sekarang
                    </button>
                </form>
            </div>

            <!-- Right Column: Info & Maps -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Info Kantor Card -->
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm space-y-5">
                    <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                        <span class="w-2.5 h-5 bg-emerald-600 rounded"></span> Kontak & Alamat Kantor
                    </h3>
                    
                    <ul class="space-y-4 text-xs text-slate-600">
                        <li class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900">Alamat Kantor:</span>
                                <span>{{ $siteSettings['alamat'] ?? 'Jl. Raya Mlarak - Sambit No. 12, Mlarak, Kabupaten Ponorogo, Jawa Timur 63472' }}</span>
                            </div>
                        </li>

                        <li class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900">Telepon Resmi:</span>
                                <span>{{ $siteSettings['telepon'] ?? '(0352) 311029' }}</span>
                            </div>
                        </li>

                        <li class="flex items-center gap-4">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900">Email Kedinasan:</span>
                                <span>{{ $siteSettings['email'] ?? 'kecamatan.mlarak@ponorogo.go.id' }}</span>
                            </div>
                        </li>

                        <li class="flex items-start gap-4">
                            <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <span class="block font-bold text-slate-900">Jam Pelayanan PATEN:</span>
                                <span>{{ $siteSettings['jam_kerja'] ?? 'Senin - Kamis: 07.30 - 15.30 WIB | Jumat: 07.30 - 14.30 WIB' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Google Maps Card -->
                <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm h-64 bg-slate-100">
                    <iframe src="{{ $siteSettings['maps_embed'] ?? 'https://maps.google.com' }}" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
@endsection
