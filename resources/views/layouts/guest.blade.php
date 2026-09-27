<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Kecamatan Mlarak') }} - Login Administrator</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logoponorogo.png') }}">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-900 selection:bg-emerald-600 selection:text-white">
        <div class="min-h-screen flex flex-col justify-center items-center p-4 relative overflow-hidden">
            <!-- Background glow -->
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-emerald-700/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-teal-700/20 rounded-full blur-3xl"></div>

            <div class="w-full sm:max-w-md relative z-10 space-y-6">
                <div class="text-center space-y-3">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                        <img src="{{ \App\Models\Setting::logoUrl() }}" 
                             alt="Logo Ponorogo" class="h-16 w-auto object-contain transition-transform group-hover:scale-105">
                    </a>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-widest text-emerald-400">Pemerintah Kabupaten Ponorogo</span>
                        <h1 class="text-2xl font-extrabold text-white tracking-tight">Kecamatan Mlarak</h1>
                        <p class="text-xs text-slate-400 mt-1">Sistem Informasi & Pelayanan Administrasi Terpadu</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-2xl border border-slate-100">
                    {{ $slot }}
                </div>

                <div class="text-center">
                    <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-emerald-400 font-semibold transition">
                        &larr; Kembali ke Beranda Website Publik
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
