<!-- AI Generator Button Component & Modal Partial -->

<!-- 1. Tombol Pemicu AI Generator (Tampil di Bawah Foto Utama) -->
<div class="p-4 sm:p-5 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 my-2"
     style="background-color: #f5f3ff; border: 1.5px solid #c4b5fd; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);">
    <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl shrink-0 shadow-sm"
             style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff;">
            <i class="fa-solid fa-wand-magic-sparkles text-amber-300"></i>
        </div>
        <div>
            <h4 class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                <span>Tulis Artikel Otomatis dengan AI</span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider"
                      style="background-color: #ede9fe; color: #5b21b6; border: 1px solid #ddd6fe;">
                    Gemini AI
                </span>
            </h4>
            <p class="text-xs text-slate-600 mt-0.5">Tuliskan poin liputan atau rangkuman acara, asisten AI akan menyusun naskah berita lengkap secara instan.</p>
        </div>
    </div>
    <button type="button" onclick="openAiModal()" 
            class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl font-bold text-xs transition-all shrink-0 cursor-pointer"
            style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff !important; box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.4); border: none;"
            onmouseover="this.style.filter='brightness(1.1)'; this.style.transform='translateY(-2px)'"
            onmouseout="this.style.filter='none'; this.style.transform='none'">
        <i class="fa-solid fa-sparkles text-amber-300 text-sm"></i>
        <span class="font-extrabold tracking-wide" style="color: #ffffff;">Generate Artikel AI</span>
    </button>
</div>

