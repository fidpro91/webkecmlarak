<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Download;
use App\Models\Gallery;
use App\Models\Official;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Village;
use App\Models\Visitor;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sliders = Slider::aktif()->get();
        $latestArticles = Article::published()->with(['category', 'author'])->take(3)->get();
        $villagesCount = Village::count();
        $totalPopulation = Village::sum('jumlah_penduduk');
        $servicesCount = Service::count();
        $downloadsCount = Download::where('status', true)->count();
        $visitorsCount = Visitor::totalVisitorsCount();
        $services = Service::take(4)->get();
        $officials = Official::orderBy('urutan')->take(4)->get();
        $camat = Official::getCamat();
        $galleries = Gallery::latest()->take(6)->get();

        return view('frontend.home', compact(
            'sliders',
            'latestArticles',
            'villagesCount',
            'totalPopulation',
            'servicesCount',
            'downloadsCount',
            'visitorsCount',
            'services',
            'officials',
            'camat',
            'galleries'
        ));
    }
}
