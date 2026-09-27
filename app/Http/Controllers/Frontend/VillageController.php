<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VillageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Village::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kepala_desa', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $villages = $query->orderBy('nama', 'asc')->paginate(12)->withQueryString();
        $totalPopulation = Village::sum('jumlah_penduduk');
        $totalVillages = Village::count();

        return view('frontend.villages.index', compact('villages', 'totalPopulation', 'totalVillages'));
    }

    public function show(string $slug): View
    {
        $village = Village::where('slug', $slug)->firstOrFail();
        $otherVillages = Village::where('id', '!=', $village->id)->inRandomOrder()->take(4)->get();

        return view('frontend.villages.show', compact('village', 'otherVillages'));
    }
}
