<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Resmi Pemerintah') - {{ $siteSettings['instansi_nama'] ?? 'Kecamatan Mlarak' }}</title>
    <meta name="description" content="@yield('meta_description', $siteSettings['deskripsi_singkat'] ?? 'Portal Informasi Resmi Pemerintah Kecamatan Mlarak, Kabupaten Ponorogo, Jawa Timur.')">
    @hasSection('meta_keywords')
        <meta name="keywords" content="@yield('meta_keywords')">
    @endif
    @yield('seo_meta')
    <link rel="icon" type="image/png" href="{{ asset('images/logoponorogo.png') }}">

    <!-- Fonts & Scripts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen selection:bg-emerald-600 selection:text-white">

    @php
        $isHome = request()->routeIs('home');
    @endphp

    <!-- 1. Top Bar -->
    <div class="bg-[#45080e] text-rose-100 text-xs py-2 border-b border-[#5c0c16]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="flex flex-wrap items-center gap-4 text-center md:text-left">
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-clock text-amber-400"></i>
                    {{ $siteSettings['jam_kerja'] ?? 'Senin - Kamis: 07.30 - 15.30 | Jumat: 07.30 - 14.30' }}
                </span>
                <span class="hidden sm:inline text-rose-900">|</span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-phone text-amber-400"></i>
                    {{ $siteSettings['telepon'] ?? '(0352) 311029' }}
                </span>
                <span class="hidden md:inline text-rose-900">|</span>
                <span class="hidden md:inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-envelope text-amber-400"></i>
                    {{ $siteSettings['email'] ?? 'kecamatan.mlarak@ponorogo.go.id' }}
                </span>
            </div>
            <div class="flex items-center gap-3">
                @if(!empty($siteSettings['facebook']))
                    <a href="{{ $siteSettings['facebook'] }}" target="_blank" class="hover:text-amber-400 transition" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                @endif
                @if(!empty($siteSettings['instagram']))
                    <a href="{{ $siteSettings['instagram'] }}" target="_blank" class="hover:text-amber-400 transition" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                @endif
                @if(!empty($siteSettings['youtube']))
                    <a href="{{ $siteSettings['youtube'] }}" target="_blank" class="hover:text-amber-400 transition" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                @endif
                @if(!empty($siteSettings['whatsapp']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['whatsapp']) }}" target="_blank" class="hover:text-amber-400 transition" title="WhatsApp Pelayanan"><i class="fa-brands fa-whatsapp"></i></a>
                @endif
                <span class="text-rose-900">|</span>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1 font-semibold text-amber-300 hover:text-amber-200">
                        <i class="fa-solid fa-gauge-high"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 hover:text-white transition">
                        <i class="fa-solid fa-lock text-amber-400"></i> Login Admin
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- 2. Main Navigation Bar -->
    <header x-data="{ 
                mobileMenuOpen: false, 
                atTop: true,
                scrolled: false,
                mouseNearTop: false,
                isHovering: false,
                scrollUp: false,
                lastScrollY: 0,
                mouseHideTimer: null,
                isTouch: false,
                get visible() { 
                    return this.atTop || this.mouseNearTop || this.isHovering || this.mobileMenuOpen || this.scrollUp; 
                },
                init() {
                    this.isTouch = window.matchMedia && window.matchMedia('(hover: none)').matches;
                    this.lastScrollY = window.pageYOffset || 0;
                    this.atTop = (this.lastScrollY <= 80);
                    this.scrolled = !this.atTop;
                    
                    window.addEventListener('mousemove', (e) => {
                        if (e.clientY <= 60) {
                            this.mouseNearTop = true;
                            clearTimeout(this.mouseHideTimer);
                        } else if (e.clientY > 120 && !this.isHovering) {
                            clearTimeout(this.mouseHideTimer);
                            this.mouseHideTimer = setTimeout(() => {
                                if (!this.isHovering) {
                                    this.mouseNearTop = false;
                                }
                            }, 600);
                        }
                    });
                },
                onScroll() {
                    const currentY = window.pageYOffset || 0;
                    this.atTop = (currentY <= 80);
                    this.scrolled = !this.atTop;
                    if (currentY > this.lastScrollY + 5) {
                        // Scrolling down: auto-hide header
                        this.scrollUp = false;
                        this.mouseNearTop = false;
                        if (!this.atTop) {
                            this.mobileMenuOpen = false;
                        }
                    } else if (this.isTouch && this.lastScrollY - currentY > 15) {
                        // On touch/mobile devices, scrolling up reveals header
                        this.scrollUp = true;
                    }
                    this.lastScrollY = currentY <= 0 ? 0 : currentY;
                }
            }" 
            x-init="init()"
            @mouseenter="isHovering = true; clearTimeout(mouseHideTimer);"
            @mouseleave="isHovering = false; if (!atTop) { mouseHideTimer = setTimeout(() => { mouseNearTop = false; }, 400); }"
            @scroll.window="onScroll()"
            :class="{
                'translate-y-0 opacity-100 shadow-md bg-white/95 backdrop-blur-md py-2.5 pointer-events-auto border-b border-slate-100': visible,
                '-translate-y-full opacity-0 pointer-events-none py-0 border-none': !visible
            }"
            class="transition-all duration-300 transform sticky top-0 z-50 translate-y-0 opacity-100 shadow-md bg-white/95 backdrop-blur-md py-2.5 pointer-events-auto border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <!-- Brand / Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ \App\Models\Setting::logoUrl() }}" 
                         alt="Logo Ponorogo" 
                         class="h-11 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105">
                    <div>
                        <span class="block text-xs font-semibold text-emerald-700 uppercase tracking-widest leading-none">
                            {{ $siteSettings['kabupaten'] ?? 'KABUPATEN PONOROGO' }}
                        </span>
                        <span class="block text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-tight group-hover:text-emerald-700 transition">
                            KECAMATAN MLARAK
                        </span>
                        <span class="hidden sm:block text-[11px] text-slate-500 font-medium leading-none">
                            Pelayanan Terpadu & Informasi Publik
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <nav class="hidden lg:flex items-center space-x-1 font-medium text-sm text-slate-700">
                    @if(isset($navMenus) && $navMenus->count() > 0)
                        @foreach($navMenus as $menu)
                            @if($menu->children->count() > 0)
                                <!-- Dropdown Menu -->
                                <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                    <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition"
                                            :class="open ? 'text-emerald-700 bg-emerald-50' : ''">
                                        <span>{{ $menu->nama_menu }}</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-1"
                                         class="absolute left-0 mt-1 w-56 rounded-xl bg-white shadow-xl border border-slate-100 py-2 z-50">
                                        @foreach($menu->children as $child)
                                            <a href="{{ $child->target_url }}" 
                                               class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                                {{ $child->nama_menu }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $menu->target_url }}" 
                                   class="px-3 py-2 rounded-lg hover:text-emerald-700 hover:bg-emerald-50 transition {{ request()->url() == $menu->target_url ? 'text-emerald-700 bg-emerald-50 font-semibold' : '' }}">
                                    {{ $menu->nama_menu }}
                                </a>
                            @endif
                        @endforeach
                    @else
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Beranda</a>
                        <a href="{{ route('profile') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Profil</a>
                        <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Berita</a>
                        <a href="{{ route('villages.index') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Data Desa</a>
                        <a href="{{ route('services.index') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Layanan</a>
                        <a href="{{ route('downloads.index') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Download</a>
                        <a href="{{ route('galleries.index') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Galeri</a>
                        <a href="{{ route('contact.index') }}" class="px-3 py-2 rounded-lg hover:text-emerald-700 transition">Kontak</a>
                    @endif

                    <a href="{{ route('contact.index') }}" 
                       class="ml-3 inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-semibold px-4 py-2 rounded-lg shadow-sm hover:shadow transition transform active:scale-95 text-xs uppercase tracking-wider">
                        <i class="fa-solid fa-bullhorn"></i> Pengaduan
                    </a>
                </nav>

                <!-- Mobile Hamburger Button -->
                <div class="lg:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            type="button" 
                            class="p-2 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-slate-100 transition focus:outline-none"
                            aria-label="Toggle menu">
                        <i class="fa-solid fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-xl" x-show="mobileMenuOpen"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden bg-white border-b border-slate-200 px-4 pt-2 pb-6 space-y-1 shadow-lg"
             @click.away="mobileMenuOpen = false">
            @if(isset($navMenus))
                @foreach($navMenus as $menu)
                    @if($menu->children->count() > 0)
                        <div x-data="{ subOpen: false }" class="py-1">
                            <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                                <span>{{ $menu->nama_menu }}</span>
                                <i class="fa-solid fa-chevron-down text-xs transition" :class="subOpen ? 'rotate-180 text-emerald-700' : ''"></i>
                            </button>
                            <div x-show="subOpen" class="pl-4 space-y-1 mt-1 border-l-2 border-emerald-500 ml-3">
                                @foreach($menu->children as $child)
                                    <a href="{{ $child->target_url }}" class="block px-3 py-1.5 text-sm text-slate-600 hover:text-emerald-700">
                                        {{ $child->nama_menu }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $menu->target_url }}" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                            {{ $menu->nama_menu }}
                        </a>
                    @endif
                @endforeach
            @endif
            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('contact.index') }}" class="w-full text-center block bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-lg shadow">
                    <i class="fa-solid fa-bullhorn mr-2"></i> Layanan Pengaduan Warga
                </a>
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    @if(session('success') || session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                    <div>
                        <p class="font-semibold text-sm">Berhasil!</p>
                        <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl"></i>
                    <div>
                        <p class="font-semibold text-sm">Perhatian!</p>
                        <p class="text-xs text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
            </div>
        @endif
    </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- 3. Footer -->
    <footer class="bg-[#5c0c16] text-rose-100/90 pt-16 pb-8 border-t border-[#7a1220]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-[#7a1220]/80">
                <!-- Col 1: Instansi -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ \App\Models\Setting::logoUrl() }}" 
                             alt="Logo Ponorogo" 
                             class="h-12 w-auto object-contain">
                        <div>
                            <span class="block text-xs uppercase tracking-wider text-amber-300 font-semibold">Pemerintah Kabupaten</span>
                            <span class="block text-lg font-bold text-white tracking-tight">Kecamatan Mlarak</span>
                        </div>
                    </div>
                    <p class="text-xs text-rose-100/80 leading-relaxed">
                        {{ $siteSettings['deskripsi_singkat'] ?? 'Portal layanan publik dan sistem keterbukaan informasi terpadu Kecamatan Mlarak, Kabupaten Ponorogo, Jawa Timur.' }}
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        @if(!empty($siteSettings['facebook']))
                            <a href="{{ $siteSettings['facebook'] }}" target="_blank" class="w-9 h-9 rounded-lg bg-[#45080e] hover:bg-[#851624] text-rose-200 hover:text-white flex items-center justify-center transition border border-[#7a1220]/60" title="Facebook">
                                <i class="fa-brands fa-facebook-f text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['instagram']))
                            <a href="{{ $siteSettings['instagram'] }}" target="_blank" class="w-9 h-9 rounded-lg bg-[#45080e] hover:bg-[#851624] text-rose-200 hover:text-white flex items-center justify-center transition border border-[#7a1220]/60" title="Instagram">
                                <i class="fa-brands fa-instagram text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['youtube']))
                            <a href="{{ $siteSettings['youtube'] }}" target="_blank" class="w-9 h-9 rounded-lg bg-[#45080e] hover:bg-[#851624] text-rose-200 hover:text-white flex items-center justify-center transition border border-[#7a1220]/60" title="YouTube">
                                <i class="fa-brands fa-youtube text-sm"></i>
                            </a>
                        @endif
                        @if(!empty($siteSettings['whatsapp']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['whatsapp']) }}" target="_blank" class="w-9 h-9 rounded-lg bg-[#45080e] hover:bg-[#851624] text-rose-200 hover:text-white flex items-center justify-center transition border border-[#7a1220]/60" title="WhatsApp Pelayanan">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Navigasi Cepat -->
                <div>
                    <h3 class="text-white text-base font-bold mb-4 flex items-center gap-2">
                        <span class="w-2 h-4 bg-amber-400 rounded"></span> Tautan Cepat
                    </h3>
                    <ul class="space-y-2 text-xs">
                        <li><a href="{{ route('home') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Beranda</a></li>
                        <li><a href="{{ route('profile') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Profil & Visi Misi</a></li>
                        <li><a href="{{ route('villages.index') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Data 15 Desa</a></li>
                        <li><a href="{{ route('services.index') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Layanan PATEN</a></li>
                        <li><a href="{{ route('articles.index') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Berita & Kegiatan</a></li>
                        <li><a href="{{ route('downloads.index') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Unduhan Dokumen SOP</a></li>
                        <li><a href="{{ route('statistics.index') }}" class="text-rose-100/90 hover:text-amber-300 transition flex items-center gap-2"><i class="fa-solid fa-angle-right text-[10px] text-amber-400"></i> Statistik Kependudukan</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kontak & Jam Layanan -->
                <div>
                    <h3 class="text-white text-base font-bold mb-4 flex items-center gap-2">
                        <span class="w-2 h-4 bg-amber-400 rounded"></span> Jam & Kontak
                    </h3>
                    <ul class="space-y-3 text-xs text-rose-100/80">
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot text-amber-400 mt-1"></i>
                            <span>{{ $siteSettings['alamat'] ?? 'Jl. Raya Mlarak - Sambit No. 12, Mlarak, Ponorogo' }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-phone text-amber-400"></i>
                            <span>{{ $siteSettings['telepon'] ?? '(0352) 311029' }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope text-amber-400"></i>
                            <span>{{ $siteSettings['email'] ?? 'kecamatan.mlarak@ponorogo.go.id' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fa-solid fa-clock text-amber-400 mt-1"></i>
                            <span>{{ $siteSettings['jam_kerja'] ?? 'Senin - Kamis: 07.30 - 15.30 | Jumat: 07.30 - 14.30' }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Peta Wilayah -->
                <div>
                    <h3 class="text-white text-base font-bold mb-4 flex items-center gap-2">
                        <span class="w-2 h-4 bg-amber-400 rounded"></span> Lokasi Kantor
                    </h3>
                    <div class="rounded-xl overflow-hidden border border-[#7a1220] shadow h-40 bg-[#45080e]">
                        <iframe src="{{ $siteSettings['maps_embed'] ?? 'https://maps.google.com' }}" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <a href="https://maps.google.com" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-amber-300 hover:text-amber-200 mt-2 font-medium">
                        <i class="fa-solid fa-diamond-turn-right"></i> Petunjuk Arah Google Maps
                    </a>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-rose-200/70 gap-4">
                <p>&copy; {{ date('Y') }} <strong>Pemerintah Kecamatan Mlarak</strong>. Hak Cipta Dilindungi Undang-Undang.</p>
                <p class="flex items-center gap-2">
                    <span>Kabupaten Ponorogo, Jawa Timur</span>
                    <span>•</span>
                    <a href="{{ route('login') }}" class="hover:text-amber-300 transition">Akses Administrator</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Global Modal: PDF Preview (Fase 12) -->
    <div id="pdf-preview-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/80 backdrop-blur-sm transition-opacity" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-3 sm:p-6">
            <div class="relative w-full max-w-5xl rounded-2xl bg-white shadow-2xl overflow-hidden flex flex-col h-[90vh] border border-slate-200">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 bg-slate-900 text-white border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-rose-600 text-white flex items-center justify-center font-bold text-xs">PDF</span>
                        <div>
                            <h3 id="pdf-preview-title" class="text-sm sm:text-base font-bold text-white truncate max-w-md sm:max-w-xl">
                                Pratinjau Dokumen PDF
                            </h3>
                            <p class="text-[11px] text-slate-400">Sistem Informasi Kecamatan Mlarak</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a id="pdf-preview-download-btn" href="#" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center gap-1.5 transition">
                            <i class="fa-solid fa-download"></i> <span class="hidden sm:inline">Unduh Berkas</span>
                        </a>
                        <button onclick="closePreview()" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- Iframe Viewer Body -->
                <div class="flex-1 w-full bg-slate-100 relative">
                    <iframe id="pdf-preview-iframe" class="w-full h-full border-0" src="" title="PDF Viewer"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Modal: Image Lightbox (Fase 10) -->
    <div id="image-lightbox-modal" class="hidden fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 transition-opacity" role="dialog">
        <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/80 hover:text-white text-2xl p-2 z-10 transition">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="max-w-4xl max-h-[85vh] flex flex-col items-center">
            <img id="lightbox-img" src="" alt="Perbesar" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl">
            <p id="lightbox-caption" class="text-white/90 text-sm mt-4 text-center font-medium px-4"></p>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
