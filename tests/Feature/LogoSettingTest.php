<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LogoSettingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_setting_logourl_returns_logoponorogo_png(): void
    {
        $logoUrl = Setting::logoUrl();
        $this->assertStringContainsString('images/logoponorogo.png', $logoUrl);
    }

    public function test_frontend_renders_logoponorogo(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('images/logoponorogo.png', $content);
        $this->assertStringNotContainsString('wikimedia.org', $content);
    }

    public function test_login_page_renders_logoponorogo(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('images/logoponorogo.png', $content);
        $this->assertStringNotContainsString('wikimedia.org', $content);
    }

    public function test_admin_dashboard_renders_logoponorogo(): void
    {
        $admin = User::where('role', 'super_admin')->first() ?? User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('images/logoponorogo.png', $content);
        $this->assertStringNotContainsString('wikimedia.org', $content);
    }

    public function test_admin_settings_has_logo_field_and_can_upload_custom_logo(): void
    {
        Storage::fake('public');
        $admin = User::where('role', 'super_admin')->first() ?? User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Logo Resmi Instansi (Lambang Daerah)');
        $response->assertSee('logoponorogo.png');

        // Upload custom logo
        $file = UploadedFile::fake()->create('custom-logo.png', 100, 'image/png');
        $updateResp = $this->actingAs($admin)->put('/admin/settings', [
            'instansi_nama' => 'Pemerintah Kecamatan Mlarak',
            'kabupaten' => 'Kabupaten Ponorogo',
            'logo' => $file,
        ]);
        $updateResp->assertSessionHas('success');
        $this->assertStringContainsString('settings/', Setting::get('logo'));

        // Reset logo back to default
        $resetResp = $this->actingAs($admin)->put('/admin/settings', [
            'instansi_nama' => 'Pemerintah Kecamatan Mlarak',
            'kabupaten' => 'Kabupaten Ponorogo',
            'hapus_logo' => '1',
        ]);
        $resetResp->assertSessionHas('success');
        $this->assertEquals('images/logoponorogo.png', Setting::get('logo'));
    }
}
