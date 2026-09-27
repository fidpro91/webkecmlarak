<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HomeHeaderScrollTest extends TestCase
{
    use DatabaseTransactions;

    public function test_home_page_initially_hides_header_until_scrolled(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $content = $response->getContent();

        // 1. Home page must configure isHome as true in Alpine data
        $this->assertStringContainsString('isHome: true', $content);

        // 2. Home page header must have fixed offscreen hidden classes initially
        $this->assertStringContainsString('fixed top-0 left-0 right-0 z-50 -translate-y-full opacity-0 pointer-events-none', $content);

        // 3. Top bar should not be present on home initial load
        $this->assertStringNotContainsString('<!-- 1. Top Bar -->', $content);

        // 4. Flash notification wrapper must not be present when there are no messages, preventing white margin
        $this->assertStringNotContainsString('mt-4', substr($content, 0, strpos($content, '<main')));
    }

    public function test_non_home_pages_keep_header_visible_on_initial_load(): void
    {
        $response = $this->get('/profil');
        $response->assertStatus(200);

        $content = $response->getContent();

        // 1. Profil page must configure isHome as false
        $this->assertStringContainsString('isHome: false', $content);

        // 2. Profil page header must have sticky and visible classes on initial load
        $this->assertStringContainsString('sticky top-0 z-40 bg-white shadow-sm py-4', $content);
        $this->assertStringNotContainsString('fixed top-0 left-0 right-0 z-50 -translate-y-full opacity-0 pointer-events-none', $content);

        // 3. Top bar must be present on non-home pages
        $this->assertStringContainsString('<!-- 1. Top Bar -->', $content);
    }
}
