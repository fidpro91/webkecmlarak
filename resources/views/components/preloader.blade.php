<!-- Preloader Resmi Kecamatan Mlarak -->
<div id="site-preloader" aria-label="Memuat Halaman Website" role="status">
    <div class="preloader-content">
        <!-- Container Animasi GIF -->
        <div class="preloader-gif-wrap">
            <img src="{{ asset('images/preloader.gif') }}?v={{ file_exists(public_path('images/preloader.gif')) ? filemtime(public_path('images/preloader.gif')) : time() }}" 
                 alt="Memuat Website Resmi Kecamatan Mlarak..." 
                 class="preloader-img">
        </div>
    </div>
</div>

<style>
    #site-preloader {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100vw;
        height: 100vh;
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        user-select: none;
        -webkit-user-select: none;
        /* Transisi pudar perlahan yang sangat halus (850ms dengan kurva deselerasi lembut) */
        transition: opacity 0.85s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.85s ease;
        will-change: opacity, visibility;
    }

    #site-preloader .preloader-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.85s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.75s ease-out;
        will-change: transform, opacity;
        transform: scale(1) translateY(0);
        opacity: 1;
    }

    #site-preloader .preloader-gif-wrap {
        position: relative;
        width: 18rem;
        max-width: 85vw;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #site-preloader .preloader-img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
    }

    /* Status Pudar Perlahan (Smooth Fade-out Dissolve) */
    #site-preloader.preloader-fading {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }

    #site-preloader.preloader-fading .preloader-content {
        transform: scale(0.96) translateY(-8px);
        opacity: 0 !important;
    }
</style>

<script>
    (function() {
        const preloader = document.getElementById('site-preloader');
        if (!preloader) return;

        let isHidden = false;

        function hidePreloader() {
            if (isHidden) return;
            isHidden = true;

            // Beri kelas transisi pudar perlahan
            preloader.classList.add('preloader-fading');

            // Hapus dari alur render setelah animasi pudar (850ms) selesai sempurna
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 900);
        }

        // Jalankan saat dokumen & seluruh gambar selesai dimuat
        if (document.readyState === 'complete') {
            setTimeout(hidePreloader, 250);
        } else {
            window.addEventListener('load', function() {
                setTimeout(hidePreloader, 250);
            });
        }

        // Fallback pengaman: jangan biarkan pengunjung tertahan jika aset pihak ketiga lambat
        setTimeout(hidePreloader, 3000);

        // Dukungan bfcache (kembali/maju di history browser)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                preloader.style.display = 'none';
            }
        });
    })();
</script>
