import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// PDF Preview Modal Controller
window.openPreview = function(url, title = 'Pratinjau Dokumen PDF') {
    const modal = document.getElementById('pdf-preview-modal');
    const iframe = document.getElementById('pdf-preview-iframe');
    const titleEl = document.getElementById('pdf-preview-title');
    const downloadBtn = document.getElementById('pdf-preview-download-btn');

    if (modal && iframe) {
        if (titleEl) titleEl.textContent = title;
        iframe.src = url;
        if (downloadBtn) {
            // Point to download url if provided
            const downloadUrl = url.replace('/preview', '/unduh');
            downloadBtn.href = downloadUrl;
        }
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
};

window.closePreview = function() {
    const modal = document.getElementById('pdf-preview-modal');
    const iframe = document.getElementById('pdf-preview-iframe');

    if (modal && iframe) {
        iframe.src = ''; // Stop PDF rendering
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
};

// Image Lightbox Modal Controller
window.openLightbox = function(src, caption = '') {
    const modal = document.getElementById('image-lightbox-modal');
    const img = document.getElementById('lightbox-img');
    const captionEl = document.getElementById('lightbox-caption');

    if (modal && img) {
        img.src = src;
        if (captionEl) captionEl.textContent = caption;
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
};

window.closeLightbox = function() {
    const modal = document.getElementById('image-lightbox-modal');
    const img = document.getElementById('lightbox-img');

    if (modal && img) {
        img.src = '';
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
};

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        window.closePreview();
        window.closeLightbox();
    }
});