<!-- 2. Modal Dialog AI Generator (Diposisikan di Root Body agar Menutupi Seluruh Layar & Header) -->
@push('modals')
<div id="ai-generator-modal" 
     class="hidden fixed inset-0 overflow-y-auto"
     style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100vw; height: 100vh; z-index: 99999; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
     role="dialog" 
     aria-modal="true">
    
    <!-- Backdrop Dismiss Click Area -->
    <div class="fixed inset-0" onclick="closeAiModal()" style="z-index: 1;"></div>

    <div class="min-h-full flex items-center justify-center p-4 sm:p-6" style="min-height: 100vh; position: relative; z-index: 2;">
        <div class="relative w-full max-w-xl rounded-3xl bg-white shadow-2xl border border-slate-200 overflow-hidden my-8"
             style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);">
            
            <!-- Modal Header -->
            <div class="p-5 sm:p-6 flex items-start justify-between gap-4"
                 style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); color: #ffffff;">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-lg shrink-0"
                         style="background-color: rgba(99, 102, 241, 0.2); border: 1px solid rgba(129, 140, 248, 0.3); color: #a5b4fc;">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-300"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold tracking-tight text-white flex items-center gap-2">
                            <span>AI Agent Penulis Berita</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold tracking-wide"
                                  style="background-color: rgba(99, 102, 241, 0.3); color: #c7d2fe;">
                                Gemini
                            </span>
                        </h3>
                        <p class="text-xs text-slate-300 mt-0.5">Tuliskan topik atau poin berita yang ingin disusun secara otomatis</p>
                    </div>
                </div>
                <button type="button" onclick="closeAiModal()" 
                        class="p-1.5 rounded-xl transition cursor-pointer text-slate-400 hover:text-white"
                        style="background-color: rgba(255, 255, 255, 0.1);"
                        title="Tutup Modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-5 sm:p-6 space-y-4 bg-white text-slate-800">

                <!-- Alert Error / Warning (Tersembunyi secara default) -->
                <div id="ai_error_box" class="hidden p-4 rounded-2xl border"
                     style="background-color: #fff1f2; border-color: #fecdd3; color: #9f1239;">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base mt-0.5 shrink-0"></i>
                        <div class="flex-1">
                            <p class="font-bold text-rose-900 text-xs">Gagal Menghasilkan Artikel</p>
                            <p id="ai_error_message" class="text-rose-700 text-xs mt-0.5 leading-relaxed"></p>
                        </div>
                    </div>
                </div>

                <!-- Info Judul Terdeteksi -->
                <div id="ai_detected_title_wrapper" class="hidden p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-center gap-2">
                    <i class="fa-solid fa-heading text-indigo-600 shrink-0"></i>
                    <span class="font-semibold text-slate-500 shrink-0">Judul Aktif:</span>
                    <span id="ai_detected_title" class="font-bold text-slate-800 truncate"></span>
                </div>

                <!-- Input Textarea Prompt -->
                <div>
                    <label for="ai_prompt" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Instruksi / Poin Kegiatan Berita <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="ai_prompt" rows="5"
                              placeholder="Tuliskan poin liputan, contoh:&#10;- Lokasi kegiatan di Balai Desa Bajang, Kecamatan Mlarak&#10;- Dihadiri Camat Mlarak, Kepala Desa, BPD, dan tokoh masyarakat&#10;- Penyaluran BLT Dana Desa tahap 1 kepada 45 KPM&#10;- Pesan Camat agar dana digunakan untuk keperluan pokok & gizi keluarga..."
                              class="w-full text-xs rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 p-3.5 leading-relaxed font-sans"
                              style="border: 1px solid #cbd5e1;"></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Semakin detail poin kegiatan yang Anda tulis, semakin akurat naskah berita yang dihasilkan oleh AI.</p>
                </div>

                <!-- Saran Cepat / Inspirasi Topik -->
                <div>
                    <span class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Inspirasi Poin Cepat:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="insertPromptSuggestion('Penyaluran Bantuan Langsung Tunai (BLT) Dana Desa kepada warga, dihadiri Camat Mlarak dan Forkopimcam.')" 
                                class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition cursor-pointer"
                                style="background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;"
                                onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#3730a3'"
                                onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#334155'">
                            + Penyaluran BLT DD
                        </button>
                        <button type="button" onclick="insertPromptSuggestion('Musyawarah Perencanaan Pembangunan Kecamatan (Musrenbangcam) Mlarak membahas prioritas infrastruktur dan ketahanan pangan.')" 
                                class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition cursor-pointer"
                                style="background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;"
                                onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#3730a3'"
                                onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#334155'">
                            + Musrenbangcam
                        </button>
                        <button type="button" onclick="insertPromptSuggestion('Sosialisasi dan pelayanan terpadu administrasi kependudukan (PATEN) jemput bola ke desa-desa binaan Kecamatan Mlarak.')" 
                                class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition cursor-pointer"
                                style="background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;"
                                onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#3730a3'"
                                onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#334155'">
                            + Pelayanan PATEN
                        </button>
                        <button type="button" onclick="insertPromptSuggestion('Kerja bakti gotong royong warga bersama aparat Kecamatan Mlarak dalam menjaga kebersihan lingkungan dan kelancaran saluran irigasi.')" 
                                class="px-2.5 py-1 rounded-lg text-[11px] font-medium transition cursor-pointer"
                                style="background-color: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;"
                                onmouseover="this.style.backgroundColor='#e0e7ff'; this.style.color='#3730a3'"
                                onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.color='#334155'">
                            + Gotong Royong
                        </button>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="p-4 sm:p-5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeAiModal()" 
                        class="px-4 py-2.5 rounded-xl font-semibold text-xs transition cursor-pointer"
                        style="background-color: #ffffff; color: #334155; border: 1px solid #cbd5e1;">
                    Batal
                </button>
                <button type="button" id="btn-submit-ai" onclick="submitAiGeneration()" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs transition-all cursor-pointer"
                        style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: #ffffff !important; box-shadow: 0 4px 14px 0 rgba(79, 70, 229, 0.4); border: none;">
                    <i class="fa-solid fa-paper-plane" id="icon-submit-ai" style="color: #ffffff;"></i>
                    <span id="text-submit-ai" style="color: #ffffff;">Kirim & Generate Artikel</span>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Floating Toast Pemberitahuan Sukses -->
<div id="ai-toast-success" 
     class="hidden fixed bottom-6 right-6 z-[99999] p-4 rounded-2xl shadow-2xl max-w-sm flex items-center gap-3 transition-all duration-300"
     style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; background-color: #064e3b; color: #ffffff; border: 1px solid #059669;">
    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
         style="background-color: #10b981; color: #ffffff;">
        <i class="fa-solid fa-check"></i>
    </div>
    <div class="flex-1">
        <p class="text-xs font-bold" style="color: #ffffff;">Berhasil Di-generate!</p>
        <p class="text-[11px]" style="color: #a7f3d0;">Naskah artikel AI telah dimasukkan ke editor konten.</p>
    </div>
</div>
@endpush

