<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::with(['children', 'page', 'parent'])
            ->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create(): View
    {
        $parentMenus = Menu::whereNull('parent_id')->orderBy('urutan')->get();
        return view('admin.menus.create', compact('parentMenus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:page,module,external_link'],
            'url' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'urutan' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'icon' => ['nullable', 'string', 'max:50'],
            'konten' => ['nullable', 'string'],
            'gambar_page' => ['nullable', 'image', 'max:3072'],
        ]);

        $validated['slug'] = Str::slug($validated['nama_menu']);

        $menu = Menu::create([
            'nama_menu' => $validated['nama_menu'],
            'slug' => $validated['slug'],
            'tipe' => $validated['tipe'],
            'url' => $validated['url'],
            'parent_id' => $validated['parent_id'],
            'urutan' => $validated['urutan'],
            'status' => $validated['status'],
            'icon' => $validated['icon'] ?? null,
        ]);

        if ($validated['tipe'] === 'page') {
            $gambarPath = null;
            if ($request->hasFile('gambar_page')) {
                $gambarPath = $request->file('gambar_page')->store('pages', 'public');
            }

            MenuPage::create([
                'menu_id' => $menu->id,
                'konten' => $request->input('konten'),
                'gambar' => $gambarPath,
            ]);
        }

        Cache::forget('nav_menus');

        return redirect()->route('admin.menus.index')->with('success', 'Menu navigasi berhasil dibuat.');
    }

    public function edit(Menu $menu): View
    {
        $menu->load('page');
        $parentMenus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('urutan')->get();

        return view('admin.menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:100'],
            'tipe' => ['required', 'in:page,module,external_link'],
            'url' => ['nullable', 'string', 'max:255'],
            'parent_id' => ['nullable', 'exists:menus,id'],
            'urutan' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'boolean'],
            'icon' => ['nullable', 'string', 'max:50'],
            'konten' => ['nullable', 'string'],
            'gambar_page' => ['nullable', 'image', 'max:3072'],
        ]);

        $validated['slug'] = Str::slug($validated['nama_menu']);

        $menu->update([
            'nama_menu' => $validated['nama_menu'],
            'slug' => $validated['slug'],
            'tipe' => $validated['tipe'],
            'url' => $validated['url'],
            'parent_id' => $validated['parent_id'],
            'urutan' => $validated['urutan'],
            'status' => $validated['status'],
            'icon' => $validated['icon'] ?? null,
        ]);

        if ($validated['tipe'] === 'page') {
            $menuPage = $menu->page ?? new MenuPage(['menu_id' => $menu->id]);

            if ($request->hasFile('gambar_page')) {
                if ($menuPage->gambar && !str_starts_with($menuPage->gambar, 'http') && Storage::disk('public')->exists($menuPage->gambar)) {
                    Storage::disk('public')->delete($menuPage->gambar);
                }
                $menuPage->gambar = $request->file('gambar_page')->store('pages', 'public');
            }

            $menuPage->konten = $request->input('konten');
            $menuPage->save();
        }

        Cache::forget('nav_menus');

        return redirect()->route('admin.menus.index')->with('success', 'Menu navigasi berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->page && $menu->page->gambar && !str_starts_with($menu->page->gambar, 'http')) {
            Storage::disk('public')->delete($menu->page->gambar);
        }

        $menu->delete();
        Cache::forget('nav_menus');

        return redirect()->route('admin.menus.index')->with('success', 'Menu navigasi berhasil dihapus.');
    }
}
