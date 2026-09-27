<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Official;
use App\Models\Setting;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $officials = Official::orderBy('urutan', 'asc')->get();
        $baganStrukturUrl = Setting::baganStrukturUrl();

        $videoUrl = Setting::get('video_profil_youtube', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $youtubeEmbedUrl = $this->getYoutubeEmbedUrl($videoUrl);

        return view('frontend.profile', compact('officials', 'baganStrukturUrl', 'youtubeEmbedUrl', 'videoUrl'));
    }

    private function getYoutubeEmbedUrl(?string $url): string
    {
        if (empty($url)) {
            return 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?rel=0';
        }

        if (str_contains($url, '/embed/')) {
            return $url;
        }

        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1] . '?rel=0&modestbranding=1';
        }

        return $url;
    }
}
