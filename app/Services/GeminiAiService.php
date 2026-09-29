<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    /**
     * Generate naskah artikel / siaran pers berita menggunakan Google Gemini AI.
     *
     * @param string $prompt Instruksi atau poin-poin berita dari admin
     * @param string|null $judul Judul berita yang telah diinput (opsional)
     * @param string|null $kategori Nama kategori berita (opsional)
     * @return array{success: bool, content?: string, message?: string}
     */
    public static function generateArticle(string $prompt, ?string $judul = null, ?string $kategori = null): array
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(120);
        }

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return [
                'success' => false,
                'message' => 'API Key Gemini belum disetel. Silakan tambahkan variabel GEMINI_API_KEY di file .env server Anda.',
            ];
        }

        $configuredModel = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-3.5-flash-lite');

        // Daftar model yang dicoba secara berurutan. Prioritaskan flash-lite karena respon super cepat (< 3 detik)
        $modelsToTry = array_unique([
            $configuredModel,
            'gemini-3.5-flash-lite',
            'gemini-3.5-flash',
            'gemini-3.8-flash',
        ]);

        // Bangun system context & user prompt yang komprehensif
        $systemInstructions = "Anda adalah asisten jurnalis AI profesional untuk Portal Informasi Resmi Pemerintah Kecamatan Mlarak, Kabupaten Ponorogo, Jawa Timur.\n"
            . "Tugas Anda adalah menulis naskah berita liputan yang berbobot, akurat, informatif, dan komunikatif dengan kaidah jurnalistik 5W+1H (What, Who, When, Where, Why, How).\n"
            . "Karakter penulisan:\n"
            . "- Bahasa Indonesia yang baku, lugas, santun, dan representatif bagi instansi pemerintah kecamatan.\n"
            . "- Menghadirkan konteks lokal Kecamatan Mlarak atau Kabupaten Ponorogo yang relevan.\n"
            . "- Berisi 3 sampai 6 paragraf lengkap: Paragraf pembuka (lead), penjabaran jalannya kegiatan/isu, kutipan pernyataan pimpinan atau narasumber relevan, serta paragraf penutup atau harapan ke depan.\n"
            . "Aturan Format Output:\n"
            . "- Wajib menghasilkan format HTML murni yang siap disisipkan ke editor naskah (gunakan tag <p>, <h3>, <strong>, <em>, <ul>, <li>).\n"
            . "- JANGAN menyertakan backticks pembungkus kode seperti ```html atau ``` di awal dan akhir.\n"
            . "- JANGAN mengulang menulis tag <html>, <head>, atau <body>, cukup konten isi beritanya saja.";

        $userPrompt = "";
        if (!empty($judul)) {
            $userPrompt .= "Judul Artikel: {$judul}\n";
        }
        if (!empty($kategori)) {
            $userPrompt .= "Kategori Berita: {$kategori}\n";
        }
        $userPrompt .= "Instruksi & Poin-Poin Berita dari Admin:\n{$prompt}\n\nSilakan tuliskan naskah berita lengkap:";

        $fullInstruction = $systemInstructions . "\n\n---\n" . $userPrompt;

        $lastErrorMessage = '';

        foreach ($modelsToTry as $currentModel) {
            $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$currentModel}:generateContent?key={$apiKey}";

            try {
                $httpClient = Http::timeout(25)
                    ->connectTimeout(10)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ]);

                if (app()->environment('local', 'testing') || config('app.debug')) {
                    $httpClient = $httpClient->withoutVerifying();
                }

                $response = $httpClient->post($endpoint, [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $fullInstruction],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topK' => 40,
                        'topP' => 0.95,
                        'maxOutputTokens' => 1500,
                    ],
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $generatedText = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

                    if (empty(trim($generatedText))) {
                        return [
                            'success' => false,
                            'message' => 'AI tidak mengembalikan naskah konten. Silakan coba sesuaikan instruksi Anda.',
                        ];
                    }

                    // Bersihkan pembungkus markdown ```html ... ``` jika ada
                    $cleanHtml = self::cleanMarkdownWrappers($generatedText);

                    return [
                        'success' => true,
                        'content' => $cleanHtml,
                    ];
                }

                // Ambil info error
                $errorData = $response->json('error');
                $status = $response->status();
                $errorMessage = $errorData['message'] ?? ('HTTP Error ' . $status);

                // Jika error adalah API key invalid atau quota habis permanen, tidak perlu lanjut coba model lain
                if (str_contains(strtolower($errorMessage), 'api_key_invalid') || str_contains(strtolower($errorMessage), 'key not valid')) {
                    return [
                        'success' => false,
                        'message' => 'API Key Gemini tidak valid. Mohon periksa kembali GEMINI_API_KEY di file .env.',
                    ];
                }

                Log::warning("Gemini AI attempt with model {$currentModel} failed (HTTP {$status}): {$errorMessage}. Trying fallback if available...");
                $lastErrorMessage = $errorMessage;

                // Jika error 404 (model tidak didukung/usang) atau 503 (server overloaded), loop akan mencoba model berikutnya
                if ($status === 404 || $status === 503) {
                    continue;
                }

                // Untuk error lainnya (misal 400 Bad Request parameter), log dan kembalikan error
                break;
            } catch (\Throwable $e) {
                Log::warning("GeminiAiService exception with model {$currentModel}: " . $e->getMessage());
                $lastErrorMessage = $e->getMessage();
                continue;
            }
        }

        if (str_contains(strtolower($lastErrorMessage), 'quota') || str_contains(strtolower($lastErrorMessage), 'resource_exhausted')) {
            $lastErrorMessage = 'Batas kuota harian API Gemini tercapai (Quota Exceeded). Silakan coba lagi beberapa saat lagi atau periksa kuota akun Google AI Studio Anda.';
        }

        return [
            'success' => false,
            'message' => 'Gagal dari Gemini AI: ' . ($lastErrorMessage ?: 'Layanan AI sedang tidak dapat diakses saat ini.'),
        ];
    }

    /**
     * Bersihkan teks hasil AI dari pembungkus markdown codeblock.
     */
    private static function cleanMarkdownWrappers(string $text): string
    {
        $text = trim($text);

        // Hapus ```html di awal dan ``` di akhir
        if (str_starts_with($text, '```html')) {
            $text = substr($text, 7);
        } elseif (str_starts_with($text, '```')) {
            $text = substr($text, 3);
        }

        if (str_ends_with($text, '```')) {
            $text = substr($text, 0, -3);
        }

        $text = trim($text);

        // Jika tidak ada tag HTML sama sekali, ubah baris baru ganda menjadi paragraf <p>
        if (!str_contains($text, '<p>') && !str_contains($text, '<br>') && !str_contains($text, '<h3>')) {
            $paragraphs = preg_split('/\n\s*\n/', $text);
            $formatted = '';
            foreach ($paragraphs as $para) {
                $para = trim($para);
                if (!empty($para)) {
                    $formatted .= '<p>' . nl2br(htmlspecialchars($para, ENT_QUOTES, 'UTF-8')) . '</p>';
                }
            }
            return $formatted;
        }

        return $text;
    }
}
