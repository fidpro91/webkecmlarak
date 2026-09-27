<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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
                'logo' => ['image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            ]);
            $currentLogo = Setting::get('logo');
            if ($currentLogo && str_starts_with($currentLogo, 'settings/') && Storage::disk('public')->exists($currentLogo)) {
                Storage::disk('public')->delete($currentLogo);
            }
            $logoPath = $request->file('logo')->store('settings', 'public');
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
                'foto_camat_upload' => ['image', 'mimes:png,jpg,jpeg,webp', 'max:3072'],
            ]);
            $camatPath = $request->file('foto_camat_upload')->store('settings', 'public');
            Setting::set('foto_camat', Storage::url($camatPath));
        }

        if ($request->hasFile('bagan_struktur_organisasi_upload')) {
            $request->validate([
                'bagan_struktur_organisasi_upload' => ['image', 'mimes:png,jpg,jpeg,webp,svg', 'max:10240'],
            ]);
            $currentBagan = Setting::get('bagan_struktur_organisasi');
            if ($currentBagan && str_starts_with($currentBagan, '/storage/settings/') && Storage::disk('public')->exists(str_replace('/storage/', '', $currentBagan))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $currentBagan));
            }
            $baganPath = $request->file('bagan_struktur_organisasi_upload')->store('settings', 'public');
            Setting::set('bagan_struktur_organisasi', Storage::url($baganPath));
        }

        if ($request->boolean('hapus_bagan_struktur')) {
            $currentBagan = Setting::get('bagan_struktur_organisasi');
            if ($currentBagan && str_starts_with($currentBagan, '/storage/settings/') && Storage::disk('public')->exists(str_replace('/storage/', '', $currentBagan))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $currentBagan));
            }
            Setting::set('bagan_struktur_organisasi', null);
        }

        Setting::clearCache();

        return back()->with('success', 'Pengaturan website kecamatan berhasil disimpan dan diperbarui.');
    }
}
