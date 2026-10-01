<?php

namespace Tests\Feature;

use App\Models\Slider;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SliderLayoutStyleTest extends TestCase
{
    use DatabaseTransactions;

    protected function getAdminUser(): User
    {
        return User::where('role', 'super_admin')->first()
            ?? User::factory()->create(['role' => 'super_admin']);
    }

    public function test_admin_can_view_slider_create_page_with_layout_style_options(): void
    {
        $admin = $this->getAdminUser();

        $response = $this->actingAs($admin)->get('/admin/sliders/create');

        $response->assertStatus(200);
        $response->assertSee('Tampilan Slider (Layout Style)');
        $response->assertSee('Klasik');
        $response->assertSee('Gradien Lembut');
        $response->assertSee('Split Miring');
        $response->assertSee('Direkomendasikan untuk foto bersama');
        $response->assertSee('Posisi Fokus Foto');
    }

    public function test_admin_can_view_slider_edit_page_with_layout_style_selected(): void
    {
        $admin = $this->getAdminUser();
        $slider = Slider::create([
            'judul' => 'Kegiatan Bersama Pejabat',
            'gambar' => 'sliders/test_slider.jpg',
            'deskripsi' => 'Dokumentasi foto bersama',
            'urutan' => 1,
            'status' => true,
            'layout_style' => 'split_diagonal',
            'focal_point' => 'right',
        ]);

        $response = $this->actingAs($admin)->get('/admin/sliders/' . $slider->id . '/edit');

        $response->assertStatus(200);
        $response->assertSee('Tampilan Slider (Layout Style)');
        $response->assertSee('Split Miring');
        $response->assertSee('Direkomendasikan untuk foto bersama');
    }

    public function test_admin_can_store_slider_with_split_diagonal_layout(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $file = UploadedFile::fake()->image('fotobersama.jpg', 1600, 700);

        $response = $this->actingAs($admin)->post('/admin/sliders', [
            'judul' => 'Foto Bersama Camat & Staf',
            'deskripsi' => 'Pengambilan foto bersama seluruh aparatur',
            'urutan' => 2,
            'status' => 1,
            'layout_style' => 'split_diagonal',
            'focal_point' => 'right',
            'gambar' => $file,
        ]);

        $response->assertRedirect('/admin/sliders');

        $slider = Slider::where('judul', 'Foto Bersama Camat & Staf')->first();
        $this->assertNotNull($slider);
        $this->assertEquals('split_diagonal', $slider->layout_style);
        $this->assertEquals('right', $slider->focal_point);
    }

    public function test_admin_can_update_slider_layout_style(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $slider = Slider::create([
            'judul' => 'Pemandangan Kantor',
            'gambar' => 'sliders/pemandangan.jpg',
            'urutan' => 3,
            'status' => true,
            'layout_style' => 'classic',
            'focal_point' => 'center',
        ]);

        $response = $this->actingAs($admin)->put('/admin/sliders/' . $slider->id, [
            'judul' => 'Pemandangan Kantor Terkini',
            'urutan' => 3,
            'status' => 1,
            'layout_style' => 'gradient_soft',
            'focal_point' => 'left',
        ]);

        $response->assertRedirect('/admin/sliders');

        $slider->refresh();
        $this->assertEquals('gradient_soft', $slider->layout_style);
        $this->assertEquals('left', $slider->focal_point);
    }

    public function test_invalid_layout_style_falls_back_to_classic(): void
    {
        Storage::fake('public');
        $admin = $this->getAdminUser();

        $file = UploadedFile::fake()->image('test.jpg', 1600, 700);

        $response = $this->actingAs($admin)->post('/admin/sliders', [
            'judul' => 'Slider Uji Coba Fallback',
            'urutan' => 4,
            'status' => 1,
            'layout_style' => 'invalid_random_style',
            'gambar' => $file,
        ]);

        $response->assertRedirect('/admin/sliders');

        $slider = Slider::where('judul', 'Slider Uji Coba Fallback')->first();
        $this->assertNotNull($slider);
        $this->assertEquals('classic', $slider->layout_style);
    }

    public function test_slider_index_page_displays_layout_style_badge(): void
    {
        $admin = $this->getAdminUser();

        $slider = Slider::create([
            'judul' => 'Slider Test Badge',
            'gambar' => 'sliders/test.jpg',
            'urutan' => 5,
            'status' => true,
            'layout_style' => 'split_diagonal',
        ]);

        $response = $this->actingAs($admin)->get('/admin/sliders');

        $response->assertStatus(200);
        $response->assertSee('Slider Test Badge');
        $response->assertSee('Split Miring');
    }

    public function test_frontend_home_page_renders_hero_slider_with_layout_modifier_classes(): void
    {
        $sliderClassic = Slider::create([
            'judul' => 'Slide Judul Classic',
            'gambar' => 'sliders/classic.jpg',
            'urutan' => 1,
            'status' => true,
            'layout_style' => 'classic',
        ]);

        $sliderDiagonal = Slider::create([
            'judul' => 'Slide Judul Split Miring',
            'gambar' => 'sliders/diagonal.jpg',
            'urutan' => 2,
            'status' => true,
            'layout_style' => 'split_diagonal',
        ]);

        $sliderGradient = Slider::create([
            'judul' => 'Slide Judul Gradient Soft',
            'gambar' => 'sliders/gradient.jpg',
            'urutan' => 3,
            'status' => true,
            'layout_style' => 'gradient_soft',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('hero--classic');
        $response->assertSee('hero--split-diagonal');
        $response->assertSee('hero--gradient-soft');
        $response->assertSee('hero-diagonal-panel');
        $response->assertSee('hero-overlay--gradient-soft');
        $response->assertSee('Slide Judul Split Miring');
    }
}
