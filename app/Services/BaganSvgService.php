<?php

namespace App\Services;

use App\Models\Official;
use App\Models\Setting;
use App\Models\Village;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BaganSvgService
{
    /**
     * Path direktori penyimpanan template kustom.
     */
    public static function getCustomTemplatePath(): string
    {
        return storage_path('app/bagan/custom_template.svg');
    }

    /**
     * Path berkas SVG bagan aktif di direktori public.
     */
    public static function getPublicSvgPath(): string
    {
        return public_path('images/bagan-struktur-organisasi.svg');
    }

    /**
     * Ambil data pejabat dan desa yang telah dipetakan ke slot bagan struktur.
     */
    public static function getMappedData(): array
    {
        $officials = Official::orderBy('urutan', 'asc')->get();
        $namaCamatSetting = Setting::get('nama_camat');
        $instansiNama = Setting::get('instansi_nama') ?? 'Kecamatan Mlarak';
        $kabupaten = Setting::get('kabupaten') ?? 'Kabupaten Ponorogo';

        // 1. Camat
        $camatOfficial = self::findOfficialByKeywords($officials, ['camat', 'kepala kecamatan']);
        $camatNama = $camatOfficial?->nama ?: ($namaCamatSetting ?: 'Drs. H. Bambang Sujarwo, M.Si');
        $camatKet = 'Pembina Tingkat I (IV/b)';

        // 2. Sekcam
        $sekcamOfficial = self::findOfficialByKeywords($officials, ['sekretaris', 'sekcam']);
        $sekcamNama = $sekcamOfficial?->nama ?: 'Wahyu Hidayat, S.Sos, M.M';
        $sekcamKet = 'Penata Tingkat I (III/d)';

        // 3. Seksi Tata Pemerintahan
        $tapemOfficial = self::findOfficialByKeywords($officials, ['pemerintahan', 'tapem']);
        $tapemNama = $tapemOfficial?->nama ?: 'Endang Sulistyowati, S.IP';

        // 4. Seksi Trantibum
        $trantibOfficial = self::findOfficialByKeywords($officials, ['ketentraman', 'trantib', 'ketertiban', 'pol pp']);
        $trantibNama = $trantibOfficial?->nama ?: 'Kapten (Purn) Sunardi, S.H';

        // 5. Seksi Kesejahteraan Sosial
        $kesraOfficial = self::findOfficialByKeywords($officials, ['kesejahteraan', 'kesra', 'sosial']);
        $kesraNama = $kesraOfficial?->nama ?: 'Dra. Siti Rahmawati';

        // 6. Seksi PMD
        $pmdOfficial = self::findOfficialByKeywords($officials, ['pemberdayaan', 'pmd', 'desa']);
        $pmdNama = $pmdOfficial?->nama ?: 'Ir. Agus Supriyanto';

        // 7. Seksi Pelayanan Umum (PATEN)
        $pelayananOfficial = self::findOfficialByKeywords($officials, ['pelayanan', 'paten', 'umum (paten)']);
        $pelayananNama = $pelayananOfficial?->nama ?: 'Rina Wijayanti, S.E';

        // 8. Subbag Perencanaan & Keuangan
        $keuanganOfficial = self::findOfficialByKeywords($officials, ['keuangan', 'perencanaan']);
        $keuanganNama = $keuanganOfficial?->nama ?: 'Penyusunan Anggaran & Pelaporan';

        // 9. Subbag Umum & Kepegawaian
        $umumOfficial = self::findOfficialByKeywords($officials, ['kepegawaian', 'subbag umum']);
        $umumNama = $umumOfficial?->nama ?: 'Tata Usaha, Aset & Personalia';

        // 10. Jabatan Fungsional
        $fungsionalOfficial = self::findOfficialByKeywords($officials, ['fungsional', 'kelompok jabatan']);
        $fungsionalNama = $fungsionalOfficial?->nama ?: 'Auditor, Arsiparis, Analis';

        // 11. Daftar Desa (15 Desa)
        $villages = Village::orderBy('id', 'asc')->pluck('nama')->toArray();
        if (empty($villages)) {
            $villages = [
                'Desa Mlarak', 'Desa Bajang', 'Desa Candi', 'Desa Gontor', 'Desa Jabung',
                'Desa Joresan', 'Desa Kaponan', 'Desa Ngrukem', 'Desa Nglumpang', 'Desa Serangan',
                'Desa Siwalan', 'Desa Suren', 'Desa Turen', 'Desa Totokan', 'Desa Gandu'
            ];
        }

        return [
            'judul_instansi' => 'PEMERINTAH ' . strtoupper($kabupaten),
            'judul_bagan' => 'BAGAN STRUKTUR ORGANISASI ' . strtoupper($instansiNama),
            'camat_nama' => $camatNama,
            'camat_keterangan' => $camatKet,
            'sekcam_nama' => $sekcamNama,
            'sekcam_keterangan' => $sekcamKet,
            'kasi_tapem_nama' => $tapemNama,
            'kasi_tapem_keterangan' => 'Kepala Seksi',
            'kasi_trantib_nama' => $trantibNama,
            'kasi_trantib_keterangan' => 'Kepala Seksi',
            'kasi_kesra_nama' => $kesraNama,
            'kasi_kesra_keterangan' => 'Kepala Seksi',
            'kasi_pmd_nama' => $pmdNama,
            'kasi_pmd_keterangan' => 'Kepala Seksi',
            'kasi_pelayanan_nama' => $pelayananNama,
            'kasi_pelayanan_keterangan' => 'Kepala Seksi',
            'subbag_keuangan_nama' => $keuanganNama,
            'subbag_umum_nama' => $umumNama,
            'fungsional_nama' => $fungsionalNama,
            'fungsional_keterangan' => 'Tenaga Teknis Profesional',
            'villages' => $villages,
        ];
    }

    /**
     * Regenerasi berkas SVG bagan struktur organisasi secara instan.
     * Cepat, aman, dan hemat sumber daya (dijalankan hanya saat ada perubahan data).
     */
    public static function sync(bool $forceStandard = false): bool
    {
        try {
            $data = self::getMappedData();
            $customTemplate = self::getCustomTemplatePath();
            $targetPath = self::getPublicSvgPath();

            File::ensureDirectoryExists(dirname($targetPath));

            // Jika ada custom template dan tidak dipaksa ke standar, gunakan custom template
            if (!$forceStandard && File::exists($customTemplate)) {
                $rawSvg = File::get($customTemplate);
                $renderedSvg = self::applyMappingToSvg($rawSvg, $data);
            } else {
                // Gunakan template blade standar
                $renderedSvg = view('svg.bagan-struktur', $data)->render();
            }

            File::put($targetPath, $renderedSvg);

            // Sinkronkan juga nama camat ke settings agar selalu konsisten
            if (!empty($data['camat_nama'])) {
                Setting::set('nama_camat', $data['camat_nama']);
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('BaganSvgService sync error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return false;
        }
    }

    /**
     * Memproses berkas SVG yang diunggah pengguna secara manual.
     * Melakukan validasi XML aman dan pemetaan slot jabatan secara otomatis.
     */
    public static function processUploadedSvg(UploadedFile $file): array
    {
        // 1. Validasi ekstensi
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext !== 'svg') {
            return [
                'success' => false,
                'message' => 'Berkas harus memiliki ekstensi .svg (Format Vektor SVG). Format lain tidak didukung.',
            ];
        }

        // 2. Baca konten dan validasi XML
        $content = file_get_contents($file->getRealPath());
        if (!$content || !str_contains($content, '<svg')) {
            return [
                'success' => false,
                'message' => 'Berkas SVG yang diunggah tidak valid atau rusak.',
            ];
        }

        // 3. Simpan sebagai custom template
        $templateDir = dirname(self::getCustomTemplatePath());
        File::ensureDirectoryExists($templateDir);
        File::put(self::getCustomTemplatePath(), $content);

        // 4. Lakukan mapping jabatan dengan data database terkini
        $data = self::getMappedData();
        $mappedSvg = self::applyMappingToSvg($content, $data, $mappedCount);

        // 5. Tuliskan hasil akhir ke public/images/bagan-struktur-organisasi.svg
        $publicPath = self::getPublicSvgPath();
        File::ensureDirectoryExists(dirname($publicPath));
        File::put($publicPath, $mappedSvg);

        // 6. Tandai di Setting bahwa bagan kustom aktif
        Setting::set('bagan_struktur_organisasi', asset('images/bagan-struktur-organisasi.svg'));
        Setting::clearCache();

        $infoMsg = $mappedCount > 0
            ? "Berkas SVG berhasil diunggah! Sistem berhasil memetakan {$mappedCount} posisi jabatan secara otomatis."
            : "Berkas SVG berhasil diunggah. Teks bagan ditampilkan sesuai berkas asli Anda. Gunakan Template Standar untuk pemetaan ID otomatis.";

        return [
            'success' => true,
            'mapped_count' => $mappedCount,
            'message' => $infoMsg,
        ];
    }

    /**
     * Menerapkan pemetaan nama dan jabatan ke dalam dokumen SVG.
     * Menggunakan DOMDocument (aman dari XXE) serta substitusi token placeholder.
     */
    public static function applyMappingToSvg(string $svgContent, array $data, ?int &$mappedCount = 0): string
    {
        $mappedCount = 0;

        // Peta ID elemen XML ke nilai data
        $idMappings = [
            'nama-camat' => $data['camat_nama'] ?? null,
            'ket-camat' => $data['camat_keterangan'] ?? null,
            'nama-sekcam' => $data['sekcam_nama'] ?? null,
            'ket-sekcam' => $data['sekcam_keterangan'] ?? null,
            'nama-kasi-tapem' => $data['kasi_tapem_nama'] ?? null,
            'ket-kasi-tapem' => $data['kasi_tapem_keterangan'] ?? null,
            'nama-kasi-trantib' => $data['kasi_trantib_nama'] ?? null,
            'ket-kasi-trantib' => $data['kasi_trantib_keterangan'] ?? null,
            'nama-kasi-kesra' => $data['kasi_kesra_nama'] ?? null,
            'ket-kasi-kesra' => $data['kasi_kesra_keterangan'] ?? null,
            'nama-kasi-pmd' => $data['kasi_pmd_nama'] ?? null,
            'ket-kasi-pmd' => $data['kasi_pmd_keterangan'] ?? null,
            'nama-kasi-pelayanan' => $data['kasi_pelayanan_nama'] ?? null,
            'ket-kasi-pelayanan' => $data['kasi_pelayanan_keterangan'] ?? null,
            'nama-subbag-keuangan' => $data['subbag_keuangan_nama'] ?? null,
            'nama-subbag-umum' => $data['subbag_umum_nama'] ?? null,
            'nama-fungsional' => $data['fungsional_nama'] ?? null,
            'ket-fungsional' => $data['fungsional_keterangan'] ?? null,
        ];

        // Tambahkan mapping 15 desa (desa-1 s/d desa-15)
        if (!empty($data['villages']) && is_array($data['villages'])) {
            foreach ($data['villages'] as $idx => $villageName) {
                $idMappings['desa-' . ($idx + 1)] = ($idx + 1) . '. ' . $villageName;
            }
        }

        // Peta Placeholder Token: {{ CAMAT_NAMA }}, dll.
        $tokenMappings = [
            '{{ CAMAT_NAMA }}' => $data['camat_nama'] ?? '',
            '{{ NAMA_CAMAT }}' => $data['camat_nama'] ?? '',
            '{{ SEKCAM_NAMA }}' => $data['sekcam_nama'] ?? '',
            '{{ NAMA_SEKCAM }}' => $data['sekcam_nama'] ?? '',
            '{{ KASI_TAPEM_NAMA }}' => $data['kasi_tapem_nama'] ?? '',
            '{{ NAMA_KASI_TAPEM }}' => $data['kasi_tapem_nama'] ?? '',
            '{{ KASI_TRANTIB_NAMA }}' => $data['kasi_trantib_nama'] ?? '',
            '{{ NAMA_KASI_TRANTIB }}' => $data['kasi_trantib_nama'] ?? '',
            '{{ KASI_KESRA_NAMA }}' => $data['kasi_kesra_nama'] ?? '',
            '{{ NAMA_KASI_KESRA }}' => $data['kasi_kesra_nama'] ?? '',
            '{{ KASI_PMD_NAMA }}' => $data['kasi_pmd_nama'] ?? '',
            '{{ NAMA_KASI_PMD }}' => $data['kasi_pmd_nama'] ?? '',
            '{{ KASI_PELAYANAN_NAMA }}' => $data['kasi_pelayanan_nama'] ?? '',
            '{{ NAMA_KASI_PELAYANAN }}' => $data['kasi_pelayanan_nama'] ?? '',
            '{{ SUBBAG_KEUANGAN_NAMA }}' => $data['subbag_keuangan_nama'] ?? '',
            '{{ SUBBAG_UMUM_NAMA }}' => $data['subbag_umum_nama'] ?? '',
            '{{ FUNGSIONAL_NAMA }}' => $data['fungsional_nama'] ?? '',
        ];

        // Tahap 1: Eksekusi Token Replacements jika ada
        foreach ($tokenMappings as $token => $val) {
            if (str_contains($svgContent, $token)) {
                $svgContent = str_replace($token, htmlspecialchars($val, ENT_XML1, 'UTF-8'), $svgContent);
                $mappedCount++;
            }
        }

        // Tahap 2: Eksekusi DOM ID Mapping
        libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'UTF-8');
        // LIBXML_NONET untuk keamanan dari eksploitasi eksternal
        $loaded = $dom->loadXML($svgContent, LIBXML_NONET | LIBXML_NOBLANKS);

        if ($loaded) {
            $xpath = new DOMXPath($dom);

            foreach ($idMappings as $id => $val) {
                if (empty($val)) {
                    continue;
                }

                // Cari elemen dengan ID
                $elements = $xpath->query("//*[@id='{$id}']");
                if ($elements && $elements->length > 0) {
                    /** @var DOMElement $el */
                    $el = $elements->item(0);
                    $el->nodeValue = htmlspecialchars($val, ENT_XML1, 'UTF-8');
                    $mappedCount++;
                }
            }

            $output = $dom->saveXML();
            libxml_clear_errors();

            if ($output) {
                return $output;
            }
        }

        libxml_clear_errors();
        return $svgContent;
    }

    /**
     * Reset bagan kustom dan kembali ke bagan standar resmi sistem.
     */
    public static function resetToStandard(): void
    {
        $customTemplate = self::getCustomTemplatePath();
        if (File::exists($customTemplate)) {
            File::delete($customTemplate);
        }

        Setting::set('bagan_struktur_organisasi', null);
        Setting::clearCache();

        self::sync(true);
    }

    /**
     * Helper untuk mencari pejabat berdasarkan kata kunci pada nama jabatan.
     */
    private static function findOfficialByKeywords(Collection $officials, array $keywords): ?Official
    {
        foreach ($officials as $official) {
            $jabatan = strtolower($official->jabatan);
            foreach ($keywords as $keyword) {
                if (str_contains($jabatan, strtolower($keyword))) {
                    return $official;
                }
            }
        }
        return null;
    }
}
