<?php

namespace Tests\Feature;

use App\Models\Menu;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProfileMenuTest extends TestCase
{
    use DatabaseTransactions;

    public function test_profile_dropdown_menu_targets(): void
    {
        Cache::forget('nav_menus');

        // Check database records
        $visiMenu = Menu::whereIn('slug', ['visi-misi', 'visi-dan-misi'])->first();
        $this->assertNotNull($visiMenu, 'Menu Visi & Misi should exist');
        $this->assertEquals('/profil#visimisi', $visiMenu->url);
        $this->assertStringContainsString('profil#visimisi', $visiMenu->target_url);

        $sejarahMenu = Menu::where('slug', 'sejarah-kecamatan')->first();
        $this->assertNotNull($sejarahMenu, 'Menu Sejarah Kecamatan should exist');
        $this->assertEquals('/profil#sejarah', $sejarahMenu->url);
        $this->assertStringContainsString('profil#sejarah', $sejarahMenu->target_url);

        // Check frontend navigation HTML
        $response = $this->get('/');
        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('profil#visimisi', $content, 'Navbar should contain link to profil#visimisi');
        $this->assertStringContainsString('profil#sejarah', $content, 'Navbar should contain link to profil#sejarah');
    }

    public function test_profile_page_has_matching_anchors(): void
    {
        $response = $this->get('/profil');
        $response->assertStatus(200);

        $content = $response->getContent();
        $this->assertStringContainsString('id="visimisi"', $content, 'Profile page must have id="visimisi" section');
        $this->assertStringContainsString('id="sejarah"', $content, 'Profile page must have id="sejarah" section');
        $this->assertStringContainsString('id="struktur"', $content, 'Profile page must have id="struktur" section');
    }

    public function test_legacy_routes_redirect_to_anchors(): void
    {
        $responseVisi = $this->get('/visi-dan-misi');
        $responseVisi->assertRedirect('/profil#visimisi');

        $responseSejarah = $this->get('/sejarah-kecamatan');
        $responseSejarah->assertRedirect('/profil#sejarah');

        $responseStruktur = $this->get('/struktur-organisasi');
        $responseStruktur->assertRedirect('/profil#struktur');
    }
}
