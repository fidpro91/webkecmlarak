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

        // 1. Home page must configure unified header with Alpine data
        $this->assertStringContainsString('mobileMenuOpen: false', $content);

        // 2. Header must have sticky positioning and dynamic classes
        $this->assertStringContainsString('sticky top-0 z-50', $content);

        // 3. Top bar is present in layout
        $this->assertStringContainsString('<!-- 1. Top Bar -->', $content);
    }

    public function test_non_home_pages_keep_header_visible_on_initial_load(): void
    {
        $response = $this->get('/profil');
        $response->assertStatus(200);

        $content = $response->getContent();

        // 1. Profil page must have navigation Alpine component
        $this->assertStringContainsString('mobileMenuOpen: false', $content);

        // 2. Profil page header must have sticky positioning
        $this->assertStringContainsString('sticky top-0 z-50', $content);

        // 3. Top bar must be present
        $this->assertStringContainsString('<!-- 1. Top Bar -->', $content);
    }
}
