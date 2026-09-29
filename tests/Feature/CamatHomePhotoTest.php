<?php

namespace Tests\Feature;

use App\Models\Official;
use App\Models\Setting;
use App\Services\BaganSvgService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CamatHomePhotoTest extends TestCase
{
    use DatabaseTransactions;

    public function test_home_page_displays_camat_photo_from_officials(): void
    {
        // Get or create Camat official
        $camat = Official::getCamat();
        if (!$camat) {
            $camat = Official::create([
                'nama' => 'JOKO SETIAWAN, S.STP, M.Si',
                'jabatan' => 'Camat Mlarak',
                'urutan' => 1,
                'foto' => 'officials/test_camat.jpg',
            ]);
        } else {
            $camat->update([
                'foto' => 'officials/test_camat.jpg',
                'nama' => 'Bapak Camat Baru, M.Si',
            ]);
        }

        // Run sync
        BaganSvgService::sync();

        $response = $this->get('/');
        $response->assertStatus(200);

        // Verify that the updated photo and name from officials are displayed on the home page
        $response->assertSee('officials/test_camat.jpg');
        $response->assertSee('Bapak Camat Baru, M.Si');
    }

    public function test_official_camat_syncs_with_setting(): void
    {
        $camat = Official::getCamat();
        if ($camat) {
            $camat->update([
                'nama' => 'Drs. H. Bambang Sujarwo, M.Si',
                'foto' => 'officials/bambang.jpg',
            ]);
            BaganSvgService::sync();

            $this->assertEquals('Drs. H. Bambang Sujarwo, M.Si', Setting::get('nama_camat'));
            $this->assertStringContainsString('officials/bambang.jpg', Setting::get('foto_camat'));
        }
    }
}
