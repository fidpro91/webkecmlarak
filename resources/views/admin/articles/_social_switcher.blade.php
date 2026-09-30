@php
    $hasSocialAccounts = isset($socialAccounts) && $socialAccounts->isNotEmpty();
    $defaultSwitcherState = old('auto_post_social', $hasSocialAccounts ? '1' : '0') == '1';
@endphp

<div x-data="{ autoPost: @js($defaultSwitcherState) }" 
     class="rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 to-white p-5 sm:p-6 shadow-xs space-y-4">
    
    <!-- Top Row: Switcher Button Toggle -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg shadow-xs">
                <i class="fa-solid fa-share-nodes"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                    <span>Otomatis Post ke Sosial Media</span>
                    @if($hasSocialAccounts)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            {{ $socialAccounts->count() }} Akun Terhubung
                        </span>
                    @endif
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">
                    Bagikan ringkasan judul, link, dan foto berita otomatis saat artikel berstatus <span class="font-semibold text-emerald-600">Published</span>.
                </p>
            </div>
        </div>

        <!-- Toggle Switcher Button -->
        <div class="flex items-center gap-3">
            <input type="hidden" name="auto_post_social" :value="autoPost ? '1' : '0'">
            <button type="button" 
                    @click="autoPost = !autoPost"
                    :class="autoPost ? 'bg-indigo-600 ring-2 ring-indigo-200' : 'bg-slate-300'"
                    class="relative inline-flex h-7 w-13 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                    role="switch" 
                    :aria-checked="autoPost.toString()">
                <span class="sr-only">Otomatis Post ke Sosial Media</span>
                <span :class="autoPost ? 'translate-x-6' : 'translate-x-0'"
                      class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out flex items-center justify-center text-[10px]">
                    <i :class="autoPost ? 'fa-solid fa-check text-indigo-600' : 'fa-solid fa-xmark text-slate-400'"></i>
                </span>
            </button>
            <span class="text-xs font-bold" :class="autoPost ? 'text-indigo-700' : 'text-slate-400'" x-text="autoPost ? 'Aktif' : 'Nonaktif'"></span>
        </div>
    </div>

    <!-- Collapsible Channel Selection -->
    <div x-show="autoPost" x-collapse x-cloak class="pt-3 border-t border-slate-200/80 space-y-3">
        @if($hasSocialAccounts)
            <p class="text-xs font-semibold text-slate-700 flex items-center gap-1.5">
                <i class="fa-solid fa-bullhorn text-indigo-500"></i> Pilih Target Channel Sosial Media:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($socialAccounts as $account)
                    @php
                        $isSelected = old('social_channels') ? in_array($account->id, old('social_channels')) : $account->auto_post;
                    @endphp
                    <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-200 bg-white hover:border-indigo-300 hover:bg-indigo-50/30 transition cursor-pointer shadow-xs">
                        <input type="checkbox" name="social_channels[]" value="{{ $account->id }}" 
                               {{ $isSelected ? 'checked' : '' }}
                               class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                        <div class="flex items-center gap-2 overflow-hidden">
                            <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 text-sm flex-shrink-0">
                                <i class="{{ $account->platform_icon }}"></i>
                            </div>
                            <div class="truncate">
                                <span class="block text-xs font-bold text-slate-800 truncate">{{ $account->account_name }}</span>
                                <span class="block text-[10px] text-slate-400 capitalize">{{ $account->platform_label }}</span>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
            <p class="text-[11px] text-slate-400">
                Tip: Pengaturan akun & penambahan token API baru dapat dikelola di <a href="{{ route('admin.profile.edit') }}" target="_blank" class="text-indigo-600 underline font-semibold hover:text-indigo-800">Halaman Akun & Sosial Media</a>.
            </p>
        @else
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <i class="fa-solid fa-circle-info text-amber-600 text-base"></i>
                    <span>Belum ada akun sosial media yang terhubung ke sistem.</span>
                </div>
                <a href="{{ route('admin.profile.edit') }}" target="_blank" 
                   class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition shadow-xs whitespace-nowrap">
                    Hubungkan Sekarang
                </a>
            </div>
        @endif

        <!-- Riwayat Log Posting Terakhir (khusus halaman Edit) -->
        @if(isset($socialLogs) && $socialLogs->isNotEmpty())
            <div class="pt-3 border-t border-slate-200/80 space-y-2">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Riwayat Publikasi Sosial Media:</span>
                <div class="space-y-1.5">
                    @foreach($socialLogs as $log)
                        <div class="flex items-center justify-between text-xs p-2 rounded-xl bg-white border border-slate-200">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $log->status === 'success' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span class="font-semibold text-slate-800 capitalize">{{ $log->platform }}</span>
                                <span class="text-slate-400">• {{ $log->created_at->isoFormat('D MMM Y, HH:mm') }}</span>
                            </div>
                            <span class="text-[11px] font-semibold {{ $log->status === 'success' ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $log->message }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

</div>
