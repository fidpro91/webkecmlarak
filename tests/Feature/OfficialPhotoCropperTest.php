<?php

namespace Tests\Feature;

use App\Models\Official;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OfficialPhotoCropperTest extends TestCase
{
    use DatabaseTransactions;

    protected function getAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'super_admin',
        ]);
    }

    public function test_admin_can_view_official_create_page_with_cropper_components(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/officials/create');

        $response->assertStatus(200);
        $response->assertSee('Foto Pejabat', false);
        $response->assertSee('Lingkaran (Profil)', false);
        $response->assertSee('Rounded Card', false);
        $response->assertSee('Sesuaikan Posisi & Potongan Foto Pejabat', false);
        $response->assertSee('cropper-modal', false);
        $response->assertSee('vendor/cropperjs/cropper.min.js', false);
    }

    public function test_admin_can_view_official_edit_page_with_existing_photo(): void
    {
        $admin = $this->getAdminUser();
        $official = Official::create([
            'nama' => 'Drs. H. Bambang Sujarwo, M.Si',
            'jabatan' => 'Camat Mlarak',
            'urutan' => 1,
            'foto' => 'officials/test_camat.jpg',
        ]);

        $response = $this->actingAs($admin)->get('/admin/officials/' . $official->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Foto Pejabat', false);
        $response->assertSee('Ganti Foto', false);
        $response->assertSee('Sesuaikan Posisi (Crop)', false);
        $response->assertSee('officials/test_camat.jpg', false);
    }

    public function test_admin_can_store_official_with_base64_cropped_photo(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        // 1x1 transparent/white pixel png base64
        $fakeBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

        $response = $this->actingAs($admin)->post('/admin/officials', [
            'nama' => 'Drs. Subur Raharjo',
            'jabatan' => 'Sekretaris Kecamatan',
            'urutan' => 2,
            'foto_cropped' => $fakeBase64,
        ]);

        $response->assertRedirect('/admin/officials');

        $official = Official::where('nama', 'Drs. Subur Raharjo')->first();
        $this->assertNotNull($official);
        $this->assertNotNull($official->foto);
        $this->assertStringStartsWith('officials/', $official->foto);

        Storage::disk('public')->assertExists($official->foto);
    }

    public function test_admin_can_store_official_with_regular_uploaded_file(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $file = UploadedFile::fake()->image('pejabat.jpg', 600, 600);

        $response = $this->actingAs($admin)->post('/admin/officials', [
            'nama' => 'Ahmad Fauzi, S.STP',
            'jabatan' => 'Kasi Pemerintahan',
            'urutan' => 3,
            'foto' => $file,
        ]);

        $response->assertRedirect('/admin/officials');

        $official = Official::where('nama', 'Ahmad Fauzi, S.STP')->first();
        $this->assertNotNull($official);
        $this->assertNotNull($official->foto);
        $this->assertStringStartsWith('officials/', $official->foto);

        Storage::disk('public')->assertExists($official->foto);
    }

    public function test_admin_can_update_official_with_new_cropped_photo(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        // Put initial photo
        $oldFile = 'officials/old_photo.jpg';
        Storage::disk('public')->put($oldFile, 'fake content');

        $official = Official::create([
            'nama' => 'Rina Wahyuni, SE',
            'jabatan' => 'Kasi PMD',
            'urutan' => 4,
            'foto' => $oldFile,
        ]);

        $newBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

        $response = $this->actingAs($admin)->put('/admin/officials/' . $official->id, [
            'nama' => 'Rina Wahyuni, SE, MM',
            'jabatan' => 'Kasi PMD',
            'urutan' => 4,
            'foto_cropped' => $newBase64,
        ]);

        $response->assertRedirect('/admin/officials');

        $official->refresh();
        $this->assertEquals('Rina Wahyuni, SE, MM', $official->nama);
        $this->assertNotEquals($oldFile, $official->foto);
        Storage::disk('public')->assertMissing($oldFile);
        Storage::disk('public')->assertExists($official->foto);
    }

    public function test_admin_can_remove_official_photo(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $photoFile = 'officials/tobe_deleted.jpg';
        Storage::disk('public')->put($photoFile, 'fake content');

        $official = Official::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Staf Umum',
            'urutan' => 10,
            'foto' => $photoFile,
        ]);

        $response = $this->actingAs($admin)->put('/admin/officials/' . $official->id, [
            'nama' => 'Budi Santoso',
            'jabatan' => 'Staf Umum',
            'urutan' => 10,
            'hapus_foto' => 1,
        ]);

        $response->assertRedirect('/admin/officials');

        $official->refresh();
        $this->assertNull($official->foto);
        Storage::disk('public')->assertMissing($photoFile);
    }
}
