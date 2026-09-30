<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\SocialMediaAccount;
use App\Models\SocialPostLog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArticleHashtagsAndSocialPostTest extends TestCase
{
    use DatabaseTransactions;

    private function getAdmin(): User
    {
        return User::where('role', 'super_admin')->first()
            ?? User::factory()->create(['role' => 'super_admin']);
    }

    private function getCategory(): Category
    {
        return Category::first() ?? Category::create([
            'nama' => 'Pemerintahan',
            'slug' => 'pemerintahan',
        ]);
    }

    public function test_article_create_and_edit_views_render_hashtags_input_and_social_switcher(): void
    {
        $admin = $this->getAdmin();
        $category = $this->getCategory();

        $createResponse = $this->actingAs($admin)->get(route('admin.articles.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Hashtag / Tagar Berita');
        $createResponse->assertSee('Otomatis Post ke Sosial Media');

        $article = Article::create([
            'judul' => 'Berita Hashtag Test ' . uniqid(),
            'slug' => 'berita-hashtag-test-' . uniqid(),
            'konten' => '<p>Konten pengujian hashtag</p>',
            'category_id' => $category->id,
            'user_id' => $admin->id,
            'status' => 'draft',
            'hashtags' => ['#Mlarak', '#Ponorogo'],
        ]);

        $editResponse = $this->actingAs($admin)->get(route('admin.articles.edit', $article->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Hashtag / Tagar Berita');
        $editResponse->assertSee('Otomatis Post ke Sosial Media');
        $editResponse->assertSee('#Mlarak');
    }

    public function test_admin_can_store_article_with_hashtags_and_trigger_auto_post(): void
    {
        $admin = $this->getAdmin();
        $category = $this->getCategory();

        $socialAccount = SocialMediaAccount::create([
            'user_id' => $admin->id,
            'platform' => 'telegram',
            'account_name' => 'Kecamatan Mlarak News',
            'account_id' => '@mlarak_news',
            'access_token' => 'mock-token',
            'is_active' => true,
            'auto_post' => true,
        ]);

        $judul = 'Karnaval Akbar Budaya Mlarak Tahun Ini ' . uniqid();
        $payload = [
            'judul' => $judul,
            'konten' => '<p>Ribuan warga memadati ruas jalan utama kecamatan...</p>',
            'category_id' => $category->id,
            'status' => 'published',
            'hashtags' => '["#KarnavalMlarak", "#PonorogoHebat"]',
            'auto_post_social' => '1',
            'social_channels' => [$socialAccount->id],
        ];

        $response = $this->actingAs($admin)->post(route('admin.articles.store'), $payload);

        $response->assertSessionHas('success');
        $article = Article::where('judul', $judul)->first();
        $this->assertNotNull($article);
        $this->assertContains('#KarnavalMlarak', $article->formatted_hashtags);
        $this->assertContains('#PonorogoHebat', $article->formatted_hashtags);

        // Verify social post log was created
        $log = SocialPostLog::where('article_id', $article->id)->first();
        $this->assertNotNull($log);
        $this->assertEquals('telegram', $log->platform);
    }

    public function test_frontend_article_show_displays_hashtags_and_seo_meta(): void
    {
        $category = $this->getCategory();
        $slug = 'pelayanan-terpadu-administrasi-kecamatan-mlarak-' . uniqid();
        $article = Article::create([
            'judul' => 'Pelayanan Terpadu Administrasi Kecamatan Mlarak',
            'slug' => $slug,
            'konten' => '<p>Pemerintah Kecamatan Mlarak berkomitmen memberikan pelayanan maksimal.</p>',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
            'hashtags' => ['#PelayananPublik', '#MlarakCepat'],
        ]);

        $response = $this->get(route('articles.show', $article->slug));

        $response->assertStatus(200);
        $response->assertSee('Topik Terkait');
        $response->assertSee('PelayananPublik');
        $response->assertSee('MlarakCepat');
        $response->assertSee('article:tag');
        $response->assertSee('keywords');
        $response->assertSee('Kategori Berita');
        $response->assertSee('Berita Terpopuler');
        $response->assertViewHas('categories');
        $response->assertViewHas('recentArticles');
    }

    public function test_frontend_articles_index_can_filter_by_hashtag(): void
    {
        $category = $this->getCategory();
        $uniqueTag = 'DonorUnik' . uniqid();

        $article1 = Article::create([
            'judul' => 'Kegiatan Donor Darah di Balai Desa Siwalan ' . uniqid(),
            'slug' => 'donor-darah-siwalan-' . uniqid(),
            'konten' => '<p>Kegiatan kemanusiaan donor darah...</p>',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
            'hashtags' => ['#' . $uniqueTag, '#PMIPonorogo'],
        ]);

        $article2 = Article::create([
            'judul' => 'Sosialisasi Pertanian Organik Desa Turen ' . uniqid(),
            'slug' => 'pertanian-organik-turen-' . uniqid(),
            'konten' => '<p>Pemberdayaan petani lokal...</p>',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now(),
            'hashtags' => ['#Pertanian', '#KetahananPangan'],
        ]);

        // Filter tag $uniqueTag
        $response = $this->get(route('articles.index', ['tag' => $uniqueTag]));
        $response->assertStatus(200);
        $response->assertSee('Filter aktif:');
        $response->assertViewHas('articles', function ($articles) use ($article1, $article2) {
            return $articles->contains($article1) && !$articles->contains($article2);
        });
    }
}
