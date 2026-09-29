<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTinyMceTest extends TestCase
{
    public function test_admin_menu_create_renders_tinymce(): void
    {
        $admin = User::first() ?? User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($admin)->get(route('admin.menus.create'));

        $response->assertStatus(200);
        $response->assertSee('tinymce.init', false);
        $response->assertSee('selector: \'#konten\'', false);
        $response->assertSee('tinymce.min.js', false);
    }

    public function test_admin_menu_edit_renders_tinymce(): void
    {
        $admin = User::first() ?? User::factory()->create(['role' => 'super_admin']);
        $menu = Menu::first() ?? Menu::create([
            'nama_menu' => 'Test Menu',
            'slug' => 'test-menu',
            'tipe' => 'page',
            'urutan' => 1,
            'status' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.menus.edit', $menu->id));

        $response->assertStatus(200);
        $response->assertSee('tinymce.init', false);
        $response->assertSee('selector: \'#konten\'', false);
        $response->assertSee('tinymce.min.js', false);
    }
}
