<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\BaganSvgService;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $settings = Setting::allKeyed();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $textFields = [
            'instansi_nama',
            'kabupaten',
            'provinsi',
            'slogan',
            'alamat',
            'telepon',
            'email',
            'whatsapp',
            'jam_kerja',
            'facebook',
            'instagram',
            'youtube',
            'video_profil_youtube',
            'maps_embed',
            'visi',
            'misi',
            'sejarah',
            'sambutan_camat',
            'nama_camat',
        ];

        foreach ($textFields as $field) {
            Setting::set($field, $request->input($field));
        }

        // Handle file uploads for logo and foto_camat
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => ['image', 'mimes:png,jpg,jpeg,svg,webp', 'max:10240'],
            ]);
            $currentLogo = Setting::get('logo');
            if ($currentLogo && str_starts_with($currentLogo, 'settings/') && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            $logoPath = ImageService::compressAndStore($request->file('logo'), 'settings', 600, 600);
            Setting::set('logo', $logoPath);
        }

        if ($request->boolean('hapus_logo')) {
            $currentLogo = Setting::get('logo');
            if ($currentLogo && str_starts_with($currentLogo, 'settings/') && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            Setting::set('logo', 'images/logoponorogo.png');
        }

        if ($request->hasFile('foto_camat_upload')) {
            $request->validate([
                'foto_camat_upload' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:10240'],
            ]);
            $camatPath = ImageService::compressAndStore($request->file('foto_camat_upload'), 'settings', 800, 1000);
            Setting::set('foto_camat', Storage::url($camatPath));
        }

        if ($request->hasFile('bagan_struktur_organisasi_upload')) {
            $request->validate([
                'bagan_struktur_organisasi_upload' => ['file', 'mimes:svg', 'max:5120'],
            ], [
                'bagan_struktur_organisasi_upload.mimes' => 'Berkas bagan harus berupa gambar vektor berformat SVG (.svg). Format bitmap (PNG/JPG) tidak didukung agar sistem dapat memetakan jabatan secara otomatis.',
                'bagan_struktur_organisasi_upload.max' => 'Ukuran berkas SVG maksimal adalah 5 MB.',
            ]);

            $result = BaganSvgService::processUploadedSvg($request->file('bagan_struktur_organisasi_upload'));
            if (!$result['success']) {
                return back()->withErrors(['bagan_struktur_organisasi_upload' => $result['message']]);
            }
        }

        if ($request->boolean('hapus_bagan_struktur')) {
            BaganSvgService::resetToStandard();
        } else {
            // Sinkronkan data bagan dengan pengaturan nama camat / instansi terbaru
            BaganSvgService::sync();
        }

        Setting::clearCache();

        return back()->with('success', 'Pengaturan website kecamatan berhasil disimpan dan diperbarui.');
    }
}
