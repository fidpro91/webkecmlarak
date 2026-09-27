<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\View\View;

class StatisticController extends Controller
{
    public function index(): View
    {
        $villages = Village::orderBy('jumlah_penduduk', 'desc')->get();
        $totalPopulation = $villages->sum('jumlah_penduduk');
        $totalVillages = $villages->count();
        $avgPopulation = $totalVillages > 0 ? round($totalPopulation / $totalVillages) : 0;
        $maxVillage = $villages->first();
        $minVillage = $villages->last();

        $chartLabels = $villages->pluck('nama')->toArray();
        $chartData = $villages->pluck('jumlah_penduduk')->toArray();

        return view('frontend.statistics', compact(
            'villages',
            'totalPopulation',
            'totalVillages',
            'avgPopulation',
            'maxVillage',
            'minVillage',
            'chartLabels',
            'chartData'
        ));
    }
}
