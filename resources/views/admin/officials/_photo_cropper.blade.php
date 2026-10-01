<div class="space-y-4">
    <div>
        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
            Foto Pejabat & Posisi Cropping (Border Rounded)
        </label>
        <p class="text-xs text-slate-500 mb-3">
            Atur dan posisikan foto agar pas dengan bingkai melingkar (avatar) maupun kartu rounded pada website.
        </p>
    </div>

    <!-- Hidden Native Inputs -->
    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp,image/jpg" class="sr-only">
    <input type="hidden" id="foto_cropped" name="foto_cropped" value="">
    <input type="hidden" id="hapus_foto" name="hapus_foto" value="0">

    <!-- Photo Management & Preview Card -->
    <div class="p-5 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/70 hover:bg-slate-50 transition relative" id="photo-dropzone">
        <div class="flex flex-col sm:flex-row items-center gap-6">
            
            <!-- Dual Preview Frames: Rounded-Full (Circle) & Rounded-2XL (Card) -->
            <div class="flex items-center gap-4 flex-shrink-0">
                <!-- Frame 1: Circle (rounded-full) as in Profile & Table -->
                <div class="text-center">
                    <div class="relative w-24 h-24 rounded-full border-4 border-emerald-500 shadow-md overflow-hidden bg-white flex items-center justify-center group ring-2 ring-emerald-100">
                        <img id="form-preview-circle" 
                             src="{{ !empty($currentPhoto) ? $currentPhoto : 'https://ui-avatars.com/api/?name=Pejabat&background=e2e8f0&color=64748b&size=256' }}" 
                             alt="Preview Lingkaran" 
                             class="w-full h-full object-cover transition duration-300 {{ empty($currentPhoto) ? 'opacity-60' : '' }}">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer" onclick="document.getElementById('foto').click()">
                            <i class="fa-solid fa-camera text-white text-base"></i>
                        </div>
                    </div>
                    <span class="block text-[10px] font-semibold text-slate-500 mt-1">Lingkaran (Profil)</span>
                </div>

                <!-- Frame 2: Rounded Card (rounded-2xl) as in Camat Card -->
                <div class="text-center">
                    <div class="relative w-24 h-24 rounded-2xl border-2 border-emerald-400 shadow-md overflow-hidden bg-white flex items-center justify-center group ring-2 ring-emerald-50">
                        <img id="form-preview-rounded" 
                             src="{{ !empty($currentPhoto) ? $currentPhoto : 'https://ui-avatars.com/api/?name=Pejabat&background=e2e8f0&color=64748b&size=256' }}" 
                             alt="Preview Rounded Card" 
                             class="w-full h-full object-cover transition duration-300 {{ empty($currentPhoto) ? 'opacity-60' : '' }}">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center cursor-pointer" onclick="document.getElementById('foto').click()">
                            <i class="fa-solid fa-camera text-white text-base"></i>
                        </div>
                    </div>
                    <span class="block text-[10px] font-semibold text-slate-500 mt-1">Rounded Card</span>
                </div>
            </div>

            <!-- Action Controls & Status Information -->
            <div class="flex-1 text-center sm:text-left space-y-2.5">
                <div class="flex flex-wrap items-center gap-2 justify-center sm:justify-start">
                    <span id="photo-status-badge" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold {{ !empty($currentPhoto) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-600' }}">
                        <i class="fa-solid {{ !empty($currentPhoto) ? 'fa-circle-check text-emerald-600' : 'fa-image text-slate-400' }}" id="photo-status-icon"></i>
                        <span id="photo-status-text">{{ !empty($currentPhoto) ? 'Foto Pejabat Aktif' : 'Belum Ada Foto' }}</span>
                    </span>
                    <span class="text-[11px] text-slate-400">JPG, PNG, atau WebP (Maks. 10MB)</span>
                </div>

                <p class="text-xs text-slate-500 leading-relaxed">
                    Setelah memilih file, jendela pemotong foto interaktif akan terbuka agar Anda dapat memperbesar (zoom), menggeser, dan memutar foto sehingga wajah dan postur pas dengan garis lengkung rounded.
                </p>

                <!-- Action Button Group -->
                <div class="flex flex-wrap items-center gap-2 pt-1 justify-center sm:justify-start">
                    <button type="button" 
                            onclick="document.getElementById('foto').click()" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition">
                        <i class="fa-solid fa-upload"></i>
                        <span>{{ !empty($currentPhoto) ? 'Ganti Foto' : 'Pilih Foto Pejabat' }}</span>
                    </button>

                    <button type="button" 
                            id="btn-open-cropper" 
                            onclick="window.openCropperStudio()" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:border-emerald-500 hover:text-emerald-700 text-slate-700 font-semibold text-xs shadow-sm transition {{ empty($currentPhoto) ? 'hidden' : '' }}">
                        <i class="fa-solid fa-crop-simple text-emerald-600"></i>
                        <span>Sesuaikan Posisi (Crop)</span>
                    </button>

                    <button type="button" 
                            id="btn-remove-photo" 
                            onclick="window.removeSelectedPhoto()" 
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs transition {{ empty($currentPhoto) ? 'hidden' : '' }}" 
                            title="Hapus Foto">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL STUDIO CROPPING & POSITIONING FOTO                   -->
<!-- ======================================================== -->
<div id="cropper-modal" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-sm overflow-y-auto p-3 sm:p-6 flex items-center justify-center">
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-4xl overflow-hidden my-auto flex flex-col max-h-[92vh] animate-in fade-in zoom-in duration-200">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-crop-simple"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm sm:text-base text-white">Sesuaikan Posisi & Potongan Foto Pejabat</h3>
                    <p class="text-xs text-slate-300">Geser (drag), perbesar (zoom), dan putar foto agar pas berada di dalam border rounded</p>
                </div>
            </div>
            <button type="button" onclick="window.closeCropperModal()" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Modal Body: 2 Columns (Cropper Canvas + Real-time Preview) -->
        <div class="p-4 sm:p-6 overflow-y-auto flex-1 grid grid-cols-1 lg:grid-cols-12 gap-6 bg-slate-50">
            
            <!-- Column 1: Main Cropper Canvas & Controls (7 Cols) -->
            <div class="lg:col-span-7 flex flex-col space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-sliders text-emerald-600"></i> Kanvas Penyesuaian
                    </span>
                    <!-- Border Guide Switcher -->
                    <div class="inline-flex p-1 bg-slate-200/80 rounded-xl text-xs">
                        <button type="button" id="btn-guide-circle" onclick="window.setCropGuide('circle')" class="px-2.5 py-1 rounded-lg font-bold text-emerald-800 bg-white shadow-sm transition">
                            <i class="fa-regular fa-circle mr-1"></i> Lingkaran
                        </button>
                        <button type="button" id="btn-guide-rounded" onclick="window.setCropGuide('rounded')" class="px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 transition">
                            <i class="fa-regular fa-square mr-1"></i> Rounded
                        </button>
                    </div>
                </div>

                <!-- Cropper Image Container -->
                <div class="relative w-full h-[320px] sm:h-[380px] bg-slate-950 rounded-2xl overflow-hidden border border-slate-800 shadow-inner flex items-center justify-center cropper-circle-guide" id="cropper-container">
                    <img id="cropper-image" src="" alt="Cropper Target" class="max-w-full block">
                </div>

                <!-- Interactive Toolbar Controls -->
                <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="window.cropperAction('zoom', 0.1)" title="Perbesar (Zoom In)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                        </button>
                        <button type="button" onclick="window.cropperAction('zoom', -0.1)" title="Perkecil (Zoom Out)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-magnifying-glass-minus"></i>
                        </button>
                        <div class="h-5 w-px bg-slate-200 mx-1"></div>
                        <button type="button" onclick="window.cropperAction('rotate', -90)" title="Putar Kiri 90°" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>
                        <button type="button" onclick="window.cropperAction('rotate', 90)" title="Putar Kanan 90°" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>
                        <div class="h-5 w-px bg-slate-200 mx-1"></div>
                        <button type="button" onclick="window.cropperAction('flipX')" title="Balik Horizontal (Mirror)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 font-bold transition flex items-center justify-center text-xs">
                            <i class="fa-solid fa-arrows-left-right"></i>
                        </button>
                    </div>

                    <button type="button" onclick="window.cropperAction('reset')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition">
                        <i class="fa-solid fa-arrow-rotate-left text-[11px]"></i>
                        <span>Reset Posisi</span>
                    </button>
                </div>
            </div>

            <!-- Column 2: Live Real-time Preview in Rounded Borders (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-eye text-emerald-600"></i> Pratinjau Hasil Pemotongan
                        </span>
                        <p class="text-[11px] text-slate-500 mt-0.5">
                            Pratinjau otomatis bergerak saat Anda menggeser atau memperbesar foto.
                        </p>
                    </div>

                    <!-- Live Preview 1: Circular Avatar (Profil & Tabel) -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-28 h-28 rounded-full border-4 border-emerald-500 shadow-lg overflow-hidden bg-slate-100 flex-shrink-0 relative">
                            <div class="official-live-preview w-full h-full overflow-hidden"></div>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-extrabold uppercase tracking-wider">
                                Border Lingkaran (Bulat)
                            </span>
                            <h4 class="text-xs font-bold text-slate-900">Avatar Pejabat</h4>
                            <p class="text-[11px] text-slate-500 leading-tight">
                                Tampilan di Halaman Profil Aparatur Kecamatan dan Tabel Kelola Pejabat.
                            </p>
                        </div>
                    </div>

                    <!-- Live Preview 2: Rounded-2XL Card (Kartu Sambutan Camat) -->
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                        <div class="w-28 h-28 rounded-2xl border-2 border-emerald-400 shadow-md overflow-hidden bg-slate-100 flex-shrink-0 relative">
                            <div class="official-live-preview w-full h-full overflow-hidden"></div>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2 py-0.5 rounded-md bg-teal-100 text-teal-800 text-[10px] font-extrabold uppercase tracking-wider">
                                Border Rounded-2XL
                            </span>
                            <h4 class="text-xs font-bold text-slate-900">Kartu Beranda Camat</h4>
                            <p class="text-[11px] text-slate-500 leading-tight">
                                Tampilan pada Kartu Sambutan Camat dan Bagan Struktur Organisasi.
                            </p>
                        </div>
                    </div>

                    <!-- Tips Panel -->
                    <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200/80 text-amber-900 text-xs space-y-1">
                        <p class="font-bold flex items-center gap-1.5 text-amber-800">
                            <i class="fa-solid fa-lightbulb text-amber-500"></i> Tips Posisi Ideal:
                        </p>
                        <p class="text-[11px] text-amber-800/90 leading-relaxed">
                            Pastikan posisi kepala/rambut tidak terpotong garis lengkung atas dan wajah tepat berada di tengah lingkaran.
                        </p>
                    </div>
                </div>

                <!-- Help Notice -->
                <div class="text-[11px] text-slate-400 text-center sm:text-left">
                    <i class="fa-solid fa-circle-info mr-1"></i> Foto akan disimpan dalam resolusi tajam 800 x 800 piksel dengan kompresi berkualitas tinggi.
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-white border-t border-slate-200 flex items-center justify-end gap-3">
            <button type="button" onclick="window.closeCropperModal()" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition">
                Batal
            </button>
            <button type="button" onclick="window.applyCroppedPhoto()" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-700/20 transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>Terapkan Potongan & Gunakan Foto</span>
            </button>
        </div>

    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/cropperjs/cropper.min.css') }}">
<style>
    /* Styling khusus guide cropper rounded */
    .cropper-circle-guide .cropper-view-box,
    .cropper-circle-guide .cropper-face {
        border-radius: 50% !important;
    }
    .cropper-rounded-guide .cropper-view-box,
    .cropper-rounded-guide .cropper-face {
        border-radius: 1.5rem !important;
    }
    .cropper-view-box {
        outline: 2px dashed #10b981 !important;
        box-shadow: 0 0 0 1px rgba(16, 185, 129, 0.4);
    }
    .cropper-line {
        background-color: #10b981 !important;
    }
    .cropper-point {
        background-color: #10b981 !important;
        width: 8px !important;
        height: 8px !important;
        border-radius: 50% !important;
    }
    .cropper-dashed {
        border-color: rgba(255, 255, 255, 0.6) !important;
    }
    .cropper-modal {
        background-color: rgba(15, 23, 42, 0.75) !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('vendor/cropperjs/cropper.min.js') }}"></script>
<script>
(function() {
    let cropperInstance = null;
    let currentImageSrc = "{{ !empty($currentPhoto) ? $currentPhoto : '' }}";
    let scaleX = 1;

    const fileInput = document.getElementById('foto');
    const croppedInput = document.getElementById('foto_cropped');
    const hapusFotoInput = document.getElementById('hapus_foto');
    const previewCircle = document.getElementById('form-preview-circle');
    const previewRounded = document.getElementById('form-preview-rounded');
    const cropperImage = document.getElementById('cropper-image');
    const cropperModal = document.getElementById('cropper-modal');
    const cropperContainer = document.getElementById('cropper-container');
    const btnOpenCropper = document.getElementById('btn-open-cropper');
    const btnRemovePhoto = document.getElementById('btn-remove-photo');
    const statusBadge = document.getElementById('photo-status-badge');
    const statusText = document.getElementById('photo-status-text');
    const statusIcon = document.getElementById('photo-status-icon');

    // Handle File Selection
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const file = files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Mohon pilih berkas gambar yang valid (JPG, PNG, atau WebP).');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    currentImageSrc = event.target.result;
                    window.openCropperStudio();
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Drag & Drop Support on dropzone
    const dropzone = document.getElementById('photo-dropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('border-emerald-500', 'bg-emerald-50/50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('border-emerald-500', 'bg-emerald-50/50');
            }, false);
        });

        dropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files.length > 0) {
                if (fileInput) {
                    fileInput.files = files;
                    const event = new Event('change');
                    fileInput.dispatchEvent(event);
                }
            }
        });
    }

    // Open Cropper Studio Modal
    window.openCropperStudio = function() {
        if (!currentImageSrc) {
            if (fileInput) fileInput.click();
            return;
        }

        cropperModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        // Destroy previous instance if any
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }

        cropperImage.src = currentImageSrc;
        scaleX = 1;

        // Initialize Cropper.js after image is ready
        setTimeout(function() {
            if (typeof Cropper === 'undefined') {
                console.error('Cropper.js library not loaded');
                return;
            }

            cropperInstance = new Cropper(cropperImage, {
                aspectRatio: 1, // 1:1 Aspect ratio perfect for avatars & cards
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.9,
                responsive: true,
                restore: false,
                guides: true,
                center: true,
                highlight: true,
                cropBoxMovable: true,
                cropBoxResizable: true,
                preview: '.official-live-preview',
                ready: function() {
                    window.setCropGuide('circle');
                }
            });
        }, 150);
    };

    // Close Cropper Modal
    window.closeCropperModal = function() {
        cropperModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (cropperInstance) {
            cropperInstance.destroy();
            cropperInstance = null;
        }
    };

    // Switch Visual Crop Guide (Circle vs Rounded)
    window.setCropGuide = function(mode) {
        const btnCircle = document.getElementById('btn-guide-circle');
        const btnRounded = document.getElementById('btn-guide-rounded');

        if (mode === 'circle') {
            cropperContainer.classList.add('cropper-circle-guide');
            cropperContainer.classList.remove('cropper-rounded-guide');
            btnCircle.className = "px-2.5 py-1 rounded-lg font-bold text-emerald-800 bg-white shadow-sm transition";
            btnRounded.className = "px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 transition";
        } else {
            cropperContainer.classList.remove('cropper-circle-guide');
            cropperContainer.classList.add('cropper-rounded-guide');
            btnRounded.className = "px-2.5 py-1 rounded-lg font-bold text-emerald-800 bg-white shadow-sm transition";
            btnCircle.className = "px-2.5 py-1 rounded-lg font-semibold text-slate-600 hover:text-slate-900 transition";
        }
    };

    // Toolbar Actions
    window.cropperAction = function(action, param) {
        if (!cropperInstance) return;

        switch(action) {
            case 'zoom':
                cropperInstance.zoom(param);
                break;
            case 'rotate':
                cropperInstance.rotate(param);
                break;
            case 'flipX':
                scaleX = scaleX === 1 ? -1 : 1;
                cropperInstance.scaleX(scaleX);
                break;
            case 'reset':
                scaleX = 1;
                cropperInstance.reset();
                break;
        }
    };

    // Apply Cropped Photo
    window.applyCroppedPhoto = function() {
        if (!cropperInstance) return;

        // Generate high quality canvas
        const canvas = cropperInstance.getCroppedCanvas({
            width: 800,
            height: 800,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        if (!canvas) {
            alert('Gagal memproses potongan gambar. Silakan coba kembali.');
            return;
        }

        const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);

        // Update hidden cropped input
        croppedInput.value = croppedDataUrl;
        currentImageSrc = croppedDataUrl;

        // Reset remove flag
        if (hapusFotoInput) hapusFotoInput.value = '0';

        // Update form visual previews
        previewCircle.src = croppedDataUrl;
        previewCircle.classList.remove('opacity-60');
        previewRounded.src = croppedDataUrl;
        previewRounded.classList.remove('opacity-60');

        // Update action buttons & status
        btnOpenCropper.classList.remove('hidden');
        btnRemovePhoto.classList.remove('hidden');

        statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200';
        statusIcon.className = 'fa-solid fa-circle-check text-emerald-600';
        statusText.textContent = 'Foto Siap Disimpan (Sudah Dipotong)';

        // Also convert to File object into the native file input if DataTransfer is supported
        canvas.toBlob(function(blob) {
            if (blob && window.DataTransfer && fileInput) {
                try {
                    const file = new File([blob], 'pejabat_' + Date.now() + '.jpg', { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;
                } catch (e) {
                    console.log('DataTransfer fallback to base64 input', e);
                }
            }
        }, 'image/jpeg', 0.9);

        window.closeCropperModal();
    };

    // Remove Selected Photo
    window.removeSelectedPhoto = function() {
        if (!confirm('Hapus atau batalkan foto pejabat ini?')) return;

        if (fileInput) fileInput.value = '';
        if (croppedInput) croppedInput.value = '';
        if (hapusFotoInput) hapusFotoInput.value = '1';
        currentImageSrc = '';

        const defaultAvatar = 'https://ui-avatars.com/api/?name=Pejabat&background=e2e8f0&color=64748b&size=256';
        previewCircle.src = defaultAvatar;
        previewCircle.classList.add('opacity-60');
        previewRounded.src = defaultAvatar;
        previewRounded.classList.add('opacity-60');

        btnOpenCropper.classList.add('hidden');
        btnRemovePhoto.classList.add('hidden');

        statusBadge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-200 text-slate-600';
        statusIcon.className = 'fa-solid fa-image text-slate-400';
        statusText.textContent = 'Foto Dihapus';
    };

    // Close on Escape Key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !cropperModal.classList.contains('hidden')) {
            window.closeCropperModal();
        }
    });

})();
</script>
@endpush
