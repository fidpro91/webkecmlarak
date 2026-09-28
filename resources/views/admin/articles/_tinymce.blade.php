@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '#konten',
            height: 480,
            menubar: 'file edit view insert format tools table help',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | ' +
                     'bold italic underline strikethrough | forecolor backcolor | ' +
                     'alignleft aligncenter alignright alignjustify | ' +
                     'bullist numlist outdent indent | link image media table | ' +
                     'removeformat code fullscreen',
            content_style: "body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #334155; } p { margin-bottom: 1rem; } img { max-width: 100%; height: auto; border-radius: 0.5rem; }",
            branding: false,
            promotion: false,
            image_advtab: true,
            images_upload_url: '{{ route('admin.articles.upload-image') }}',
            automatic_uploads: true,
            images_upload_handler: function (blobInfo, progress) {
                return new Promise(function (resolve, reject) {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route('admin.articles.upload-image') }}');
                    
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }

                    xhr.upload.onprogress = function (e) {
                        progress(e.loaded / e.total * 100);
                    };

                    xhr.onload = function () {
                        if (xhr.status < 200 || xhr.status >= 300) {
                            reject('HTTP Error: ' + xhr.status);
                            return;
                        }

                        let json;
                        try {
                            json = JSON.parse(xhr.responseText);
                        } catch (err) {
                            reject('Respon server tidak valid');
                            return;
                        }

                        if (!json || typeof json.location !== 'string') {
                            reject('Format respon upload tidak sesuai: ' + xhr.responseText);
                            return;
                        }

                        resolve(json.location);
                    };

                    xhr.onerror = function () {
                        reject('Gagal mengunggah gambar karena kesalahan koneksi jaringan.');
                    };

                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                });
            },
            setup: function (editor) {
                editor.on('change keyup NodeChange', function () {
                    editor.save();
                });
            }
        });

        // Sinkronisasi TinyMCE dan validasi konten sebelum submit form
        const form = document.getElementById('konten')?.closest('form');
        if (form) {
            form.addEventListener('submit', function (e) {
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                    const editor = tinymce.get('konten');
                    if (editor) {
                        const content = editor.getContent({ format: 'text' }).trim();
                        if (!content) {
                            e.preventDefault();
                            alert('Mohon isi konten berita terlebih dahulu sebelum menyimpan.');
                            editor.focus();
                            return false;
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
