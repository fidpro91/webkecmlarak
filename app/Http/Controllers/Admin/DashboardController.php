<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Download;
use App\Models\Message;
use App\Models\Service;
use App\Models\User;
use App\Models\Village;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'villages' => Village::count(),
            'population' => Village::sum('jumlah_penduduk'),
            'articles' => Article::count(),
            'services' => Service::count(),
            'downloads' => Download::count(),
            'total_downloads_counter' => Download::sum('jumlah_unduhan'),
            'messages_unread' => Message::unread()->count(),
            'users' => User::count(),
        ];

        $latestMessages = Message::latest()->take(5)->get();
        $latestArticles = Article::latest()->take(5)->get();
        $topDownloads = Download::orderBy('jumlah_unduhan', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestMessages', 'latestArticles', 'topDownloads'));
    }
}
