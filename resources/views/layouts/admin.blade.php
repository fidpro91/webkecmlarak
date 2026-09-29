<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ $siteSettings['instansi_nama'] ?? 'Kecamatan Mlarak' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoponorogo.png') }}">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans flex min-h-screen" x-data="{ sidebarOpen: false }">

    <!-- Sidebar Backdrop for Mobile -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden">
    </div>

    <!-- Admin Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 ease-in-out shadow-2xl lg:shadow-none border-r border-slate-800">
        
        <!-- Sidebar Brand Header -->
        <div class="h-20 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800/80">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ \App\Models\Setting::logoUrl() }}" 
                     alt="Logo Ponorogo" class="h-10 w-auto object-contain">
                <div>
                    <span class="block text-xs font-semibold text-emerald-400 uppercase tracking-wider">PANEL ADMIN</span>
                    <span class="block text-base font-extrabold text-white tracking-tight">KEC. MLARAK</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Menu -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-7 custom-scrollbar">
            
            <!-- Group 1: Utama -->
            <div>
                <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Ringkasan</span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-900/30' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center text-base {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <!-- Group 2: Tampilan & Halaman -->
            <div>
                <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Tampilan & Navigasi</span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.sliders.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.sliders.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-images w-5 text-center {{ request()->routeIs('admin.sliders.*') ? 'text-white' : 'text-teal-400' }}"></i>
                        <span>Slider Beranda</span>
                    </a>
                    <a href="{{ route('admin.menus.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.menus.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-bars-staggered w-5 text-center {{ request()->routeIs('admin.menus.*') ? 'text-white' : 'text-teal-400' }}"></i>
                        <span>Menu Dinamis & Halaman</span>
                    </a>
                </div>
            </div>

            <!-- Group 3: Publikasi & Konten -->
            <div>
                <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Publikasi & Informasi</span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.articles.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.articles.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-newspaper w-5 text-center {{ request()->routeIs('admin.articles.*') ? 'text-white' : 'text-sky-400' }}"></i>
                        <span>Berita & Kegiatan</span>
                    </a>
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-tag w-5 text-center {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-sky-400' }}"></i>
                        <span>Kategori Berita</span>
                    </a>
                    <a href="{{ route('admin.galleries.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.galleries.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-photo-film w-5 text-center {{ request()->routeIs('admin.galleries.*') ? 'text-white' : 'text-sky-400' }}"></i>
                        <span>Galeri Foto & Video</span>
                    </a>
                </div>
            </div>

            <!-- Group 4: Layanan & Kewilayahan -->
            <div>
                <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Layanan & Wilayah</span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.villages.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.villages.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-tree-city w-5 text-center {{ request()->routeIs('admin.villages.*') ? 'text-white' : 'text-amber-400' }}"></i>
                        <span>Data 15 Desa</span>
                    </a>
                    <a href="{{ route('admin.services.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.services.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-hand-holding-heart w-5 text-center {{ request()->routeIs('admin.services.*') ? 'text-white' : 'text-amber-400' }}"></i>
                        <span>Layanan Publik (PATEN)</span>
                    </a>
                    <a href="{{ route('admin.officials.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.officials.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-user-tie w-5 text-center {{ request()->routeIs('admin.officials.*') ? 'text-white' : 'text-amber-400' }}"></i>
                        <span>Struktur Pejabat</span>
                    </a>
                </div>
            </div>

            <!-- Group 5: Transparansi & Unduhan -->
            <div>
                <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Transparansi Berkas</span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.downloads.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.downloads.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-file-pdf w-5 text-center {{ request()->routeIs('admin.downloads.*') ? 'text-white' : 'text-violet-400' }}"></i>
                        <span>Berkas Unduhan</span>
                    </a>
                    <a href="{{ route('admin.download-categories.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.download-categories.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-folder w-5 text-center {{ request()->routeIs('admin.download-categories.*') ? 'text-white' : 'text-violet-400' }}"></i>
                        <span>Kategori Berkas</span>
                    </a>
                </div>
            </div>

            <!-- Group 6: Komunikasi & Pengaturan -->
            <div>
                <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-slate-400">Interaksi & Sistem</span>
                <div class="mt-2 space-y-1">
                    <a href="{{ route('admin.messages.index') }}" 
                       class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.messages.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-inbox w-5 text-center {{ request()->routeIs('admin.messages.*') ? 'text-white' : 'text-rose-400' }}"></i>
                            <span>Pesan Pengaduan</span>
                        </div>
                        @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                            <span class="px-2 py-0.5 text-xs font-bold bg-rose-500 text-white rounded-full">
                                {{ $unreadMessagesCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.settings.edit') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-sliders w-5 text-center {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-indigo-400' }}"></i>
                        <span>Pengaturan Website</span>
                    </a>
                    @if(Auth::user()->isSuperAdmin())
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-emerald-600 text-white shadow-md' : 'hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-users-gear w-5 text-center {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-pink-400' }}"></i>
                            <span>Manajemen User Admin</span>
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <!-- Sidebar User Footer -->
        <div class="p-4 bg-slate-950 border-t border-slate-800/80 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <img src="{{ Auth::user()->photo_url }}" 
                     alt="{{ Auth::user()->name }}" 
                     class="w-10 h-10 rounded-full object-cover border-2 border-emerald-500">
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <span class="inline-block text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full {{ Auth::user()->isSuperAdmin() ? 'bg-amber-400/20 text-amber-300' : 'bg-emerald-400/20 text-emerald-300' }}">
                        {{ Auth::user()->role === 'super_admin' ? 'Super Admin' : 'Admin Operator' }}
                    </span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Keluar / Logout" class="text-slate-400 hover:text-rose-400 p-2 transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
        
        <!-- Admin Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-8 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 hidden sm:block">Panel Kontrol Sistem Informasi Kecamatan Mlarak</p>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('home') }}" target="_blank" 
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200 transition">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span class="hidden sm:inline">Lihat Website Publik</span>
                </a>

                <div class="h-6 w-px bg-slate-200"></div>

                <!-- Profile Dropdown -->
                <div class="flex items-center gap-3">
                    <img src="{{ Auth::user()->photo_url }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-9 h-9 rounded-full object-cover border border-slate-200">
                    <div class="hidden md:block text-right">
                        <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 sm:px-8 mt-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm mb-4">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-xl"></i>
                        <div>
                            <p class="font-semibold text-sm">Operasi Berhasil</p>
                            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:text-emerald-700"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm mb-4">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-rose-600 text-xl"></i>
                        <div>
                            <p class="font-semibold text-sm">Terjadi Kesalahan</p>
                            <p class="text-xs text-rose-700">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div x-data="{ show: true }" x-show="show" class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-semibold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-600"></i> Mohon periksa kembali input Anda:
                        </p>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Main Body -->
        <main class="flex-1 p-4 sm:p-8">
            @yield('content')
        </main>

        <!-- Admin Footer -->
        <footer class="py-4 px-8 border-t border-slate-200 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2 bg-white">
            <p>&copy; {{ date('Y') }} Sistem Informasi <strong>Kecamatan Mlarak</strong>. All rights reserved.</p>
            <p>Versi 1.0 (Laravel 11 + Tailwind CSS + MySQL)</p>
        </footer>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
