@extends('layouts.admin')

@section('title', 'Pengaturan Akun & Sosial Media')
@section('page_title', 'Profil Akun & Integrasi Sosial Media')

@section('content')
<div class="space-y-8 pb-12" x-data="profileSocialManager()">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-emerald-900/40 relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="relative">
                    <img src="{{ $user->photo_url }}" 
                         alt="{{ $user->name }}" 
                         class="w-20 h-20 rounded-2xl object-cover border-2 border-emerald-400 shadow-lg">
                    <span class="absolute -bottom-1 -right-1 w-5 h-5 bg-emerald-500 border-2 border-slate-900 rounded-full flex items-center justify-center text-[10px]" title="Online">
                        <i class="fa-solid fa-check text-white text-[9px]"></i>
                    </span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-white">{{ $user->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $user->isSuperAdmin() ? 'bg-amber-400/20 text-amber-300 border border-amber-400/30' : 'bg-emerald-400/20 text-emerald-300 border border-emerald-400/30' }}">
                            {{ $user->role === 'super_admin' ? 'Super Administrator' : 'Operator Kecamatan' }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-emerald-400"></i> {{ $user->email }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="openCreateModal()" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs sm:text-sm font-bold shadow-lg shadow-emerald-900/40 flex items-center gap-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus"></i>
                    <span>Hubungkan Sosial Media</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Section 1: Profil Akun & Keamanan (2 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Form Informasi Profil -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Informasi Pengguna</h3>
                    <p class="text-xs text-slate-500">Perbarui identitas dan foto akun Anda</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Ganti Foto Profil (Opsional)</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition border border-slate-200 rounded-xl p-1">
                    <p class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP. Maksimal 10MB.</p>
                    @error('photo') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold flex items-center gap-2 transition shadow-sm">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Perbarui Kata Sandi -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Ganti Kata Sandi</h3>
                    <p class="text-xs text-slate-500">Pastikan akun menggunakan sandi yang aman</p>
                </div>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('current_password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Kata Sandi Baru</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('password') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold flex items-center gap-2 transition shadow-sm">
                        <i class="fa-solid fa-key"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Section 2: Tabel Pengaturan & Autentikasi Sosial Media -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-md shadow-sky-500/20">
                    <i class="fa-solid fa-share-nodes"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-lg">Autentikasi & Integrasi Sosial Media</h3>
                    <p class="text-xs text-slate-500">Hubungkan akun sosial media resmi untuk auto-posting berita kecamatan secara otomatis</p>
                </div>
            </div>

            <button type="button" @click="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-plus"></i> Tambah Akun Baru
            </button>
        </div>

        @if($socialAccounts->isEmpty())
            <div class="text-center py-12 px-4 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200">
                <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-2xl mx-auto mb-3">
                    <i class="fa-solid fa-satellite-dish"></i>
                </div>
                <h4 class="font-bold text-slate-700 text-base">Belum Ada Akun Sosial Media Terhubung</h4>
                <p class="text-xs text-slate-500 max-w-md mx-auto mt-1 mb-5">
                    Tambahkan kredensial Telegram Channel, Facebook Page, atau Twitter/X agar berita yang Anda terbitkan bisa otomatis dibagikan secara bersamaan.
                </p>
                <button type="button" @click="openCreateModal()" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm">
                    <i class="fa-solid fa-plus mr-1.5"></i> Hubungkan Akun Pertama
                </button>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-slate-200 shadow-sm">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[10px] font-bold tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Platform</th>
                            <th class="py-3.5 px-4">Nama Akun / Target</th>
                            <th class="py-3.5 px-4">Kredensial Token</th>
                            <th class="py-3.5 px-4 text-center">Status Koneksi</th>
                            <th class="py-3.5 px-4 text-center">Default Auto-Post</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($socialAccounts as $account)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-base bg-white border border-slate-200 shadow-xs">
                                            <i class="{{ $account->platform_icon }}"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 block text-xs">{{ $account->platform_label }}</span>
                                            <span class="text-[10px] text-slate-400 capitalize">{{ $account->platform }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800">{{ $account->account_name }}</div>
                                    @if($account->account_id)
                                        <div class="text-[11px] text-slate-500 font-mono">ID: {{ $account->account_id }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5 font-mono text-[11px] text-slate-600">
                                        <i class="fa-solid fa-key text-[10px] text-slate-400"></i>
                                        <span>{{ $account->masked_token }}</span>
                                    </div>
                                    <div class="text-[10px] text-slate-400 mt-0.5">{{ $account->last_status ?? 'Siap digunakan' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" 
                                            @click="toggleAccount({{ $account->id }}, 'is_active', $el)"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $account->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $account->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $account->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button type="button" 
                                            @click="toggleAccount({{ $account->id }}, 'auto_post', $el)"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $account->auto_post ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                        <i class="fa-solid {{ $account->auto_post ? 'fa-toggle-on text-indigo-600' : 'fa-toggle-off text-slate-400' }}"></i>
                                        <span>{{ $account->auto_post ? 'Auto ON' : 'Manual' }}</span>
                                    </button>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button type="button" 
                                                @click="testAccount({{ $account->id }}, $el)"
                                                title="Uji Validitas Token"
                                                class="p-2 rounded-lg bg-slate-100 hover:bg-sky-50 text-slate-600 hover:text-sky-700 transition">
                                            <i class="fa-solid fa-vial-circle-check"></i>
                                        </button>
                                        <button type="button" 
                                                @click="openEditModal(@js($account))"
                                                title="Ubah Kredensial"
                                                class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('admin.profile.social-accounts.destroy', $account->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus integrasi akun ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Akun" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 transition">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-amber-900 text-xs flex items-start gap-3">
            <i class="fa-solid fa-circle-question text-amber-600 text-base mt-0.5"></i>
            <div>
                <p class="font-bold mb-1">Panduan Singkat Integrasi Sosial Media</p>
                <ul class="list-disc list-inside space-y-1 text-amber-800 text-[11px]">
                    <li><strong>Instagram:</strong> Hubungkan akun Instagram Business/Creator via Meta Graph API, masukkan Instagram Account ID (atau Username <code>@namapengguna</code>) dan User/Page Access Token.</li>
                    <li><strong>Telegram:</strong> Buat Bot di <code>@BotFather</code> untuk mendapatkan Bot Token, lalu masukkan username channel (contoh: <code>@kecamatan_mlarak</code>) atau Chat ID di kolom ID Akun.</li>
                    <li><strong>Facebook:</strong> Dapatkan Page ID dan Page Access Token dengan izin <code>pages_manage_posts</code> di Facebook Developers.</li>
                    <li><strong>X (Twitter):</strong> Buat App di Twitter Developer Portal dan gunakan Bearer Token untuk posting otomatis.</li>
                    <li><strong>Switcher Otomatis:</strong> Di form tambah/edit artikel, Anda dapat memilih apakah artikel yang baru diterbitkan ingin langsung diposting ke akun di atas.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah / Edit Akun Sosial Media -->
    <template x-teleport="body">
        <div x-show="modalOpen" 
             x-cloak
             @keydown.escape.window="modalOpen = false"
             class="fixed inset-0 overflow-y-auto"
             style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; min-height: 100vh; z-index: 99999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); margin: 0; padding: 0;"
             role="dialog" 
             aria-modal="true">
            
            <!-- Backdrop Dismiss Click Area -->
            <div class="fixed inset-0" 
                 @click="modalOpen = false" 
                 style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1;"></div>

            <!-- Modal Content Wrapper (Centering) -->
            <div class="min-h-full flex items-center justify-center p-4 sm:p-6" 
                 style="min-height: 100vh; position: relative; z-index: 2;">
                
                <div class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden my-8"
                     style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);"
                     @click.stop>
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4.5 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white flex items-center justify-between border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center text-sm shadow-md shadow-emerald-950/50">
                                <i class="fa-solid fa-share-nodes"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-white" x-text="isEdit ? 'Ubah Akun Sosial Media' : 'Hubungkan Akun Sosial Media'"></h4>
                                <p class="text-[11px] text-slate-400">Atur kredensial API untuk auto-posting berita</p>
                            </div>
                        </div>
                        <button type="button" @click="modalOpen = false" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800/80 transition" title="Tutup Modal">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Modal Body Form -->
                    <form :action="formAction" method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Platform <span class="text-rose-500">*</span></label>
                                <select name="platform" x-model="formData.platform" required
                                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                    <option value="telegram">Telegram (Channel / Bot)</option>
                                    <option value="facebook">Facebook Page</option>
                                    <option value="twitter">X (Twitter)</option>
                                    <option value="instagram">Instagram Business</option>
                                    <option value="whatsapp">WhatsApp Gateway</option>
                                    <option value="other">Platform Lain</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Nama Tampilan Akun <span class="text-rose-500">*</span></label>
                                <input type="text" name="account_name" x-model="formData.account_name" required placeholder="Contoh: Channel Kecamatan Mlarak"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                <span x-text="getIdLabel()">ID Akun / Channel / Chat ID</span>
                            </label>
                            <input type="text" name="account_id" x-model="formData.account_id" :placeholder="getIdPlaceholder()"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                                <span x-text="getTokenLabel()">Access Token / Bot Token</span>
                            </label>
                            <input type="text" name="access_token" x-model="formData.access_token" :placeholder="isEdit ? '(Biarkan kosong jika tidak ingin mengubah)' : 'Tempelkan token API di sini...'"
                                   class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-mono">
                        </div>

                        <!-- Opsi Tambahan (App ID / Webhook) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">App ID / API Key (Opsional)</label>
                                <input type="text" name="app_id" x-model="formData.app_id" placeholder="Opsional untuk FB/X"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Webhook / Gateway URL (Opsional)</label>
                                <input type="url" name="webhook_url" x-model="formData.webhook_url" placeholder="https://..."
                                       class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            </div>
                        </div>

                        <!-- Switches -->
                        <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" x-model="formData.is_active" class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="text-xs font-semibold text-slate-700">Aktifkan Koneksi Akun Ini</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="auto_post" value="1" x-model="formData.auto_post" class="rounded text-indigo-600 focus:ring-indigo-500">
                                <span class="text-xs font-semibold text-slate-700">Pilih Default Saat Terbitkan Berita</span>
                            </label>
                        </div>

                        <!-- Modal Actions -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type="button" @click="modalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center gap-2 transition shadow-sm">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Hubungkan Akun'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>

@push('scripts')
<script>
function profileSocialManager() {
    return {
        modalOpen: false,
        isEdit: false,
        formAction: '{{ route('admin.profile.social-accounts.store') }}',
        formData: {
            id: null,
            platform: 'telegram',
            account_name: '',
            account_id: '',
            app_id: '',
            access_token: '',
            webhook_url: '',
            is_active: true,
            auto_post: true
        },
        init() {
            this.$watch('modalOpen', value => {
                document.body.style.overflow = value ? 'hidden' : '';
            });
        },
        openCreateModal() {
            this.isEdit = false;
            this.formAction = '{{ route('admin.profile.social-accounts.store') }}';
            this.formData = {
                id: null,
                platform: 'telegram',
                account_name: '',
                account_id: '',
                app_id: '',
                access_token: '',
                webhook_url: '',
                is_active: true,
                auto_post: true
            };
            this.modalOpen = true;
        },
        openEditModal(account) {
            this.isEdit = true;
            this.formAction = '{{ url('admin/profile/social-accounts') }}/' + account.id;
            this.formData = {
                id: account.id,
                platform: account.platform,
                account_name: account.account_name,
                account_id: account.account_id || '',
                app_id: account.app_id || '',
                access_token: '',
                webhook_url: account.webhook_url || '',
                is_active: Boolean(account.is_active),
                auto_post: Boolean(account.auto_post)
            };
            this.modalOpen = true;
        },
        getIdLabel() {
            if (this.formData.platform === 'telegram') return 'Chat ID atau Username Channel (@namachannel)';
            if (this.formData.platform === 'facebook') return 'Facebook Page ID';
            if (this.formData.platform === 'instagram') return 'Instagram Account ID / Username';
            if (this.formData.platform === 'whatsapp') return 'Nomor HP / Target Group (misal: 628123...)';
            return 'ID Akun / Channel';
        },
        getIdPlaceholder() {
            if (this.formData.platform === 'telegram') return 'Contoh: @kecamatan_mlarak atau -100123456789';
            if (this.formData.platform === 'facebook') return 'Contoh: 10482910481920';
            if (this.formData.platform === 'instagram') return 'Contoh: 17841400000000';
            return 'ID target posting';
        },
        getTokenLabel() {
            if (this.formData.platform === 'telegram') return 'Telegram Bot Token';
            if (this.formData.platform === 'facebook') return 'Page Access Token';
            if (this.formData.platform === 'instagram') return 'Instagram Graph API Access Token';
            if (this.formData.platform === 'twitter') return 'Bearer Token / Access Token';
            return 'API Token / Access Token';
        },
        async toggleAccount(id, field, el) {
            try {
                const res = await fetch(`{{ url('admin/profile/social-accounts') }}/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ field: field })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                }
            } catch (err) {
                alert('Gagal mengubah status: ' + err.message);
            }
        },
        async testAccount(id, el) {
            const originalHtml = el.innerHTML;
            el.innerHTML = '<i class="fa-solid fa-spinner fa-spin text-sky-600"></i>';
            el.disabled = true;
            try {
                const res = await fetch(`{{ url('admin/profile/social-accounts') }}/${id}/test`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                const data = await res.json();
                alert((data.success ? '✅ SUKSES: ' : '❌ GAGAL: ') + data.message);
            } catch (err) {
                alert('Terjadi kesalahan saat menguji: ' + err.message);
            } finally {
                el.innerHTML = originalHtml;
                el.disabled = false;
            }
        }
    };
}
</script>
@endpush
@endsection