<script>
    // Pastikan modal dan toast dipindahkan langsung ke <body> agar terbebas dari batasan layout
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('ai-generator-modal');
        if (modal && modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }

        const toast = document.getElementById('ai-toast-success');
        if (toast && toast.parentNode !== document.body) {
            document.body.appendChild(toast);
        }
    });

    function openAiModal() {
        const modal = document.getElementById('ai-generator-modal');
        if (!modal) return;

        // Reset state error
        const errorBox = document.getElementById('ai_error_box');
        if (errorBox) errorBox.classList.add('hidden');

        // Deteksi judul artikel saat ini jika sudah diisi
        const judulInput = document.querySelector('input[name="judul"]');
        const detectedWrapper = document.getElementById('ai_detected_title_wrapper');
        const detectedText = document.getElementById('ai_detected_title');

        if (judulInput && judulInput.value.trim() !== '') {
            if (detectedText) detectedText.textContent = judulInput.value.trim();
            if (detectedWrapper) detectedWrapper.classList.remove('hidden');
        } else {
            if (detectedWrapper) detectedWrapper.classList.add('hidden');
        }

        // Tampilkan modal dan kunci scroll body
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            const promptInput = document.getElementById('ai_prompt');
            if (promptInput) promptInput.focus();
        }, 100);
    }

    function closeAiModal() {
        const modal = document.getElementById('ai-generator-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Tangani penutupan dengan tombol ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('ai-generator-modal');
            if (modal && !modal.classList.contains('hidden')) {
                closeAiModal();
            }
        }
    });

    function insertPromptSuggestion(text) {
        const promptInput = document.getElementById('ai_prompt');
        if (!promptInput) return;

        if (promptInput.value.trim() === '') {
            promptInput.value = text;
        } else {
            promptInput.value += "\n" + text;
        }
        promptInput.focus();
    }

    function showAiToast() {
        const toast = document.getElementById('ai-toast-success');
        if (!toast) return;

        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 4500);
    }

    async function submitAiGeneration() {
        const promptInput = document.getElementById('ai_prompt');
        const errorBox = document.getElementById('ai_error_box');
        const errorMsg = document.getElementById('ai_error_message');
        const btnSubmit = document.getElementById('btn-submit-ai');
        const textSubmit = document.getElementById('text-submit-ai');
        const iconSubmit = document.getElementById('icon-submit-ai');

        const prompt = promptInput ? promptInput.value.trim() : '';

        // Validasi client-side
        if (!prompt || prompt.length < 5) {
            if (errorBox && errorMsg) {
                errorMsg.textContent = 'Silakan tuliskan instruksi atau poin-poin berita minimal 5 karakter.';
                errorBox.classList.remove('hidden');
            }
            if (promptInput) promptInput.focus();
            return;
        }

        // Sembunyikan error sebelumnya
        if (errorBox) errorBox.classList.add('hidden');

        // Ambil judul dan kategori dari form
        const judul = document.querySelector('input[name="judul"]')?.value || '';
        const categoryId = document.querySelector('select[name="category_id"]')?.value || '';
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                   || document.querySelector('input[name="_token"]')?.value;

        // Ubah tombol ke mode loading
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.75';
        btnSubmit.style.cursor = 'not-allowed';
        textSubmit.textContent = 'AI Sedang Menulis Naskah...';
        iconSubmit.className = 'fa-solid fa-spinner fa-spin';

        try {
            const response = await fetch('{{ route('admin.articles.generate-ai') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    prompt: prompt,
                    judul: judul,
                    category_id: categoryId
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                const generatedContent = data.content;

                // 1. Masukkan ke TinyMCE editor jika aktif
                if (typeof tinymce !== 'undefined') {
                    const editor = tinymce.get('konten');
                    if (editor) {
                        editor.setContent(generatedContent);
                        editor.save();
                    }
                }

                // 2. Masukkan ke textarea asli
                const textarea = document.getElementById('konten');
                if (textarea) {
                    textarea.value = generatedContent;
                    textarea.dispatchEvent(new Event('input', { bubbles: true }));
                    textarea.dispatchEvent(new Event('change', { bubbles: true }));
                }

                // Tutup modal secara otomatis
                closeAiModal();

                // Tampilkan toast berhasil
                showAiToast();

                // Bersihkan prompt untuk request berikutnya
                if (promptInput) promptInput.value = '';
            } else {
                // Tampilkan pesan error
                const message = data.message || 'Terjadi kesalahan saat memproses permintaan ke AI.';
                if (errorBox && errorMsg) {
                    errorMsg.textContent = message;
                    errorBox.classList.remove('hidden');
                }
            }
        } catch (err) {
            if (errorBox && errorMsg) {
                errorMsg.textContent = 'Gagal menghubungi server. Mohon periksa koneksi internet Anda.';
                errorBox.classList.remove('hidden');
            }
        } finally {
            // Kembalikan tombol ke keadaan semula
            btnSubmit.disabled = false;
            btnSubmit.style.opacity = '1';
            btnSubmit.style.cursor = 'pointer';
            textSubmit.textContent = 'Kirim & Generate Artikel';
            iconSubmit.className = 'fa-solid fa-paper-plane';
        }
    }
</script>
