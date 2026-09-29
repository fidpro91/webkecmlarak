@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page_title', 'Pengaturan Umum Website & Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Konfigurasi Identitas & Informasi Kontak</h2>
            <p class="text-xs text-slate-500">Perubahan akan langsung berdampak pada seluruh halaman publik portal</p>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-sm">
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Bagian 1: Identitas Instansi -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-4 bg-emerald-600 rounded"></span> Identitas Instansi
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="instansi_nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Instansi *</label>
                        <input type="text" name="instansi_nama" id="instansi_nama" value="{{ old('instansi_nama', $settings['instansi_nama'] ?? '') }}" required
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="kabupaten" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kabupaten / Daerah *</label>
                        <input type="text" name="kabupaten" id="kabupaten" value="{{ old('kabupaten', $settings['kabupaten'] ?? '') }}" required
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label for="slogan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Slogan / Motto</label>
                    <input type="text" name="slogan" id="slogan" value="{{ old('slogan', $settings['slogan'] ?? '') }}"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="deskripsi_singkat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Singkat (Footer & Meta SEO)</label>
                    <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="3"
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">{{ old('deskripsi_singkat', $settings['deskripsi_singkat'] ?? '') }}</textarea>
                </div>

                <!-- Logo Instansi -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-shield-halved text-emerald-600 mr-1"></i> Logo Resmi Instansi (Lambang Daerah)
                    </label>
                    <div class="flex flex-col sm:flex-row items-center gap-5">
                        <div class="w-20 h-20 bg-white p-2 rounded-xl border border-slate-200 shadow-sm flex items-center justify-center shrink-0">
                            <img src="{{ \App\Models\Setting::logoUrl() }}" 
                                 alt="Logo Instansi Saat Ini" 
                                 class="max-h-full max-w-full object-contain">
                        </div>
                        <div class="flex-1 w-full space-y-2">
                            <input type="file" name="logo" id="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
                                <span>Logo standar aktif: <code class="text-emerald-700 font-mono">public/images/logoponorogo.png</code></span>
                                @if(!empty($settings['logo']) && $settings['logo'] !== 'images/logoponorogo.png')
                                    <label class="inline-flex items-center gap-1.5 text-rose-600 hover:text-rose-700 cursor-pointer font-semibold">
                                        <input type="checkbox" name="hapus_logo" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Reset ke logo bawaan (logoponorogo.png)</span>
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik & Visitor Counter -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-chart-line text-indigo-600 mr-1"></i> Pengaturan Angka Awal Visitor / Pengunjung
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                        <div>
                            <input type="number" name="base_visitor_count" id="base_visitor_count" 
                                   value="{{ old('base_visitor_count', $settings['base_visitor_count'] ?? 14850) }}" min="0"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-800">
                            <p class="text-[11px] text-slate-500 mt-1">Angka dasar awal pengunjung yang akan bertambah otomatis saat website dikunjungi.</p>
                        </div>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 text-xs">
                            <span class="text-slate-500 block text-[11px]">Total Counter Saat Ini:</span>
                            <span class="text-base font-extrabold text-indigo-600">{{ number_format(\App\Models\Visitor::totalVisitorsCount(), 0, ',', '.') }} Pengunjung</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Kontak & Operasional -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-4 bg-emerald-600 rounded"></span> Kontak & Pelayanan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="telepon" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Telepon Kantor</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $settings['telepon'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="whatsapp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor WhatsApp Pelayanan</label>
                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $settings['whatsapp'] ?? '') }}"
                               placeholder="Contoh: 081234567890"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email Resmi</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $settings['email'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap Kantor</label>
                    <input type="text" name="alamat" id="alamat" value="{{ old('alamat', $settings['alamat'] ?? '') }}"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="jam_kerja" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Kerja Pelayanan</label>
                        <input type="text" name="jam_kerja" id="jam_kerja" value="{{ old('jam_kerja', $settings['jam_kerja'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="maps_embed" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">URL Google Maps (Embed src)</label>
                        <input type="text" name="maps_embed" id="maps_embed" value="{{ old('maps_embed', $settings['maps_embed'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-[11px]">
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Media Sosial -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-4 bg-emerald-600 rounded"></span> Akun Media Sosial
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label for="facebook" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Facebook URL</label>
                        <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $settings['facebook'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="instagram" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Instagram URL</label>
                        <input type="url" name="instagram" id="instagram" value="{{ old('instagram', $settings['instagram'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="youtube" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kanal YouTube Resmi</label>
                        <input type="url" name="youtube" id="youtube" value="{{ old('youtube', $settings['youtube'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>
                </div>

                <div id="video-profil" class="pt-2">
                    <label for="video_profil_youtube" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        <i class="fa-brands fa-youtube text-rose-600 mr-1"></i> URL Video Profil YouTube (Tampil di Halaman Profil)
                    </label>
                    <input type="url" name="video_profil_youtube" id="video_profil_youtube" 
                           value="{{ old('video_profil_youtube', $settings['video_profil_youtube'] ?? '') }}"
                           placeholder="Contoh: https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                           class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 font-mono text-[11px]">
                    <p class="mt-1.5 text-[11px] text-slate-500">
                        Mendukung tautan standar (watch?v=...), link pendek (youtu.be/...), atau embed. Video ini disematkan sebagai iframe di atas Visi & Misi pada halaman Profil Kecamatan.
                    </p>
                </div>
            </div>

            <!-- Bagian 4: Camat & Sambutan -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-4 bg-emerald-600 rounded"></span> Pimpinan & Sambutan Camat
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_camat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Camat & Gelar</label>
                        <input type="text" name="nama_camat" id="nama_camat" value="{{ old('nama_camat', $settings['nama_camat'] ?? '') }}"
                               class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label for="foto_camat_upload" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ganti Foto Camat</label>
                        <div class="flex items-center gap-3 mb-2">
                            <img src="{{ \App\Models\Setting::fotoCamatUrl() }}" alt="Foto Camat Saat Ini" class="w-12 h-12 rounded-xl object-cover border border-slate-200 shadow-sm">
                            <span class="text-[11px] text-slate-500">Foto saat ini (terkoneksi dengan Data Jajaran Pejabat)</span>
                        </div>
                        <input type="file" name="foto_camat_upload" id="foto_camat_upload" accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>
                </div>

                <div>
                    <label for="sambutan_camat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Teks Sambutan Camat</label>
                    <textarea name="sambutan_camat" id="sambutan_camat" rows="4"
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('sambutan_camat', $settings['sambutan_camat'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- Bagian 5: Visi, Misi & Sejarah -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-4 bg-emerald-600 rounded"></span> Visi, Misi & Sejarah Wilayah
                </h3>

                <div>
                    <label for="visi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Visi Kecamatan</label>
                    <textarea name="visi" id="visi" rows="2"
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('visi', $settings['visi'] ?? '') }}</textarea>
                </div>

                <div>
                    <label for="misi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Misi Kecamatan (1 baris per poin misi)</label>
                    <textarea name="misi" id="misi" rows="5"
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('misi', $settings['misi'] ?? '') }}</textarea>
                </div>

                <div>
                    <label for="sejarah" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sejarah Singkat Kecamatan</label>
                    <textarea name="sejarah" id="sejarah" rows="6"
                              class="w-full text-xs rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 leading-relaxed">{{ old('sejarah', $settings['sejarah'] ?? '') }}</textarea>
                </div>
            </div>

            <!-- Bagian 6: Bagan Struktur Organisasi -->
            <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center gap-2">
                    <span class="w-2 h-4 bg-emerald-600 rounded"></span> Bagan Struktur Organisasi Kecamatan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tampilan Saat Ini</label>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden bg-slate-50 p-2 shadow-inner">
                            <img src="{{ !empty($settings['bagan_struktur_organisasi']) ? $settings['bagan_struktur_organisasi'] : asset('images/bagan-struktur-organisasi.svg') }}" 
                                 alt="Bagan Struktur Organisasi" 
                                 class="w-full h-auto max-h-48 object-contain rounded-xl">
                        </div>
                        <div class="mt-2 text-center">
                            <a href="{{ !empty($settings['bagan_struktur_organisasi']) ? $settings['bagan_struktur_organisasi'] : asset('images/bagan-struktur-organisasi.svg') }}" 
                               target="_blank" 
                               class="text-[11px] font-semibold text-emerald-700 hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Gambar Penuh
                            </a>
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-4">
                        <div>
                            <label for="bagan_struktur_organisasi_upload" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ganti Desain Bagan Struktur (Format SVG)</label>
                            <input type="file" name="bagan_struktur_organisasi_upload" id="bagan_struktur_organisasi_upload" accept=".svg,image/svg+xml"
                                   class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <p class="mt-1.5 text-[11px] text-slate-500">
                                Format wajib: <strong>Vektor SVG (.svg)</strong> &bull; Maksimal 5 MB. Sistem akan secara otomatis memetakan nama pejabat ke dalam elemen SVG Anda.
                            </p>
                        </div>

                        @if(!empty($settings['bagan_struktur_organisasi']))
                            <div class="pt-2">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-rose-600">
                                    <input type="checkbox" name="hapus_bagan_struktur" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span>Hapus bagan kustom ini dan reset ke bagan diagram standar sistem</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="px-8 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider shadow-md transition transform active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Semua Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
