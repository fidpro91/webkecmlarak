<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Service::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', "%{$search}%")
                  ->orWhere('syarat', 'like', "%{$search}%")
                  ->orWhere('prosedur', 'like', "%{$search}%");
            });
        }

        $services = $query->orderBy('nama_layanan', 'asc')->get();

        return view('frontend.services.index', compact('services'));
    }
}
