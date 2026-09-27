<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Gallery::latest();

        if ($request->filled('tipe') && in_array($request->tipe, ['foto', 'video'])) {
            $query->where('tipe', $request->tipe);
        }

        $galleries = $query->paginate(12)->withQueryString();

        return view('frontend.gallery.index', compact('galleries'));
    }
}
