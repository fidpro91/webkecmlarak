@extends('layouts.frontend')

@section('title', 'Beranda')

@section('content')
    <!-- 1. Hero Slider — Premium Split Layout -->
    <section class="relative overflow-hidden bg-[#3d0710]"
             x-data="{
                activeSlide: 0,
                slidesCount: {{ $sliders->count() > 0 ? $sliders->count() : 1 }},
                autoplay: null,
                progress: 0,
                progressInterval: null,
                animIn: false,
                goTo(index) {
                    this.animIn = false;
                    setTimeout(() => {
                        this.activeSlide = index;
                        this.animIn = true;
                        this.resetProgress();
                    }, 50);
                },
                next() { this.goTo((this.activeSlide + 1) % this.slidesCount); },
                prev() { this.goTo((this.activeSlide - 1 + this.slidesCount) % this.slidesCount); },
                resetProgress() {
                    clearInterval(this.progressInterval);
                    this.progress = 0;
                    this.progressInterval = setInterval(() => {
                        this.progress += (100 / 60);
                        if (this.progress >= 100) this.progress = 100;
                    }, 100);
                },
                startAutoplay() {
                    this.autoplay = setInterval(() => { this.next(); }, 6000);
                },
                stopAutoplay() { clearInterval(this.autoplay); clearInterval(this.progressInterval); }
             }"
             x-init="animIn = true; resetProgress(); startAutoplay();"
             @mouseenter="stopAutoplay()"
             @mouseleave="startAutoplay(); resetProgress();">

        <!-- Decorative Background Circles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-[#7a1220]/30 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 w-64 h-64 rounded-full bg-amber-900/10 blur-2xl"></div>
        </div>

        <div class="relative min-h-[580px] sm:min-h-[640px] lg:min-h-[680px] flex flex-col lg:flex-row">

            @if($sliders->count() > 0)
                @foreach($sliders as $index => $slider)

                    <!-- Slide Image Panel (right side, full bleed) -->
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition-opacity ease-out duration-700"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity ease-in duration-400"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full z-0">
                        <!-- Image covers right half on lg, full on mobile with dark overlay -->
                        <img src="{{ $slider->gambar_url }}"
                             alt="{{ $slider->judul }}"
                             class="absolute inset-0 w-full h-full object-cover object-center">
                        <!-- Gradient: strong maroon on left, transparent on right -->
                        <div class="absolute inset-0 bg-gradient-to-r from-[#3d0710] via-[#5c0c16]/85 to-[#5c0c16]/20 lg:via-[#3d0710]/90 lg:to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#3d0710]/60 via-transparent to-transparent"></div>
                    </div>

                @endforeach

                <!-- Text Content Panel — overlaid on all slides -->
                <div class="relative z-10 flex flex-col justify-center w-full lg:w-[55%] xl:w-1/2 min-h-[580px] sm:min-h-[640px] lg:min-h-[680px] px-6 sm:px-10 lg:px-16 xl:px-20 py-16 lg:py-0">

                    @foreach($sliders as $index => $slider)
                        <div x-show="activeSlide === {{ $index }}"
                             class="absolute inset-0 flex flex-col justify-center px-6 sm:px-10 lg:px-16 xl:px-20 py-16 lg:py-0">

                            <!-- Badge -->
                            <div x-show="animIn"
                                 x-transition:enter="transition ease-out duration-500 delay-100"
                                 x-transition:enter-start="opacity-0 -translate-y-3"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="inline-flex items-center gap-2 self-start px-4 py-1.5 mb-5 rounded-full border border-amber-400/40 bg-amber-400/10 backdrop-blur-sm text-amber-300 text-[11px] font-bold uppercase tracking-widest shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <i class="fa-solid fa-landmark"></i> Portal Resmi Kecamatan Mlarak
                            </div>



                            <!-- Heading -->
                            <h1 x-show="animIn"
                                x-transition:enter="transition ease-out duration-600 delay-200"
                                x-transition:enter-start="opacity-0 translate-y-6"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight text-white mb-4 max-w-lg drop-shadow">
                                {{ $slider->judul }}
                            </h1>

                            <!-- Animated divider line -->
                            <div x-show="animIn"
                                 x-transition:enter="transition ease-out duration-700 delay-300"
                                 x-transition:enter-start="opacity-0 scale-x-0"
                                 x-transition:enter-end="opacity-100 scale-x-100"
                                 class="w-16 h-1 rounded-full bg-gradient-to-r from-amber-400 to-rose-400 mb-5 origin-left"></div>

                            <!-- Description -->
                            @if($slider->deskripsi)
                            <p x-show="animIn"
                               x-transition:enter="transition ease-out duration-600 delay-350"
                               x-transition:enter-start="opacity-0 translate-y-4"
                               x-transition:enter-end="opacity-100 translate-y-0"
                               class="text-sm sm:text-base text-rose-100/85 leading-relaxed mb-8 max-w-md line-clamp-3">
                                {{ $slider->deskripsi }}
                            </p>
                            @else
                            <div class="mb-8"></div>
                            @endif

                            <!-- CTA Buttons -->
                            <div x-show="animIn"
                                 x-transition:enter="transition ease-out duration-600 delay-[450ms]"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="flex flex-wrap gap-3">
                                <a href="{{ route('services.index') }}"
                                   class="group relative inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-white font-bold text-sm shadow-lg shadow-amber-900/40 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-xl overflow-hidden">
                                    <span class="absolute inset-0 bg-gradient-to-r from-amber-400/0 to-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                    <i class="fa-solid fa-handshake-angle"></i>
                                    Layanan PATEN
                                </a>
                                <a href="{{ route('profile') }}"
                                   class="group inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm border border-white/25 hover:border-white/50 backdrop-blur-sm transition-all duration-200 transform hover:-translate-y-1">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Profil Wilayah
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- Fallback -->
                <div class="relative z-10 flex items-center justify-center w-full min-h-[580px] text-white px-6">
                    <div class="text-center max-w-xl">
                        <div class="w-20 h-20 rounded-3xl bg-white/10 flex items-center justify-center text-4xl mx-auto mb-6">
                            <i class="fa-solid fa-landmark text-amber-400"></i>
                        </div>
                        <h1 class="text-4xl font-extrabold mb-3">Selamat Datang di Kecamatan Mlarak</h1>
                        <p class="text-rose-100/90 text-sm leading-relaxed">Pusat Pelayanan Administrasi Terpadu & Keterbukaan Informasi Publik Ponorogo</p>
                    </div>
                </div>
            @endif

        </div><!-- end flex row -->

        <!-- Bottom Controls Bar -->
        <div class="absolute bottom-0 left-0 right-0 z-20 flex items-center justify-between px-6 sm:px-10 lg:px-16 py-4 bg-gradient-to-t from-[#3d0710]/80 to-transparent">

            <!-- Slide Tabs -->
            <div class="flex items-center gap-2">
                @foreach($sliders as $index => $slider)
                    <button @click="goTo({{ $index }})"
                            :class="activeSlide === {{ $index }}
                                ? 'bg-amber-400 w-10 shadow-md shadow-amber-400/30'
                                : 'bg-white/25 hover:bg-white/50 w-3'"
                            class="h-3 rounded-full transition-all duration-400"
                            aria-label="Slide {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>

            <!-- Progress Bar -->
            <div class="hidden sm:flex items-center gap-3">
                <div class="w-28 h-0.5 bg-white/20 rounded-full overflow-hidden">
                    <div :style="'width:' + progress + '%'"
                         class="h-full bg-amber-400 rounded-full transition-all duration-100 ease-linear"></div>
                </div>
                <span class="text-white/40 text-[10px] font-mono tracking-widest" x-text="String(activeSlide + 1).padStart(2, '0') + ' / ' + String(slidesCount).padStart(2, '0')"></span>
            </div>

            <!-- Arrow Navigation -->
            <div class="flex items-center gap-2">
                <button @click="prev()"
                        class="w-10 h-10 rounded-full border border-white/20 bg-white/10 hover:bg-[#851624] text-white backdrop-blur-sm flex items-center justify-center transition-all duration-200 hover:scale-110 hover:border-transparent"
                        aria-label="Previous">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button @click="next()"
                        class="w-10 h-10 rounded-full border border-white/20 bg-white/10 hover:bg-[#851624] text-white backdrop-blur-sm flex items-center justify-center transition-all duration-200 hover:scale-110 hover:border-transparent"
                        aria-label="Next">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Right Edge: Scroll indicator (decorative) -->
        <div class="absolute right-6 top-1/2 -translate-y-1/2 z-20 hidden lg:flex flex-col items-center gap-2">
            @foreach($sliders as $index => $slider)
                <button @click="goTo({{ $index }})"
                        :class="activeSlide === {{ $index }} ? 'bg-amber-400 h-8' : 'bg-white/20 hover:bg-white/40 h-3'"
                        class="w-0.5 rounded-full transition-all duration-400"
                        aria-label="Slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>

    </section>

    <!-- 2. Statistics Counter Strip (Fase 8 & 17) -->
    <section class="relative -mt-10 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 p-6 sm:p-8 grid grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex items-center gap-4 border-r border-slate-100 last:border-0 pr-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-tree-city"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $villagesCount }}</p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500">Desa Binaan</p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-r border-slate-100 last:border-0 pr-4">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($totalPopulation, 0, ',', '.') }}</p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500">Total Penduduk (Jiwa)</p>
                </div>
            </div>

            <div class="flex items-center gap-4 border-r border-slate-100 last:border-0 pr-4">
                <div class="w-14 h-14 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $servicesCount }}</p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500">Layanan PATEN</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl flex-shrink-0">
                    <i class="fa-solid fa-file-shield"></i>
                </div>
                <div>
                    <p class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $downloadsCount }}</p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500">Dokumen Publik</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Sambutan Camat & Sekilas Profil (Fase 16) -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Camat Photo Card -->
                <div class="lg:col-span-5 flex flex-col items-center">
                    <div class="relative w-full max-w-sm">
                        <div class="absolute -inset-2 bg-gradient-to-tr from-emerald-600 to-teal-500 rounded-3xl blur-lg opacity-30 transform -rotate-2"></div>
                        <div class="relative bg-white p-3 rounded-3xl shadow-xl border border-slate-100">
                            <img src="{{ $siteSettings['foto_camat'] ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80' }}" 
                                 alt="Camat Mlarak" 
                                 class="w-full h-96 object-cover rounded-2xl shadow-inner">
                            <div class="p-4 text-center">
                                <h3 class="font-extrabold text-lg text-slate-900">{{ $siteSettings['nama_camat'] ?? 'Drs. H. Bambang Sujarwo, M.Si' }}</h3>
                                <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Camat Mlarak Ponorogo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Text Description -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-quote-left"></i> Sambutan Pimpinan
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Membangun Bersama, Melayani dengan Ikhlas & Transparan
                    </h2>
                    <p class="text-slate-600 leading-relaxed text-sm sm:text-base">
                        {{ $siteSettings['sambutan_camat'] ?? 'Selamat datang di Portal Resmi Sistem Informasi Pemerintah Kecamatan Mlarak, Kabupaten Ponorogo.' }}
                    </p>
                    <div class="p-5 rounded-2xl bg-white border-l-4 border-emerald-600 shadow-sm space-y-2">
                        <p class="font-bold text-slate-900 text-sm">Visi Kecamatan Mlarak:</p>
                        <p class="text-xs text-slate-600 italic">"{{ $siteSettings['visi'] ?? 'Terwujudnya Pelayanan Publik Kecamatan Mlarak yang Profesional, Transparan, Akuntabel, dan Berkelanjutan Menuju Ponorogo Hebat.' }}"</p>
                    </div>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('profile') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm transition">
                            <span>Baca Profil Selengkapnya</span> <i class="fa-solid fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('villages.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold text-sm transition">
                            <i class="fa-solid fa-map-location-dot text-emerald-600"></i> Jelajahi 15 Desa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Layanan PATEN Unggulan (Fase 9) -->
    <section class="py-16 sm:py-20 bg-white border-y border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block mb-1">Pelayanan Prima</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Layanan Administrasi Terpadu (PATEN)</h2>
                    <p class="text-slate-500 text-sm mt-1">Kemudahan pengurusan perizinan dan kependudukan untuk seluruh warga Mlarak.</p>
                </div>
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition">
                    Lihat Semua Layanan <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($services as $srv)
                    <div class="group bg-slate-50 hover:bg-white p-6 rounded-2xl border border-slate-200 hover:border-emerald-500/50 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl mb-4 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-file-signature"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-2 group-hover:text-emerald-700 transition">
                                {{ $srv->nama_layanan }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($srv->syarat), 110) }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center justify-between">
                            <a href="{{ route('services.index') }}#srv-{{ $srv->id }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                                Syarat & Alur <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                            @if($srv->file_sop)
                                <button onclick="openPreview('{{ route('downloads.preview', 1) }}', 'SOP {{ $srv->nama_layanan }}')" 
                                        class="text-xs text-slate-500 hover:text-rose-600 flex items-center gap-1" title="Lihat SOP PDF">
                                    <i class="fa-solid fa-file-pdf"></i> SOP
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 5. Berita & Informasi Terkini (Fase 7) -->
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block mb-1">Kabar Kecamatan</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Berita & Agenda Terkini</h2>
                    <p class="text-slate-500 text-sm mt-1">Publikasi transparansi kegiatan pemerintah dan dinamika kemasyarakatan.</p>
                </div>
                <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition">
                    Semua Berita <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestArticles as $article)
                    <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group">
                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            <img src="{{ $article->gambar_url }}" 
                                 alt="{{ $article->judul }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @if($article->category)
                                <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-emerald-600/90 text-white text-[11px] font-semibold tracking-wide backdrop-blur-sm shadow">
                                    {{ $article->category->nama }}
                                </span>
                            @endif
                        </div>
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 text-xs text-slate-400 mb-2 font-medium">
                                    <span><i class="fa-regular fa-calendar text-emerald-600 mr-1"></i> {{ $article->published_at ? $article->published_at->isoFormat('D MMMM Y') : $article->created_at->isoFormat('D MMMM Y') }}</span>
                                    <span>•</span>
                                    <span><i class="fa-regular fa-user text-emerald-600 mr-1"></i> {{ $article->author->name ?? 'Admin' }}</span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-lg leading-snug group-hover:text-emerald-700 transition line-clamp-2">
                                    <a href="{{ route('articles.show', $article->slug) }}">
                                        {{ $article->judul }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                    {{ Str::limit(strip_tags($article->konten), 130) }}
                                </p>
                            </div>
                            <div class="pt-5 mt-4 border-t border-slate-100">
                                <a href="{{ route('articles.show', $article->slug) }}" 
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 group-hover:text-emerald-800 transition">
                                    <span>Baca Selengkapnya</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-1 transition"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Galeri Foto & Kegiatan (Fase 10) -->
    <section class="py-16 sm:py-20 bg-white border-t border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-emerald-700 font-bold text-xs uppercase tracking-widest block mb-1">Dokumentasi</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Galeri Dokumentasi Kegiatan</h2>
                    <p class="text-slate-500 text-sm mt-1">Potret dinamika pembangunan, kebudayaan, dan pengabdian di Kecamatan Mlarak.</p>
                </div>
                <a href="{{ route('galleries.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800 transition">
                    Lihat Galeri Lengkap <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($galleries as $gal)
                    <div class="group relative rounded-xl overflow-hidden h-44 bg-slate-100 shadow-sm cursor-pointer"
                         @if($gal->tipe === 'foto')
                             onclick="openLightbox('{{ $gal->file_url }}', '{{ addslashes($gal->judul) }}')"
                         @else
                             onclick="window.open('{{ $gal->file }}', '_blank')"
                         @endif>
                        @if($gal->tipe === 'foto')
                            <img src="{{ $gal->file_url }}" alt="{{ $gal->judul }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-slate-900 flex flex-col items-center justify-center text-white p-2">
                                <i class="fa-brands fa-youtube text-red-500 text-3xl mb-1"></i>
                                <span class="text-[10px] text-center line-clamp-2">{{ $gal->judul }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col justify-end p-3 text-white">
                            <p class="text-xs font-bold truncate">{{ $gal->judul }}</p>
                            <span class="text-[10px] text-emerald-300 capitalize">{{ $gal->tipe }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 7. Call To Action Pengaduan (Fase 13) -->
    <section class="py-16 bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-2xl space-y-3 text-center lg:text-left">
                    <span class="inline-block px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-bold uppercase tracking-wider">
                        Keterbukaan Informasi & Pengaduan Warga
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Punya Pertanyaan, Aspirasi, atau Keluhan?</h2>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Pemerintah Kecamatan Mlarak membuka ruang selebar-lebarnya bagi seluruh masyarakat untuk menyampaikan saran, pengaduan pelayanan, atau permohonan informasi secara cepat dan transparan.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('contact.index') }}" 
                       class="px-8 py-3.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-sm shadow-xl transition transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pengaduan Sekarang
                    </a>
                    @if(!empty($siteSettings['whatsapp']))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteSettings['whatsapp']) }}" target="_blank"
                           class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-sm backdrop-blur-sm transition inline-flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-lg"></i> Chat WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
