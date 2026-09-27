@extends('layouts.frontend')

@section('title', 'Profil Kecamatan Mlarak')

@section('content')
    <!-- Page Header Banner -->
    <div class="bg-[#5c0c16] py-16 sm:py-20 text-white relative border-b border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <nav class="flex justify-center sm:justify-start items-center gap-2 text-xs text-amber-300 mb-3 font-semibold uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span>/</span>
                <span class="text-rose-200">Profil</span>
            </nav>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight">Profil Pemerintah Kecamatan Mlarak</h1>
            <p class="mt-2 text-sm sm:text-base text-rose-100/90 max-w-2xl">
                Sejarah, Visi, Misi, Struktur Organisasi, dan Gambaran Umum Wilayah Kecamatan Mlarak, Kabupaten Ponorogo.
            </p>
        </div>
    </div>

    <!-- Main Profile Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-16">
        
        <!-- Video Profil Kecamatan Mlarak (YouTube Embed) -->
        <section class="space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-600 uppercase tracking-widest">
                        <i class="fa-brands fa-youtube text-sm"></i> Dokumenter Resmi
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        Video Profil Kecamatan Mlarak
                    </h2>
                    <p class="text-slate-500 text-xs sm:text-sm">
                        Tayangan audio-visual selayang pandang potensi wilayah, sejarah, kearifan lokal, dan komitmen pelayanan publik.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if(!empty($siteSettings['youtube']))
                        <a href="{{ $siteSettings['youtube'] }}" target="_blank" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold border border-rose-200 transition">
                            <i class="fa-brands fa-youtube"></i> Kanal YouTube
                        </a>
                    @endif
                    @auth
                        <a href="{{ route('admin.settings.edit') }}#video-profil" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold border border-amber-200 transition"
                           title="Ubah URL Video Profil">
                            <i class="fa-solid fa-pen-to-square"></i> Edit Video
                        </a>
                    @endauth
                </div>
            </div>

            <!-- YouTube Video Player Card -->
            <div class="bg-slate-950 rounded-3xl p-3 sm:p-5 shadow-2xl border border-slate-800">
                <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-inner bg-slate-900">
                    <iframe class="w-full h-full rounded-2xl" 
                            src="{{ $youtubeEmbedUrl ?? 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?rel=0' }}" 
                            title="Video Profil Kecamatan Mlarak" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerpolicy="strict-origin-when-cross-origin" 
                            allowfullscreen>
                    </iframe>
                </div>
            </div>
        </section>

        <!-- 1. Visi & Misi Section -->
        <section id="visimisi" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch scroll-mt-24">
            <!-- Visi Card -->
            <div class="lg:col-span-5 bg-gradient-to-br from-emerald-800 to-teal-900 rounded-3xl p-8 text-white shadow-xl flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-amber-300 text-2xl mb-6">
                        <i class="fa-solid fa-compass"></i>
                    </div>
                    <span class="text-emerald-300 font-bold text-xs uppercase tracking-widest block mb-2">Visi Utama</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug">
                        Terwujudnya Pelayanan Publik yang Profesional, Transparan, & Akuntabel
                    </h2>
                    <p class="mt-4 text-emerald-100 text-sm leading-relaxed">
                        {{ $siteSettings['visi'] ?? 'Terwujudnya Pelayanan Publik Kecamatan Mlarak yang Profesional, Transparan, Akuntabel, dan Berkelanjutan Menuju Ponorogo Hebat.' }}
                    </p>
                </div>
                <div class="pt-6 mt-6 border-t border-emerald-700/50 flex items-center gap-3 text-xs text-emerald-200">
                    <i class="fa-solid fa-award text-amber-400 text-lg"></i>
                    <span>Menuju Ponorogo Hebat & Berkeadaban</span>
                </div>
            </div>

            <!-- Misi Card -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block">Arah Kebijakan</span>
                            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Misi Kecamatan Mlarak</h2>
                        </div>
                    </div>
                    <div class="space-y-4 text-slate-600 text-sm leading-relaxed">
                        @php
                            $misiItems = explode("\n", $siteSettings['misi'] ?? "1. Meningkatkan kualitas tata kelola birokrasi pemerintahan kecamatan yang responsif, adaptif, dan akuntabel.\n2. Mengoptimalkan pelayanan administrasi terpadu kecamatan (PATEN) berbasis digital demi kepuasan masyarakat.\n3. Mendorong percepatan pembangunan desa dan pemberdayaan ekonomi masyarakat berbasis potensi pertanian & UMKM.\n4. Memperkuat sinergi ketentraman, ketertiban umum, serta pelestarian nilai budaya dan kearifan lokal Ponorogo.");
                        @endphp
                        @foreach($misiItems as $misi)
                            @if(trim($misi))
                                <div class="flex items-start gap-3">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <p>{{ preg_replace('/^[0-9]+\.\s*/', '', trim($misi)) }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Sejarah Kecamatan Section -->
        <section id="sejarah" class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-sm scroll-mt-24">
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="text-center space-y-2">
                    <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest">Napak Tilas & Histori</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sejarah Kecamatan Mlarak</h2>
                    <div class="w-16 h-1 bg-emerald-600 rounded-full mx-auto mt-2"></div>
                </div>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed text-sm sm:text-base space-y-4">
                    <p>
                        {{ $siteSettings['sejarah'] ?? 'Kecamatan Mlarak memiliki sejarah panjang sebagai salah satu wilayah penyangga peradaban spiritual dan kebudayaan di Kabupaten Ponorogo. Memiliki lahan agraris subur beririgasi teknis dan tradisi gotong royong yang kuat, Mlarak dikenal secara luas melalui perpaduan nilai-nilai religius dan kekayaan seni budaya Ponorogo.' }}
                    </p>
                    <p>
                        Keberadaan Pondok Modern Darussalam Gontor di Desa Gontor yang berdiri sejak tahun 1926 kian mengukuhkan posisi Mlarak di kancah nasional dan global sebagai kawah candradimuka pendidikan kepesantrenan terkemuka. Bersama 15 desa yang guyub rukun, masyarakat Mlarak terus melestarikan kearifan lokal seperti seni Reyog Ponorogo, tradisi bersih desa, dan semangat gotong royong sambatan yang senantiasa terjaga.
                    </p>
                </div>
            </div>
        </section>

        <!-- 3. Struktur Organisasi & Pejabat -->
        <section id="struktur" class="space-y-10 scroll-mt-24" x-data="{ showModal: false, zoomLevel: 1 }">
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest">Aparatur & Tata Kelola</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Struktur Organisasi & Pejabat</h2>
                <p class="text-slate-500 text-sm">Bagan hierarki kelembagaan serta jajaran aparatur sipil negara yang berdedikasi melayani masyarakat Kecamatan Mlarak.</p>
            </div>

            <!-- Card Bagan Struktur Organisasi -->
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/90 shadow-sm space-y-6">
                <!-- Header Card Bagan -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid fa-sitemap"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Bagan Struktur Organisasi</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">Resmi</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Diagram hierarki kelembagaan dan garis koordinasi Pemerintah Kecamatan Mlarak</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" 
                                @click="showModal = true; zoomLevel = 1;" 
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm transform active:scale-95">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                            <span>Perbesar Bagan</span>
                        </button>
                        <a href="{{ $baganStrukturUrl ?? asset('images/bagan-struktur-organisasi.svg') }}" 
                           target="_blank" 
                           download="bagan-struktur-organisasi-kecamatan-mlarak"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold transition">
                            <i class="fa-solid fa-arrow-down-to-bracket text-emerald-600"></i>
                            <span>Unduh</span>
                        </a>
                        @auth
                            <a href="{{ route('admin.officials.index') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 text-xs font-semibold transition"
                               title="Kelola Gambar Bagan via Panel Admin">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit Bagan</span>
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Preview Area Gambar Bagan -->
                <div class="relative bg-slate-50 rounded-2xl border border-slate-200/80 p-3 sm:p-5 overflow-hidden">
                    <div class="relative group cursor-zoom-in flex items-center justify-center min-h-[300px]"
                         @click="showModal = true; zoomLevel = 1;">
                        <img src="{{ $baganStrukturUrl ?? asset('images/bagan-struktur-organisasi.svg') }}" 
                             alt="Bagan Struktur Organisasi Pemerintah Kecamatan Mlarak" 
                             class="w-full max-h-[620px] object-contain rounded-xl shadow-sm transition-transform duration-300 group-hover:scale-[1.008]">
                        
                        <!-- Hover Overlay Hint -->
                        <div class="absolute inset-0 bg-slate-950/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                            <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-slate-900/85 backdrop-blur-md text-white text-xs font-bold shadow-xl transform translate-y-1 group-hover:translate-y-0 transition">
                                <i class="fa-solid fa-expand text-emerald-400"></i> Klik untuk Tampilan Layar Penuh (Zoom)
                            </span>
                        </div>
                    </div>

                    <!-- Footer Info Bar -->
                    <div class="mt-4 pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-slate-500">
                        <span class="flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-info text-emerald-600"></i>
                            Klik gambar atau tombol "Perbesar Bagan" untuk membaca nama jabatan dan tugas secara detail.
                        </span>
                        <span class="text-slate-400 font-medium">
                            Kecamatan Mlarak &bull; Kabupaten Ponorogo
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lightbox Modal Fullscreen untuk Bagan Struktur -->
            <div x-show="showModal" 
                 x-cloak
                 @keydown.escape.window="showModal = false"
                 class="fixed inset-0 z-50 flex flex-col justify-between bg-slate-950/90 backdrop-blur-md p-3 sm:p-6"
                 style="display: none;">
                
                <!-- Modal Top Control Bar -->
                <div class="flex items-center justify-between bg-slate-900/90 border border-slate-800 text-white rounded-2xl px-4 py-3 shadow-2xl z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid fa-sitemap"></i>
                        </div>
                        <div>
                            <h4 class="text-xs sm:text-sm font-bold text-white">Bagan Struktur Organisasi Kecamatan Mlarak</h4>
                            <p class="text-[10px] text-slate-400">Gunakan kontrol zoom atau scroll untuk menelusuri detail bagan</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Zoom Controls -->
                        <div class="flex items-center bg-slate-800 rounded-xl p-1 border border-slate-700">
                            <button type="button" 
                                    @click="zoomLevel = Math.max(0.6, zoomLevel - 0.2)"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition"
                                    title="Perkecil (-)">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>
                            <span class="text-xs font-mono px-2 text-emerald-400 min-w-[48px] text-center" x-text="Math.round(zoomLevel * 100) + '%'">100%</span>
                            <button type="button" 
                                    @click="zoomLevel = Math.min(2.5, zoomLevel + 0.2)"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-white hover:bg-slate-700 transition"
                                    title="Perbesar (+)">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                            <button type="button" 
                                    @click="zoomLevel = 1"
                                    class="px-2 py-1 text-[11px] font-semibold text-slate-400 hover:text-white hover:bg-slate-700 rounded-lg ml-1 transition"
                                    title="Reset ke Ukuran Asli">
                                Reset
                            </button>
                        </div>

                        <!-- Open Original -->
                        <a href="{{ $baganStrukturUrl ?? asset('images/bagan-struktur-organisasi.svg') }}" 
                           target="_blank" 
                           class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold border border-slate-700 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Tab Baru
                        </a>

                        <!-- Close Button -->
                        <button type="button" 
                                @click="showModal = false" 
                                class="w-9 h-9 rounded-xl bg-rose-500/20 text-rose-400 hover:bg-rose-500 hover:text-white flex items-center justify-center transition border border-rose-500/30 ml-2"
                                title="Tutup Modal (Esc)">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Center Image Viewport -->
                <div class="flex-1 overflow-auto my-3 flex items-center justify-center p-2 rounded-2xl bg-slate-900/40 border border-slate-800/80">
                    <div class="transition-transform duration-200 ease-out origin-center"
                         :style="'transform: scale(' + zoomLevel + ')'">
                        <img src="{{ $baganStrukturUrl ?? asset('images/bagan-struktur-organisasi.svg') }}" 
                             alt="Detail Bagan Struktur Organisasi" 
                             class="max-w-none max-h-[82vh] object-contain rounded-xl shadow-2xl mx-auto">
                    </div>
                </div>

                <!-- Modal Bottom Info -->
                <div class="text-center text-[11px] text-slate-400 py-1">
                    Tekan tombol <kbd class="px-1.5 py-0.5 rounded bg-slate-800 text-slate-200 border border-slate-700 font-mono text-[10px]">ESC</kbd> untuk menutup tampilan layar penuh
                </div>
            </div>

            <!-- Bagian 2: Jajaran Pejabat & Pegawai -->
            <div class="space-y-6 pt-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center text-lg shadow-sm">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Jajaran Pejabat & Pegawai Struktural</h3>
                        <p class="text-xs text-slate-500">Profil aparatur pimpinan dan kepala seksi pelaksana pelayanan umum</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($officials as $official)
                        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-lg transition text-center p-6 flex flex-col items-center group">
                            <div class="relative w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-emerald-50 shadow-inner group-hover:scale-105 transition duration-300">
                                <img src="{{ $official->foto_url }}" 
                                     alt="{{ $official->nama }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 mb-2">
                                Urutan #{{ $official->urutan }}
                            </span>
                            <h3 class="font-extrabold text-slate-900 text-base leading-snug">{{ $official->nama }}</h3>
                            <p class="text-xs font-semibold text-emerald-700 mt-1 uppercase tracking-wider">{{ $official->jabatan }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 4. Peta & Letak Geografis -->
        <section class="bg-slate-900 text-white rounded-3xl overflow-hidden shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <div class="lg:col-span-5 p-8 sm:p-12 flex flex-col justify-center space-y-6">
                    <div>
                        <span class="text-emerald-400 font-bold text-xs uppercase tracking-widest block mb-2">Geografis Wilayah</span>
                        <h2 class="text-3xl font-extrabold tracking-tight">Peta & Akses Kantor Kecamatan</h2>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Kecamatan Mlarak terletak di bagian tenggara Kabupaten Ponorogo. Memiliki luas wilayah sekitar 40,28 km² dan berbatasan dengan Kecamatan Siman di utara, Kecamatan Sambit di timur, Kecamatan Jetis di selatan, dan Kecamatan Ponorogo Kota di barat laut.
                    </p>
                    <div class="space-y-3 text-xs text-slate-300">
                        <p class="flex items-center gap-3"><i class="fa-solid fa-location-dot text-emerald-400 text-base"></i> {{ $siteSettings['alamat'] ?? 'Jl. Raya Mlarak - Sambit No. 12, Mlarak, Ponorogo' }}</p>
                        <p class="flex items-center gap-3"><i class="fa-solid fa-phone text-emerald-400 text-base"></i> {{ $siteSettings['telepon'] ?? '(0352) 311029' }}</p>
                        <p class="flex items-center gap-3"><i class="fa-solid fa-envelope text-emerald-400 text-base"></i> {{ $siteSettings['email'] ?? 'kecamatan.mlarak@ponorogo.go.id' }}</p>
                    </div>
                    <div>
                        <a href="https://maps.google.com" target="_blank" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 font-semibold text-xs text-white transition">
                            <i class="fa-solid fa-diamond-turn-right"></i> Buka Rute di Google Maps
                        </a>
                    </div>
                </div>
                <div class="lg:col-span-7 h-96 lg:h-auto min-h-[380px] bg-slate-800">
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
        </section>

    </div>
@endsection
