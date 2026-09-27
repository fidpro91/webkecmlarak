<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MenuPageController extends Controller
{
    public function show(string $slug): View|RedirectResponse
    {
        $menu = Menu::where('slug', $slug)
            ->where('status', true)
            ->with('page')
            ->firstOrFail();

        if ($menu->tipe !== 'page' || !$menu->page) {
            if (!empty($menu->url)) {
                return redirect($menu->url);
            }
            abort(404, 'Halaman tidak ditemukan.');
        }

        return view('frontend.page', compact('menu'));
    }
}
