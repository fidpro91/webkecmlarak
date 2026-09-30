@php
    $initialTags = [];
    if (old('hashtags')) {
        $old = old('hashtags');
        $initialTags = is_array($old) ? $old : (json_decode($old, true) ?: explode(',', $old));
    } elseif (isset($article) && !empty($article->formatted_hashtags)) {
        $initialTags = $article->formatted_hashtags;
    }
    // Bersihkan & format
    $initialTags = array_values(array_filter(array_map(function($t) {
        $t = trim((string)$t);
        return empty($t) ? null : (str_starts_with($t, '#') ? $t : '#' . $t);
    }, $initialTags)));
@endphp

<div x-data="hashtagInputManager(@js($initialTags))" class="space-y-2">
    <div class="flex items-center justify-between">
        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
            Hashtag / Tagar Berita (Mendukung SEO)
        </label>
        <span class="text-[11px] text-slate-400">
            <span x-text="tags.length"></span> tagar terpasang
        </span>
    </div>

    <!-- Tag Input Container -->
    <div class="p-2.5 rounded-2xl border border-slate-200 bg-white focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500 transition shadow-xs flex flex-wrap items-center gap-2 min-h-[48px]">
        
        <!-- Render Packaged Badges -->
        <template x-for="(tag, index) in tags" :key="index">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/80 shadow-xs transition transform hover:scale-105">
                <span x-text="tag"></span>
                <button type="button" 
                        @click="removeTag(index)" 
                        title="Hapus hashtag" 
                        class="w-4 h-4 rounded-full flex items-center justify-center text-rose-400 hover:text-rose-800 hover:bg-rose-100 transition focus:outline-none">
                    <i class="fa-solid fa-xmark text-[10px]"></i>
                </button>
            </span>
        </template>

        <!-- Dynamic Input Field -->
        <input type="text"
               x-ref="tagInput"
               x-model="rawInput"
               @keydown="handleKeyDown($event)"
               @input="handleInput($event)"
               @blur="commitCurrentInput()"
               placeholder="Ketik #namahastagh lalu tekan Spasi / Enter..."
               class="flex-1 min-w-[200px] border-0 p-1 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:ring-0 focus:outline-none bg-transparent">
    </div>

    <!-- Hidden Input For Form Submission -->
    <input type="hidden" name="hashtags" :value="JSON.stringify(tags)">

    <!-- Petunjuk & Rekomendasi Tagar Cepat -->
    <div class="flex flex-wrap items-center justify-between gap-2 pt-1 text-[11px] text-slate-500">
        <p class="flex items-center gap-1.5">
            <i class="fa-solid fa-lightbulb text-amber-500"></i>
            Ketik <code>#kata</code> lalu tekan <strong>Spasi</strong>, <strong>Koma</strong>, atau <strong>Enter</strong> untuk otomatis mengemas tagar.
        </p>

        <!-- Quick Suggestions -->
        <div class="flex flex-wrap items-center gap-1.5">
            <span class="text-slate-400 text-[10px] font-semibold">Saran:</span>
            @foreach(['#KecamatanMlarak', '#PonorogoHebat', '#BeritaMlarak', '#PelayananPublik'] as $quickTag)
                <button type="button" 
                        @click="addTag('{{ $quickTag }}')"
                        class="px-2 py-0.5 rounded-md bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 transition text-[10px] font-medium">
                    + {{ $quickTag }}
                </button>
            @endforeach
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function hashtagInputManager(defaultTags = []) {
    return {
        tags: Array.isArray(defaultTags) ? defaultTags : [],
        rawInput: '',

        handleKeyDown(e) {
            // Ketika menekan Enter, Koma, atau Spasi -> kemas tagar
            if (e.key === 'Enter' || e.key === ',' || e.key === ' ') {
                e.preventDefault();
                this.commitCurrentInput();
            } else if (e.key === 'Backspace' && this.rawInput === '' && this.tags.length > 0) {
                // Hapus tagar terakhir jika input kosong dan menekan backspace
                this.removeTag(this.tags.length - 1);
            }
        },

        handleInput(e) {
            let val = this.rawInput;

            // Jika admin mengetik '#' kedua saat sudah ada teks (misal: "#mlarak#ponorogo")
            // Maka kemas bagian pertama dan lanjutkan bagian kedua
            if (val.length > 1 && val.lastIndexOf('#') > 0) {
                const parts = val.split('#').filter(p => p.trim() !== '');
                if (parts.length > 1) {
                    const firstPart = parts[0].trim();
                    this.addTag('#' + firstPart);
                    this.rawInput = '#' + parts.slice(1).join('#');
                }
            }
        },

        commitCurrentInput() {
            let val = this.rawInput.trim();
            if (!val) return;

            // Jika ada beberapa tag dipisah spasi atau koma
            const tokens = val.split(/[\s,]+/);
            tokens.forEach(tok => {
                tok = tok.trim();
                if (tok) {
                    this.addTag(tok);
                }
            });

            this.rawInput = '';
        },

        addTag(text) {
            if (!text) return;
            text = text.trim().replace(/^#+/, ''); // Hapus # ganda di awal
            text = text.replace(/[\s\-_]+/g, '_'); // Ubah spasi jadi underscore
            if (!text) return;

            const formatted = '#' + text;
            // Cegah duplikasi (case-insensitive)
            const exists = this.tags.some(t => t.toLowerCase() === formatted.toLowerCase());
            if (!exists) {
                this.tags.push(formatted);
            }
        },

        removeTag(index) {
            this.tags.splice(index, 1);
        }
    };
}
</script>
@endpush
@endonce
