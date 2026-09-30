<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BaganStrukturTest extends TestCase
{
    use DatabaseTransactions;
    public function test_profile_page_displays_bagan_struktur(): void
    {
        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Bagan Struktur Organisasi');
        $response->assertSee('bagan-struktur-organisasi.svg');
    }

    public function test_profile_page_displays_youtube_iframe_above_visi_misi(): void
    {
        $response = $this->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Video Profil Kecamatan Mlarak');
        $response->assertSee('youtube-nocookie.com/embed');

        $content = $response->getContent();
        $youtubePos = strpos($content, 'Video Profil Kecamatan Mlarak');
        $visiPos = strpos($content, 'Visi Utama');

        $this->assertNotFalse($youtubePos);
        $this->assertNotFalse($visiPos);
        $this->assertLessThan($visiPos, $youtubePos, 'YouTube iframe must be positioned above Visi & Misi');
    }

    public function test_struktur_organisasi_url_redirects_to_profile_anchor(): void
    {
        $response = $this->get('/struktur-organisasi');

        $response->assertRedirect('/profil#struktur');
    }

    public function test_admin_can_view_officials_page_with_bagan_section(): void
    {
        $admin = User::where('role', 'super_admin')->first() ?? User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin/officials');

        $response->assertStatus(200);
        $response->assertSee('Bagan Struktur Organisasi Kecamatan');
    }

    public function test_admin_can_upload_and_delete_bagan_struktur_from_officials(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'super_admin')->first() ?? User::factory()->create(['role' => 'super_admin']);

        // 1. Upload new custom bagan (SVG)
        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><text id="nama-camat">Camat Test</text></svg>';
        $file = UploadedFile::fake()->createWithContent('bagan-baru.svg', $svgContent);

        $response = $this->actingAs($admin)->post('/admin/officials/bagan', [
            'bagan_struktur' => $file,
        ]);

        $response->assertSessionHas('success');
        $this->assertNotNull(Setting::get('bagan_struktur_organisasi'));
        $this->assertStringContainsString('bagan-struktur-organisasi.svg', Setting::get('bagan_struktur_organisasi'));

        // Check on frontend profile
        $frontResponse = $this->get('/profil');
        $frontResponse->assertSee(Setting::get('bagan_struktur_organisasi'));

        // 2. Delete / Reset to standard
        $delResponse = $this->actingAs($admin)->delete('/admin/officials/bagan');
        $delResponse->assertSessionHas('success');
        $this->assertNull(Setting::get('bagan_struktur_organisasi'));
        $this->assertStringContainsString('bagan-struktur-organisasi.svg', Setting::baganStrukturUrl());
    }

    public function test_admin_can_upload_bagan_from_settings_page(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'super_admin')->first() ?? User::factory()->create(['role' => 'super_admin']);

        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100"><text id="nama-camat">Camat Test</text></svg>';
        $file = UploadedFile::fake()->createWithContent('bagan-settings.svg', $svgContent);

        $response = $this->actingAs($admin)->put('/admin/settings', [
            'instansi_nama' => 'Pemerintah Kecamatan Mlarak',
            'kabupaten' => 'Kabupaten Ponorogo',
            'bagan_struktur_organisasi_upload' => $file,
        ]);

        $response->assertSessionHas('success');
        $this->assertNotNull(Setting::get('bagan_struktur_organisasi'));

        // Reset via checkbox
        $resetResponse = $this->actingAs($admin)->put('/admin/settings', [
            'instansi_nama' => 'Pemerintah Kecamatan Mlarak',
            'kabupaten' => 'Kabupaten Ponorogo',
            'hapus_bagan_struktur' => '1',
        ]);

        $resetResponse->assertSessionHas('success');
        $this->assertNull(Setting::get('bagan_struktur_organisasi'));
    }
}
