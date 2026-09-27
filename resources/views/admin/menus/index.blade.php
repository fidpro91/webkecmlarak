@extends('layouts.admin')

@section('title', 'Menu Dinamis')
@section('page_title', 'Manajemen Menu Navigasi & Halaman')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Menu Navigasi Portal</h2>
            <p class="text-xs text-slate-500">Atur struktur menu header publik, submenu dropdown, dan halaman statis dinamis</p>
        </div>
        <a href="{{ route('admin.menus.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm">
            <i class="fa-solid fa-plus"></i> Tambah Menu Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4 text-center">Urutan</th>
                        <th class="py-3.5 px-4">Nama Menu</th>
                        <th class="py-3.5 px-4">Tipe Menu</th>
                        <th class="py-3.5 px-4">Target / URL</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($menus as $menu)
                        <!-- Induk Menu -->
                        <tr class="bg-slate-50/50 hover:bg-emerald-50/30 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">{{ $menu->urutan }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
                                    <i class="fa-solid fa-bars text-emerald-600 text-xs"></i>
                                    {{ $menu->nama_menu }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                                    {{ $menu->tipe === 'page' ? 'bg-sky-100 text-sky-800' : ($menu->tipe === 'module' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ strtoupper($menu->tipe) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                                {{ $menu->tipe === 'page' ? route('page.show', $menu->slug) : $menu->url }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($menu->status)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Hapus menu ini beserta seluruh submenu dan halamannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Submenus -->
                        @foreach($menu->children as $child)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-2.5 px-4 text-center text-slate-400 pl-8">↳ {{ $child->urutan }}</td>
                                <td class="py-2.5 px-4 pl-10">
                                    <span class="font-semibold text-slate-700 flex items-center gap-2">
                                        <i class="fa-solid fa-turn-up rotate-90 text-slate-300 text-xs"></i>
                                        {{ $child->nama_menu }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold 
                                        {{ $child->tipe === 'page' ? 'bg-sky-100 text-sky-800' : ($child->tipe === 'module' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ strtoupper($child->tipe) }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 font-mono text-[11px] text-slate-400">
                                    {{ $child->tipe === 'page' ? route('page.show', $child->slug) : $child->url }}
                                </td>
                                <td class="py-2.5 px-4 text-center">
                                    @if($child->status)
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.menus.edit', $child->id) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.menus.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Hapus submenu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Hapus">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada menu yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
