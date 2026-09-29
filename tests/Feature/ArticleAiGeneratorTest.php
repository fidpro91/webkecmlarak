<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ArticleAiGeneratorTest extends TestCase
{
    use DatabaseTransactions;

    private function getAdmin(): User
    {
        return User::where('role', 'super_admin')->first()
            ?? User::factory()->create(['role' => 'super_admin']);
    }

    public function test_unauthenticated_user_cannot_access_ai_generator(): void
    {
        $response = $this->postJson(route('admin.articles.generate-ai'), [
            'prompt' => 'Tuliskan berita tentang penyaluran bantuan',
        ]);

        $response->assertStatus(401);
    }

    public function test_ai_generator_validates_prompt(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->postJson(route('admin.articles.generate-ai'), [
            'prompt' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['prompt']);
    }

    public function test_ai_generator_returns_error_when_api_key_is_missing(): void
    {
        $admin = $this->getAdmin();
        Config::set('services.gemini.api_key', null);

        $response = $this->actingAs($admin)->postJson(route('admin.articles.generate-ai'), [
            'prompt' => 'Tuliskan berita penyaluran beras di Desa Mlarak',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('API Key Gemini belum disetel', $response->json('message'));
    }

    public function test_ai_generator_successfully_generates_article_content(): void
    {
        $admin = $this->getAdmin();
        Config::set('services.gemini.api_key', 'test-gemini-key');
        Config::set('services.gemini.model', 'gemini-1.5-flash');

        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => "<p>Pemerintah Kecamatan Mlarak menyelenggarakan kegiatan musrenbangcam dengan lancar.</p><p>Camat Mlarak menyampaikan apresiasi atas sinergi seluruh desa.</p>",
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.articles.generate-ai'), [
            'prompt' => 'Tuliskan berita tentang musrenbangcam tahun 2026',
            'judul' => 'Musrenbangcam Mlarak Bahas Pembangunan Terpadu',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertStringContainsString('Pemerintah Kecamatan Mlarak', $response->json('content'));
    }

    public function test_create_and_edit_views_render_ai_generator_button_and_modal(): void
    {
        $admin = $this->getAdmin();
        $cat = Category::first() ?? Category::create(['nama' => 'Pemerintahan', 'slug' => 'pemerintahan']);

        // Check create view
        $createResponse = $this->actingAs($admin)->get(route('admin.articles.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Generate Artikel AI');
        $createResponse->assertSee('ai-generator-modal');
        $createResponse->assertSee('openAiModal()');

        // Check edit view
        $article = Article::create([
            'judul' => 'Berita Pengujian AI',
            'slug' => 'berita-pengujian-ai',
            'konten' => '<p>Konten awal</p>',
            'category_id' => $cat->id,
            'user_id' => $admin->id,
            'status' => 'draft',
        ]);

        $editResponse = $this->actingAs($admin)->get(route('admin.articles.edit', $article->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Generate Artikel AI');
        $editResponse->assertSee('ai-generator-modal');
        $editResponse->assertSee('openAiModal()');
    }
}
