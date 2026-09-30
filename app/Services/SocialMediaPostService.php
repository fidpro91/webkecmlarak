<?php

namespace App\Services;

use App\Models\Article;
use App\Models\SocialMediaAccount;
use App\Models\SocialPostLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SocialMediaPostService
{
    /**
     * Otomatis publikasikan artikel berita ke akun-akun sosial media yang aktif & dipilih.
     *
     * @param Article $article
     * @param array<int|string> $selectedAccountIds Array ID akun sosial media yang dipilih, atau kosong untuk semua yang auto_post
     * @return array<int, array{account_id: int, platform: string, success: bool, message: string}>
     */
    public static function postArticle(Article $article, array $selectedAccountIds = []): array
    {
        $query = SocialMediaAccount::where('is_active', true);

        if (!empty($selectedAccountIds)) {
            $query->whereIn('id', $selectedAccountIds);
        } else {
            $query->where('auto_post', true);
        }

        $accounts = $query->get();
        $results = [];

        if ($accounts->isEmpty()) {
            return $results;
        }

        $articleUrl = route('articles.show', $article->slug);
        $excerpt = Str::limit(strip_tags($article->konten), 180);
        $hashtagsString = implode(' ', $article->formatted_hashtags);

        foreach ($accounts as $account) {
            $res = self::dispatchToPlatform($account, $article, $articleUrl, $excerpt, $hashtagsString);
            $results[] = [
                'account_id' => $account->id,
                'platform' => $account->platform,
                'success' => $res['success'],
                'message' => $res['message'],
            ];

            // Catat ke log
            SocialPostLog::create([
                'article_id' => $article->id,
                'social_media_account_id' => $account->id,
                'platform' => $account->platform,
                'status' => $res['success'] ? 'success' : 'failed',
                'message' => $res['message'],
                'payload_preview' => Str::limit($res['payload'] ?? '', 500),
            ]);

            $account->update([
                'last_posted_at' => now(),
                'last_status' => $res['success'] ? 'Terkirim pada ' . now()->format('d/m/Y H:i') : 'Gagal: ' . Str::limit($res['message'], 50),
            ]);
        }

        return $results;
    }

    /**
     * Kirim postingan ke platform yang dituju sesuai kredensial.
     */
    private static function dispatchToPlatform(
        SocialMediaAccount $account,
        Article $article,
        string $url,
        string $excerpt,
        string $hashtags
    ): array {
        $platform = strtolower($account->platform);

        try {
            switch ($platform) {
                case 'telegram':
                    return self::sendToTelegram($account, $article, $url, $excerpt, $hashtags);

                case 'facebook':
                    return self::sendToFacebook($account, $article, $url, $excerpt, $hashtags);

                case 'twitter':
                case 'x':
                    return self::sendToTwitter($account, $article, $url, $excerpt, $hashtags);

                case 'instagram':
                    return self::sendToInstagram($account, $article, $url, $excerpt, $hashtags);

                case 'whatsapp':
                    return self::sendToWhatsApp($account, $article, $url, $excerpt, $hashtags);

                default:
                    return [
                        'success' => true,
                        'message' => "Postingan disimulasikan untuk platform {$account->platform}.",
                        'payload' => "{$article->judul} - {$url} {$hashtags}",
                    ];
            }
        } catch (\Throwable $e) {
            Log::error("SocialMediaPostService error for {$account->platform}: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'payload' => "{$article->judul} - {$url}",
            ];
        }
    }

    /**
     * Kirim ke Telegram Channel atau Group melalui Telegram Bot API.
     */
    private static function sendToTelegram(
        SocialMediaAccount $account,
        Article $article,
        string $url,
        string $excerpt,
        string $hashtags
    ): array {
        $botToken = $account->access_token;
        $chatId = $account->account_id ?: $account->token_secret;

        if (empty($botToken) || empty($chatId)) {
            return [
                'success' => false,
                'message' => 'Bot Token atau Chat ID Telegram belum diatur.',
                'payload' => '',
            ];
        }

        $caption = "<b>📰 " . htmlspecialchars($article->judul, ENT_QUOTES, 'UTF-8') . "</b>\n\n"
                 . htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') . "\n\n"
                 . "🔗 <a href=\"{$url}\">Baca Selengkapnya di Website Resmi</a>\n\n"
                 . htmlspecialchars($hashtags, ENT_QUOTES, 'UTF-8');

        $imageUrl = $article->gambar_url;
        $isRemoteImage = str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://');

        // Jika gambar berupa URL valid publik, kirim sendPhoto, jika tidak kirim sendMessage
        if ($isRemoteImage && !str_contains($imageUrl, 'localhost') && !str_contains($imageUrl, '127.0.0.1')) {
            $endpoint = "https://api.telegram.org/bot{$botToken}/sendPhoto";
            $response = Http::withoutVerifying()->timeout(15)->post($endpoint, [
                'chat_id' => $chatId,
                'photo' => $imageUrl,
                'caption' => Str::limit($caption, 1024),
                'parse_mode' => 'HTML',
            ]);
        } else {
            $endpoint = "https://api.telegram.org/bot{$botToken}/sendMessage";
            $response = Http::withoutVerifying()->timeout(15)->post($endpoint, [
                'chat_id' => $chatId,
                'text' => $caption,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => false,
            ]);
        }

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Berhasil dipublikasikan ke Channel Telegram!',
                'payload' => $caption,
            ];
        }

        return [
            'success' => false,
            'message' => 'Telegram API Error: ' . ($response->json('description') ?? $response->body()),
            'payload' => $caption,
        ];
    }

    /**
     * Kirim ke Facebook Page.
     */
    private static function sendToFacebook(
        SocialMediaAccount $account,
        Article $article,
        string $url,
        string $excerpt,
        string $hashtags
    ): array {
        $pageAccessToken = $account->access_token;
        $pageId = $account->account_id;

        if (empty($pageAccessToken) || empty($pageId)) {
            return [
                'success' => false,
                'message' => 'Page ID atau Page Access Token Facebook belum diatur.',
                'payload' => '',
            ];
        }

        $message = "{$article->judul}\n\n{$excerpt}\n\nBaca selengkapnya: {$url}\n\n{$hashtags}";

        $endpoint = "https://graph.facebook.com/v19.0/{$pageId}/feed";
        $response = Http::withoutVerifying()->timeout(15)->post($endpoint, [
            'message' => $message,
            'link' => $url,
            'access_token' => $pageAccessToken,
        ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Berhasil dibagikan ke Facebook Page!',
                'payload' => $message,
            ];
        }

        $errorMsg = $response->json('error.message') ?? $response->body();
        return [
            'success' => false,
            'message' => 'Facebook API Error: ' . $errorMsg,
            'payload' => $message,
        ];
    }

    /**
     * Kirim Tweet ke X / Twitter.
     */
    private static function sendToTwitter(
        SocialMediaAccount $account,
        Article $article,
        string $url,
        string $excerpt,
        string $hashtags
    ): array {
        $bearerToken = $account->access_token;

        if (empty($bearerToken)) {
            return [
                'success' => false,
                'message' => 'Bearer Token Twitter/X belum diatur.',
                'payload' => '',
            ];
        }

        $text = Str::limit($article->judul, 160) . "\n\n" . $url . "\n\n" . Str::limit($hashtags, 60);

        $response = Http::withoutVerifying()->timeout(15)
            ->withToken($bearerToken)
            ->post('https://api.twitter.com/2/tweets', [
                'text' => $text,
            ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Berhasil membuat Tweet di akun X (Twitter)!',
                'payload' => $text,
            ];
        }

        return [
            'success' => false,
            'message' => 'Twitter API Error: ' . ($response->json('detail') ?? $response->body()),
            'payload' => $text,
        ];
    }

    /**
     * Kirim ke Instagram Business via Graph API Container.
     */
    private static function sendToInstagram(
        SocialMediaAccount $account,
        Article $article,
        string $url,
        string $excerpt,
        string $hashtags
    ): array {
        $accessToken = $account->access_token;
        $igUserId = $account->account_id;

        if (empty($accessToken) || empty($igUserId)) {
            return [
                'success' => false,
                'message' => 'Instagram User ID atau Access Token belum diatur.',
                'payload' => '',
            ];
        }

        $caption = "{$article->judul}\n\n{$excerpt}\n\nLink artikel di bio atau kunjungi: {$url}\n\n{$hashtags}";

        // Instagram Feed memerlukan image public URL
        $imageUrl = $article->gambar_url;
        if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
            $imageUrl = asset($imageUrl);
        }

        // Buat media container
        $containerRes = Http::withoutVerifying()->timeout(15)->post("https://graph.facebook.com/v19.0/{$igUserId}/media", [
            'image_url' => $imageUrl,
            'caption' => Str::limit($caption, 2200),
            'access_token' => $accessToken,
        ]);

        $creationId = $containerRes->json('id');
        if (!$creationId) {
            return [
                'success' => false,
                'message' => 'Instagram Container Error: ' . ($containerRes->json('error.message') ?? $containerRes->body()),
                'payload' => $caption,
            ];
        }

        // Publikasikan media container
        $publishRes = Http::withoutVerifying()->timeout(15)->post("https://graph.facebook.com/v19.0/{$igUserId}/media_publish", [
            'creation_id' => $creationId,
            'access_token' => $accessToken,
        ]);

        if ($publishRes->successful()) {
            return [
                'success' => true,
                'message' => 'Berhasil dipublikasikan ke Feed Instagram!',
                'payload' => $caption,
            ];
        }

        return [
            'success' => false,
            'message' => 'Instagram Publish Error: ' . ($publishRes->json('error.message') ?? $publishRes->body()),
            'payload' => $caption,
        ];
    }

    /**
     * Kirim ke WhatsApp Gateway / Webhook.
     */
    private static function sendToWhatsApp(
        SocialMediaAccount $account,
        Article $article,
        string $url,
        string $excerpt,
        string $hashtags
    ): array {
        $webhookUrl = $account->webhook_url;
        $apiKey = $account->access_token;
        $targetNumber = $account->account_id;

        if (empty($webhookUrl) && empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'Webhook URL atau API Key WhatsApp Gateway belum disetel.',
                'payload' => '',
            ];
        }

        $message = "*📰 " . $article->judul . "*\n\n"
                 . $excerpt . "\n\n"
                 . "🔗 Baca selengkapnya: {$url}\n\n"
                 . $hashtags;

        $targetEndpoint = $webhookUrl ?: 'https://api.whatsapp.gateway/send';

        $response = Http::withoutVerifying()->timeout(15)
            ->withHeaders([
                'Authorization' => $apiKey ? "Bearer {$apiKey}" : '',
            ])
            ->post($targetEndpoint, [
                'target' => $targetNumber,
                'message' => $message,
                'url' => $url,
            ]);

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Pesan artikel berhasil dikirim ke WhatsApp Gateway!',
                'payload' => $message,
            ];
        }

        return [
            'success' => false,
            'message' => 'WhatsApp Gateway Error: ' . $response->body(),
            'payload' => $message,
        ];
    }

    /**
     * Uji koneksi / validitas kredensial sosial media.
     *
     * @param SocialMediaAccount $account
     * @return array{success: bool, message: string}
     */
    public static function testConnection(SocialMediaAccount $account): array
    {
        $platform = strtolower($account->platform);

        try {
            switch ($platform) {
                case 'telegram':
                    if (empty($account->access_token)) {
                        return ['success' => false, 'message' => 'Bot Token Telegram belum diisi.'];
                    }
                    $res = Http::withoutVerifying()->timeout(10)->get("https://api.telegram.org/bot{$account->access_token}/getMe");
                    if ($res->successful() && $res->json('ok')) {
                        $botUsername = $res->json('result.username');
                        return ['success' => true, 'message' => "Terhubung dengan Bot Telegram: @{$botUsername}."];
                    }
                    return ['success' => false, 'message' => 'Bot Token Telegram tidak valid: ' . ($res->json('description') ?? $res->body())];

                case 'facebook':
                    if (empty($account->access_token)) {
                        return ['success' => false, 'message' => 'Access Token Facebook belum diisi.'];
                    }
                    $res = Http::withoutVerifying()->timeout(10)->get("https://graph.facebook.com/me?access_token={$account->access_token}");
                    if ($res->successful()) {
                        $name = $res->json('name') ?? 'Akun Terverifikasi';
                        return ['success' => true, 'message' => "Terhubung ke Facebook: {$name}."];
                    }
                    return ['success' => false, 'message' => 'Token Facebook tidak valid: ' . ($res->json('error.message') ?? $res->body())];

                case 'instagram':
                    if (empty($account->access_token)) {
                        return ['success' => false, 'message' => 'Access Token Instagram belum diisi.'];
                    }
                    $endpoint = !empty($account->account_id)
                        ? "https://graph.facebook.com/v19.0/{$account->account_id}?fields=username,name&access_token={$account->access_token}"
                        : "https://graph.facebook.com/me?access_token={$account->access_token}";
                    $res = Http::withoutVerifying()->timeout(10)->get($endpoint);
                    if ($res->successful()) {
                        $name = $res->json('username') ?? ($res->json('name') ?? 'Akun Instagram Terverifikasi');
                        return ['success' => true, 'message' => "Terhubung ke Instagram: @{$name}."];
                    }
                    return ['success' => false, 'message' => 'Token Instagram tidak valid: ' . ($res->json('error.message') ?? $res->body())];

                case 'twitter':
                case 'x':
                    if (empty($account->access_token)) {
                        return ['success' => false, 'message' => 'Bearer Token Twitter/X belum diisi.'];
                    }
                    $res = Http::withoutVerifying()->timeout(10)
                        ->withToken($account->access_token)
                        ->get('https://api.twitter.com/2/users/me');
                    if ($res->successful()) {
                        $username = $res->json('data.username') ?? 'Akun Terverifikasi';
                        return ['success' => true, 'message' => "Terhubung ke Twitter/X: @{$username}."];
                    }
                    return ['success' => false, 'message' => 'Token Twitter/X tidak valid: ' . ($res->json('detail') ?? $res->body())];

                default:
                    if (!empty($account->access_token) || !empty($account->webhook_url)) {
                        return ['success' => true, 'message' => "Kredensial {$account->platform_label} tersimpan dan siap digunakan."];
                    }
                    return ['success' => false, 'message' => "Belum ada kredensial / token yang diisi untuk {$account->platform_label}."];
            }
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Koneksi gagal: ' . $e->getMessage()];
        }
    }
}
