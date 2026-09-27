<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Download;
use App\Models\DownloadCategory;
use App\Models\Gallery;
use App\Models\Menu;
use App\Models\MenuPage;
use App\Models\Official;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@mlarak.ponorogo.go.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'operator@mlarak.ponorogo.go.id'],
            [
                'name' => 'Operator Kecamatan',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Settings
        $settings = [
            'instansi_nama' => 'Pemerintah Kecamatan Mlarak',
            'logo' => 'images/logoponorogo.png',
            'kabupaten' => 'Kabupaten Ponorogo',
            'provinsi' => 'Jawa Timur',
            'slogan' => 'Membangun Bersama, Melayani dengan Ikhlas dan Transparan',
            'alamat' => 'Jl. Raya Mlarak - Sambit No. 12, Mlarak, Kabupaten Ponorogo, Jawa Timur 63472',
            'telepon' => '(0352) 311029',
            'email' => 'kecamatan.mlarak@ponorogo.go.id',
            'whatsapp' => '081234567890',
            'jam_kerja' => 'Senin - Kamis: 07.30 - 15.30 WIB | Jumat: 07.30 - 14.30 WIB',
            'facebook' => 'https://facebook.com/kecamatanmlarak',
            'instagram' => 'https://instagram.com/kecamatanmlarak',
            'youtube' => 'https://youtube.com/@kecamatanmlarak',
            'maps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.7937398939634!2d111.5126839!3d-7.8911571!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e799ff246bf2f63%3A0x6a2dfb43a96ca26!2sKantor%20Kecamatan%20Mlarak!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid',
            'visi' => 'Terwujudnya Pelayanan Publik Kecamatan Mlarak yang Profesional, Transparan, Akuntabel, dan Berkelanjutan Menuju Ponorogo Hebat.',
            'misi' => "1. Meningkatkan kualitas tata kelola birokrasi pemerintahan kecamatan yang responsif, adaptif, dan akuntabel.\n2. Mengoptimalkan pelayanan administrasi terpadu kecamatan (PATEN) berbasis digital demi kepuasan masyarakat.\n3. Mendorong percepatan pembangunan desa dan pemberdayaan ekonomi masyarakat berbasis potensi pertanian & UMKM.\n4. Memperkuat sinergi ketentraman, ketertiban umum, serta pelestarian nilai budaya dan kearifan lokal Ponorogo.",
            'sejarah' => 'Kecamatan Mlarak memiliki sejarah panjang sebagai salah satu wilayah penyangga peradaban spiritual dan kebudayaan di Kabupaten Ponorogo. Memiliki lahan agraris subur beririgasi teknis dan tradisi gotong royong yang kuat, Mlarak dikenal secara nasional dan global antara lain melalui keberadaan Pondok Modern Darussalam Gontor di Desa Gontor yang didirikan pada tahun 1926. Sepanjang perjalanan sejarah Ponorogo, Mlarak berperan aktif sebagai pusat transmisi keilmuan, sentra kerajinan, dan lumbung pangan yang tangguh di Jawa Timur.',
            'sambutan_camat' => 'Selamat datang di Portal Resmi Sistem Informasi Pemerintah Kecamatan Mlarak, Kabupaten Ponorogo. Melalui portal ini kami berkomitmen menghadirkan keterbukaan informasi publik, kemudahan akses layanan perizinan terpadu, dan wadah interaksi aktif bersama seluruh warga di 15 desa binaan.',
            'nama_camat' => 'Drs. H. Bambang Sujarwo, M.Si',
            'foto_camat' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=600&q=80',
        ];

        foreach ($settings as $key => $val) {
            Setting::set($key, $val);
        }

        // 3. Sliders
        $sliders = [
            [
                'judul' => 'Pelayanan Terpadu & Prima Kecamatan Mlarak',
                'gambar' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1600&q=80',
                'deskripsi' => 'Komitmen kami menghadirkan pelayanan administrasi publik yang cepat, mudah, transparan, dan bebas pungli.',
                'urutan' => 1,
                'status' => true,
            ],
            [
                'judul' => 'Pesona 15 Desa Maju & Berbudaya di Mlarak',
                'gambar' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=80',
                'deskripsi' => 'Kekayaan potensi pertanian, pusat pendidikan pesantren terkemuka dunia, dan harmoni seni Reyog Ponorogo.',
                'urutan' => 2,
                'status' => true,
            ],
            [
                'judul' => 'Transparansi Pembangunan & Partisipasi Warga',
                'gambar' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1600&q=80',
                'deskripsi' => 'Akses informasi publik, publikasi berkas regulasi, dan sarana pengaduan terintegrasi demi Ponorogo Hebat.',
                'urutan' => 3,
                'status' => true,
            ],
        ];
        foreach ($sliders as $s) {
            Slider::create($s);
        }

        // 4. Categories & Articles
        $categories = [
            'Pemerintahan',
            'Pembangunan Desa',
            'Sosial & Budaya',
            'Pendidikan & Keagamaan',
            'Ekonomi & UMKM',
        ];

        $catModels = [];
        foreach ($categories as $catName) {
            $catModels[$catName] = Category::create([
                'nama' => $catName,
                'slug' => Str::slug($catName),
            ]);
        }

        $articles = [
            [
                'judul' => 'Kecamatan Mlarak Gelar Musrenbangcam 2026: Fokus pada Infrastruktur Pertanian dan Digitalisasi Desa',
                'category' => 'Pemerintahan',
                'gambar' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=1200&q=80',
                'konten' => '<p>Pemerintah Kecamatan Mlarak sukses menyelenggarakan Musyawarah Perencanaan Pembangunan Kecamatan (Musrenbangcam) tahun anggaran 2026 yang dihadiri oleh seluruh Kepala Desa, BPD, tokoh masyarakat, dan perwakilan OPD Kabupaten Ponorogo.</p><p>Camat Mlarak menyampaikan bahwa prioritas usulan pembangunan tahun ini menitikberatkan pada revitalisasi saluran irigasi persawahan, pengaspalan jalan poros penghubung antar-desa, serta penguatan infrastruktur digital desa guna mendukung keterbukaan informasi publik dan layanan administrasi online.</p>',
            ],
            [
                'judul' => 'Inovasi PATEN Mobile: Petugas Kecamatan Jemput Bola Rekam KTP-el bagi Lansia dan Disabilitas',
                'category' => 'Pemerintahan',
                'gambar' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80',
                'konten' => '<p>Guna memastikan seluruh warga negara memperoleh hak dokumen kependudukan secara merata, Tim Pelayanan Administrasi Terpadu Kecamatan (PATEN) Mlarak meluncurkan program layanan jemput bola langsung ke rumah-rumah warga lansia dan disabilitas di Desa Candi dan Desa Gandu.</p><p>Melalui inisiatif ini, warga tidak perlu menempuh perjalanan jauh ke kantor kecamatan. Dokumen seperti KTP-el dan Kartu Keluarga langsung diproses dan diantarkan ke kediaman warga tanpa dipungut biaya apapun (gratis).</p>',
            ],
            [
                'judul' => 'Festival Seni Reyog Remaja se-Kecamatan Mlarak Semarakkan Peringatan Hari Jadi Ponorogo',
                'category' => 'Sosial & Budaya',
                'gambar' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80',
                'konten' => '<p>Ratusan pemuda dan seniman tari dari 15 desa se-Kecamatan Mlarak tumpah ruah di Lapangan Mlarak dalam gelaran Festival Seni Reyog Remaja. Acara ini diselenggarakan dalam rangka melestarikan seni budaya adiluhung Ponorogo di kalangan generasi muda.</p><p>Camat Mlarak mengapresiasi tingginya antusiasme paguyuban seni desa yang terus membina generasi penerus penari Dadak Merak, Warok, dan Jathil sehingga warisan budaya dunia ini tetap lestari dan membanggakan.</p>',
            ],
            [
                'judul' => 'Pelatihan Packaging & Digital Marketing bagi Puluhan Pelaku UMKM Makanan Khas Mlarak',
                'category' => 'Ekonomi & UMKM',
                'gambar' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1200&q=80',
                'konten' => '<p>Seksi Pemberdayaan Masyarakat dan Desa (PMD) Kecamatan Mlarak berkolaborasi dengan Dinas Perdagangan dan Koperasi menyelenggarakan lokakarya desain kemasan higienis serta pemasaran digital melalui marketplace bagi 45 pelaku UMKM olahan pangan lokal seperti keripik tempe, jenang, dan madu klanceng.</p><p>Diharapkan produk UMKM lokal Mlarak dapat menembus pasar ritel modern dan platform online skala nasional guna mendongkrak perekonomian keluarga perdesaan.</p>',
            ],
            [
                'judul' => 'Sinergi Pesantren dan Warga: Ribuan Santri Gontor Gotong Royong Bersihkan Sungai Bersama Warga Desa',
                'category' => 'Pendidikan & Keagamaan',
                'gambar' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80',
                'konten' => '<p>Wujud keharmonisan antara institusi pendidikan pesantren dan masyarakat perdesaan terpancar dalam aksi bersih lingkungan di sepanjang bantaran Sungai Desa Gontor dan Desa Gandu. Kegiatan ini melibatkan ribuan santri bersama warga dan aparat TNI-Polri.</p><p>Aksi ini sekaligus menjadi langkah mitigasi pencegahan genangan air pada musim hujan serta memupuk semangat kepedulian lingkungan sejak dini.</p>',
            ],
        ];

        foreach ($articles as $index => $art) {
            Article::create([
                'judul' => $art['judul'],
                'slug' => Str::slug($art['judul']),
                'konten' => $art['konten'],
                'gambar' => $art['gambar'],
                'category_id' => $catModels[$art['category']]->id,
                'user_id' => $superAdmin->id,
                'status' => 'published',
                'published_at' => now()->subDays($index * 2),
            ]);
        }

        // 5. Villages (15 Desa di Kecamatan Mlarak)
        $villages = [
            ['nama' => 'Mlarak', 'kepala_desa' => 'Budi Santoso', 'penduduk' => 3120, 'luas' => '2.45 km²', 'deskripsi' => 'Desa Mlarak merupakan pusat pemerintahan ibu kota kecamatan yang memiliki sentra perdagangan, perkantoran, dan fasilitas umum.'],
            ['nama' => 'Bajang', 'kepala_desa' => 'H. Suwandi', 'penduduk' => 2840, 'luas' => '3.10 km²', 'deskripsi' => 'Desa Bajang memiliki lahan persawahan subur dengan produktivitas padi tinggi dan tradisi kerukunan warga yang kokoh.'],
            ['nama' => 'Candi', 'kepala_desa' => 'Sutrisno, S.Pd', 'penduduk' => 2450, 'luas' => '2.15 km²', 'deskripsi' => 'Desa Candi dikenal dengan potensi industri bata merah tradisional dan peternakan sapi potong yang berkembang pesat.'],
            ['nama' => 'Gontor', 'kepala_desa' => 'Drs. H. Ahmad Fauzi', 'penduduk' => 4500, 'luas' => '3.80 km²', 'deskripsi' => 'Desa Gontor merupakan ikon pendidikan internasional berkat keberadaan Pondok Modern Darussalam Gontor dan UNIDA.'],
            ['nama' => 'Jabung', 'kepala_desa' => 'Supriyanto', 'penduduk' => 2680, 'luas' => '2.90 km²', 'deskripsi' => 'Desa Jabung memiliki kekayaan kuliner legendaris Es Dawet Jabung yang telah tersohor hingga luar daerah.'],
            ['nama' => 'Joresan', 'kepala_desa' => 'M. Thohir, M.Pd', 'penduduk' => 3210, 'luas' => '3.05 km²', 'deskripsi' => 'Desa Joresan memiliki pusat pendidikan madrasah bersejarah serta pusat produksi olahan pangan lokal berdaya saing.'],
            ['nama' => 'Kaponan', 'kepala_desa' => 'Sunarno', 'penduduk' => 2950, 'luas' => '2.75 km²', 'deskripsi' => 'Desa Kaponan merupakan desa perlintasan strategis dengan potensi pertokoan dan industri kerajinan mebel kayu jati.'],
            ['nama' => 'Ngrukem', 'kepala_desa' => 'Drs. Subandi', 'penduduk' => 2340, 'luas' => '2.20 km²', 'deskripsi' => 'Desa Ngrukem unggul dalam budidaya tanaman hortikultura seperti cabai, melon, dan sayuran organik.'],
            ['nama' => 'Nglumpang', 'kepala_desa' => 'Sugeng Pribadi', 'penduduk' => 2190, 'luas' => '2.10 km²', 'deskripsi' => 'Desa Nglumpang memiliki suasana perdesaan yang asri dan guyub dengan paguyuban kesenian karawitan aktif.'],
            ['nama' => 'Serangan', 'kepala_desa' => 'Agus Wahyudi', 'penduduk' => 2750, 'luas' => '2.65 km²', 'deskripsi' => 'Desa Serangan terkenal dengan sentra kerajinan anyaman bambu dan produk olahan pertanian terpadu.'],
            ['nama' => 'Siwalan', 'kepala_desa' => 'Mohammad Ridwan', 'penduduk' => 2580, 'luas' => '2.50 km²', 'deskripsi' => 'Desa Siwalan memiliki kelompok tani teladan yang berhasil mengoptimalkan sistem irigasi hemat air sumur dalam.'],
            ['nama' => 'Suren', 'kepala_desa' => 'H. Partono', 'penduduk' => 2410, 'luas' => '2.30 km²', 'deskripsi' => 'Desa Suren kaya akan potensi peternakan kambing dan perkebunan sengon serta pohon buah-buahan lokal.'],
            ['nama' => 'Turen', 'kepala_desa' => 'Suryanto', 'penduduk' => 2130, 'luas' => '1.95 km²', 'deskripsi' => 'Desa Turen memiliki sentra produksi tempe higienis dan keripik khas Ponorogo yang dipasarkan di pasar kabupaten.'],
            ['nama' => 'Totokan', 'kepala_desa' => 'Haryanto, S.Sos', 'penduduk' => 2640, 'luas' => '2.80 km²', 'deskripsi' => 'Desa Totokan memiliki lanskap persawahan asri dan kelompok sadar wisata desa yang aktif menggelar festival.'],
            ['nama' => 'Gandu', 'kepala_desa' => 'Drs. Ali Mustofa', 'penduduk' => 2850, 'luas' => '2.70 km²', 'deskripsi' => 'Desa Gandu berbatasan dengan Gontor dan memiliki pondok pesantren cabang serta sentra peternakan itik petelur.'],
        ];

        foreach ($villages as $idx => $v) {
            Village::create([
                'nama' => 'Desa ' . $v['nama'],
                'slug' => Str::slug('Desa ' . $v['nama']),
                'kepala_desa' => $v['kepala_desa'],
                'jumlah_penduduk' => $v['penduduk'],
                'luas_wilayah' => $v['luas'],
                'deskripsi' => $v['deskripsi'],
                'foto' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=800&q=80',
            ]);
        }

        // 6. Services (Layanan PATEN)
        $services = [
            [
                'nama' => 'Rekomendasi Penerbitan KTP-el dan Kartu Keluarga',
                'syarat' => "1. Surat pengantar dari RT/RW dan Kepala Desa setempat\n2. Fotokopi Kartu Keluarga lama (untuk perubahan data / pembaruan)\n3. Fotokopi Akta Kelahiran atau Ijazah terakhir\n4. Surat Keterangan Hilang dari Polsek setempat (bila KTP/KK hilang)",
                'prosedur' => "1. Pemohon datang ke loket Pelayanan PATEN Kecamatan Mlarak dengan membawa berkas lengkap.\n2. Petugas memverifikasi kelengkapan administrasi dan keabsahan berkas.\n3. Petugas menginput data kependudukan ke dalam sistem database SIAK.\n4. Camat / Kasi Pelayanan menandatangani formulir rekomendasi / dokumen.\n5. Berkas selesai dan diserahkan kepada pemohon tanpa dipungut biaya.",
            ],
            [
                'nama' => 'Surat Keterangan Pindah / Datang WNI (SKPWNI)',
                'syarat' => "1. Surat Pengantar Pindah dari Kepala Desa asal\n2. Kartu Keluarga asli dan KTP-el asli pemohon\n3. Pasfoto 4x6 sebanyak 2 lembar\n4. Alamat tujuan pindah yang jelas dan lengkap",
                'prosedur' => "1. Warga mendaftar di loket kependudukan kecamatan.\n2. Verifikasi berkas oleh staf pelayanan umum.\n3. Penerbitan lembar Surat Keterangan Pindah Antar-Kecamatan atau rekomendasi ke Disdukcapil Ponorogo.\n4. Penyerahan dokumen resmi kepada pemohon.",
            ],
            [
                'nama' => 'Rekomendasi Surat Keterangan Usaha Mikro dan Kecil (IUMK)',
                'syarat' => "1. Surat pengantar RT/RW dan Kepala Desa yang menerangkan keberadaan usaha\n2. Fotokopi KTP-el dan Kartu Keluarga pemohon\n3. Foto lokasi usaha dan tempat produksi\n4. Denah sederhana lokasi tempat usaha",
                'prosedur' => "1. Pemohon mengajukan permohonan rekomendasi IUMK di loket Seksi PMD / Pelayanan Umum.\n2. Petugas melakukan pengecekan administrasi dan koordinasi lapangan bila diperlukan.\n3. Camat menandatangani surat rekomendasi IUMK.\n4. Pemohon menerima surat rekomendasi untuk pengajuan perizinan OSS atau perbankan.",
            ],
            [
                'nama' => 'Rekomendasi Izin Keramaian dan Pentas Seni Rakyat',
                'syarat' => "1. Surat pengantar dari Kepala Desa setempat\n2. Surat izin penggunaan tempat atau fasilitas umum\n3. Susunan panitia acara dan estimasi jumlah pengunjung\n4. Fotokopi KTP penanggung jawab kegiatan",
                'prosedur' => "1. Pengajuan berkas minimal 7 hari kerja sebelum hari pelaksanaan acara.\n2. Seksi Ketentraman dan Ketertiban (Trantib) mengkaji aspek keamanan dan kelayakan jalur lalu lintas.\n3. Camat menerbitkan surat rekomendasi keramaian untuk diteruskan ke Polsek Mlarak dan Koramil.",
            ],
            [
                'nama' => 'Pelayanan Legalisir Dokumen Kependudukan & Umum',
                'syarat' => "1. Membawa dokumen asli yang hendak dilegalisir\n2. Fotokopi dokumen yang akan dilegalisir maksimal 5 lembar\n3. Fotokopi identitas pemohon",
                'prosedur' => "1. Serahkan dokumen asli dan fotokopi ke loket pelayanan.\n2. Petugas membandingkan kecocokan antara salinan dan dokumen asli.\n3. Pembubuhan stempel legalisir dan paraf pejabat berwenang.\n4. Dokumen selesai diproses dalam 10-15 menit.",
            ],
        ];

        foreach ($services as $srv) {
            Service::create([
                'nama_layanan' => $srv['nama'],
                'slug' => Str::slug($srv['nama']),
                'syarat' => $srv['syarat'],
                'prosedur' => $srv['prosedur'],
                'file_sop' => 'downloads/sop_pelayanan_paten_mlarak.pdf',
            ]);
        }

        // 7. Officials (Struktur Organisasi)
        $officials = [
            ['nama' => 'Drs. H. Bambang Sujarwo, M.Si', 'jabatan' => 'Camat Mlarak', 'urutan' => 1, 'foto' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&q=80'],
            ['nama' => 'Wahyu Hidayat, S.Sos, M.M', 'jabatan' => 'Sekretaris Kecamatan (Sekcam)', 'urutan' => 2, 'foto' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80'],
            ['nama' => 'Endang Sulistyowati, S.IP', 'jabatan' => 'Kasi Tata Pemerintahan', 'urutan' => 3, 'foto' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80'],
            ['nama' => 'Kapten (Purn) Sunardi, S.H', 'jabatan' => 'Kasi Ketentraman & Ketertiban', 'urutan' => 4, 'foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80'],
            ['nama' => 'Dra. Siti Rahmawati', 'jabatan' => 'Kasi Kesejahteraan Sosial', 'urutan' => 5, 'foto' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80'],
            ['nama' => 'Ir. Agus Supriyanto', 'jabatan' => 'Kasi Pemberdayaan Masyarakat & Desa', 'urutan' => 6, 'foto' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80'],
            ['nama' => 'Rina Wijayanti, S.E', 'jabatan' => 'Kasi Pelayanan Umum (PATEN)', 'urutan' => 7, 'foto' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80'],
        ];

        foreach ($officials as $off) {
            Official::create($off);
        }

        // 8. Download Categories & Downloads
        $dlCats = [
            'Formulir Pelayanan',
            'Regulasi & SK Camat',
            'Laporan Kinerja & Akuntabilitas',
            'Informasi Publik Berkala',
        ];

        $dlCatModels = [];
        foreach ($dlCats as $dc) {
            $dlCatModels[$dc] = DownloadCategory::create([
                'nama' => $dc,
                'slug' => Str::slug($dc),
            ]);
        }

        $downloads = [
            [
                'judul' => 'SOP Standar Pelayanan Administrasi Terpadu Kecamatan (PATEN) Mlarak',
                'category' => 'Formulir Pelayanan',
                'file' => 'downloads/sop_pelayanan_paten_mlarak.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => '440 KB',
                'unduhan' => 128,
            ],
            [
                'judul' => 'Formulir Permohonan Rekomendasi Surat Keterangan Pindah (F-1.08)',
                'category' => 'Formulir Pelayanan',
                'file' => 'downloads/sop_pelayanan_paten_mlarak.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => '320 KB',
                'unduhan' => 95,
            ],
            [
                'judul' => 'Keputusan Camat Mlarak tentang Penetapan Desa Binaan Program Pengentasan Kemiskinan',
                'category' => 'Regulasi & SK Camat',
                'file' => 'downloads/sop_pelayanan_paten_mlarak.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => '512 KB',
                'unduhan' => 74,
            ],
            [
                'judul' => 'Laporan Kinerja Instansi Pemerintah (LKjIP / LAKIP) Kecamatan Mlarak Tahun Anggaran 2025',
                'category' => 'Laporan Kinerja & Akuntabilitas',
                'file' => 'downloads/sop_pelayanan_paten_mlarak.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => '1.2 MB',
                'unduhan' => 162,
            ],
            [
                'judul' => 'Rekapitulasi Data Monografi dan Kependudukan Kecamatan Mlarak Semester II',
                'category' => 'Informasi Publik Berkala',
                'file' => 'downloads/sop_pelayanan_paten_mlarak.pdf',
                'tipe_file' => 'pdf',
                'ukuran_file' => '780 KB',
                'unduhan' => 210,
            ],
        ];

        foreach ($downloads as $d) {
            Download::create([
                'judul' => $d['judul'],
                'download_category_id' => $dlCatModels[$d['category']]->id,
                'file' => $d['file'],
                'tipe_file' => $d['tipe_file'],
                'ukuran_file' => $d['ukuran_file'],
                'jumlah_unduhan' => $d['unduhan'],
                'status' => true,
            ]);
        }

        // 9. Galleries
        $galleries = [
            [
                'judul' => 'Pelaksanaan Musrenbangcam Mlarak Tahun Anggaran 2026',
                'tipe' => 'foto',
                'file' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=1200&q=80',
                'deskripsi' => 'Rapat koordinasi dan sinkronisasi usulan rencana kerja pembangunan 15 desa.',
            ],
            [
                'judul' => 'Gelar Seni Reyog Ponorogo Pelajar di Lapangan Mlarak',
                'tipe' => 'foto',
                'file' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80',
                'deskripsi' => 'Pementasan Dadak Merak dan penari Jathil dalam semarak kebudayaan daerah.',
            ],
            [
                'judul' => 'Pelayanan Perekaman KTP-el Jemput Bola di Desa Candi',
                'tipe' => 'foto',
                'file' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1200&q=80',
                'deskripsi' => 'Petugas PATEN melayani perekaman identitas langsung ke rumah warga disabilitas.',
            ],
            [
                'judul' => 'Bazar Produk UMKM Unggulan Olahan Pangan Mlarak',
                'tipe' => 'foto',
                'file' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=1200&q=80',
                'deskripsi' => 'Pameran aneka makanan khas dan hasil kerajinan warga 15 desa.',
            ],
            [
                'judul' => 'Kegiatan Kerja Bakti Lingkungan Terpadu Bersama Warga dan Santri',
                'tipe' => 'foto',
                'file' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1200&q=80',
                'deskripsi' => 'Gotong royong membersihkan saluran air dan penanaman pohon penghijauan.',
            ],
            [
                'judul' => 'Video Profil Kecamatan Mlarak Kabupaten Ponorogo',
                'tipe' => 'video',
                'file' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'deskripsi' => 'Dokumenter potensi wilayah, kebudayaan, dan profil pelayanan publik Kecamatan Mlarak.',
            ],
        ];

        foreach ($galleries as $g) {
            Gallery::create($g);
        }

        // 10. Menus & MenuPages (Dinamis)
        // Menu 1: Beranda
        Menu::create([
            'nama_menu' => 'Beranda',
            'slug' => 'beranda',
            'tipe' => 'module',
            'url' => '/',
            'parent_id' => null,
            'urutan' => 1,
            'status' => true,
        ]);

        // Menu 2: Profil (Induk dengan Submenu)
        $menuProfil = Menu::create([
            'nama_menu' => 'Profil',
            'slug' => 'profil',
            'tipe' => 'module',
            'url' => '/profil',
            'parent_id' => null,
            'urutan' => 2,
            'status' => true,
        ]);

        // Submenu Profil
        $subVisi = Menu::create([
            'nama_menu' => 'Visi & Misi',
            'slug' => 'visi-dan-misi',
            'tipe' => 'module',
            'url' => '/profil#visimisi',
            'parent_id' => $menuProfil->id,
            'urutan' => 1,
            'status' => true,
        ]);
        MenuPage::create([
            'menu_id' => $subVisi->id,
            'konten' => '<h3>Visi Kecamatan Mlarak</h3><p><em>"Terwujudnya Pelayanan Publik Kecamatan Mlarak yang Profesional, Transparan, Akuntabel, dan Berkelanjutan Menuju Ponorogo Hebat."</em></p><h3>Misi Kecamatan Mlarak</h3><ol><li>Meningkatkan kualitas tata kelola birokrasi pemerintahan kecamatan yang responsif, adaptif, dan akuntabel.</li><li>Mengoptimalkan pelayanan administrasi terpadu kecamatan (PATEN) berbasis digital demi kepuasan masyarakat.</li><li>Mendorong percepatan pembangunan desa dan pemberdayaan ekonomi masyarakat berbasis potensi pertanian & UMKM.</li><li>Memperkuat sinergi ketentraman, ketertiban umum, serta pelestarian nilai budaya dan kearifan lokal Ponorogo.</li></ol>',
            'gambar' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1000&q=80',
        ]);

        $subSejarah = Menu::create([
            'nama_menu' => 'Sejarah Kecamatan',
            'slug' => 'sejarah-kecamatan',
            'tipe' => 'module',
            'url' => '/profil#sejarah',
            'parent_id' => $menuProfil->id,
            'urutan' => 2,
            'status' => true,
        ]);
        MenuPage::create([
            'menu_id' => $subSejarah->id,
            'konten' => '<p>Kecamatan Mlarak memiliki sejarah panjang sebagai salah satu wilayah penyangga peradaban spiritual dan kebudayaan di Kabupaten Ponorogo.</p><p>Memiliki lahan agraris subur beririgasi teknis dan tradisi gotong royong yang kuat, Mlarak dikenal secara nasional dan global antara lain melalui keberadaan Pondok Modern Darussalam Gontor di Desa Gontor yang didirikan pada tahun 1926. Sepanjang perjalanan sejarah Ponorogo, Mlarak berperan aktif sebagai pusat transmisi keilmuan, sentra kerajinan, dan lumbung pangan yang tangguh di Jawa Timur.</p>',
            'gambar' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1000&q=80',
        ]);

        Menu::create([
            'nama_menu' => 'Struktur Organisasi',
            'slug' => 'struktur-organisasi',
            'tipe' => 'module',
            'url' => '/profil#struktur',
            'parent_id' => $menuProfil->id,
            'urutan' => 3,
            'status' => true,
        ]);

        // Menu 3: Berita
        Menu::create([
            'nama_menu' => 'Berita',
            'slug' => 'berita',
            'tipe' => 'module',
            'url' => '/berita',
            'parent_id' => null,
            'urutan' => 3,
            'status' => true,
        ]);

        // Menu 4: Data Desa
        Menu::create([
            'nama_menu' => 'Data Desa',
            'slug' => 'data-desa',
            'tipe' => 'module',
            'url' => '/data-desa',
            'parent_id' => null,
            'urutan' => 4,
            'status' => true,
        ]);

        // Menu 5: Layanan Publik
        Menu::create([
            'nama_menu' => 'Layanan Publik',
            'slug' => 'layanan',
            'tipe' => 'module',
            'url' => '/layanan',
            'parent_id' => null,
            'urutan' => 5,
            'status' => true,
        ]);

        // Menu 6: Download Berkas
        Menu::create([
            'nama_menu' => 'Download',
            'slug' => 'download',
            'tipe' => 'module',
            'url' => '/download',
            'parent_id' => null,
            'urutan' => 6,
            'status' => true,
        ]);

        // Menu 7: Galeri
        Menu::create([
            'nama_menu' => 'Galeri',
            'slug' => 'galeri',
            'tipe' => 'module',
            'url' => '/galeri',
            'parent_id' => null,
            'urutan' => 7,
            'status' => true,
        ]);

        // Menu 8: Statistik
        Menu::create([
            'nama_menu' => 'Statistik',
            'slug' => 'statistik',
            'tipe' => 'module',
            'url' => '/statistik',
            'parent_id' => null,
            'urutan' => 8,
            'status' => true,
        ]);

        // Menu 9: Kontak & Pengaduan
        Menu::create([
            'nama_menu' => 'Kontak',
            'slug' => 'kontak',
            'tipe' => 'module',
            'url' => '/kontak',
            'parent_id' => null,
            'urutan' => 9,
            'status' => true,
        ]);
    }
}
