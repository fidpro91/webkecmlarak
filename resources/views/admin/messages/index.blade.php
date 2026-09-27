@extends('layouts.admin')

@section('title', 'Pesan Pengaduan')
@section('page_title', 'Kotak Masuk Aspirasi & Pengaduan Warga')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kotak Masuk Pengaduan & Aspirasi</h2>
            <p class="text-xs text-slate-500">Pesan dari masyarakat yang dikirim melalui formulir kontak portal</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                <i class="fa-solid fa-envelope mr-1"></i> {{ $unreadCount }} Pesan Belum Dibaca
            </span>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <form action="{{ route('admin.messages.index') }}" method="GET" class="w-full sm:w-80 flex items-center relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, subjek..." 
                   class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
        </form>

        <div class="flex items-center gap-2 text-xs">
            <a href="{{ route('admin.messages.index') }}" 
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>
            <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" 
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ request('status') === 'unread' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Belum Dibaca
            </a>
            <a href="{{ route('admin.messages.index', ['status' => 'read']) }}" 
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ request('status') === 'read' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Sudah Dibaca
            </a>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold tracking-wider text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Pengirim</th>
                        <th class="py-3.5 px-4">Subjek / Perihal</th>
                        <th class="py-3.5 px-4">Ringkasan Pesan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4">Waktu Diterima</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-50/80 transition {{ $msg->status === 'unread' ? 'bg-rose-50/30' : '' }}">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-sm">{{ $msg->nama }}</p>
                                <p class="text-[11px] text-slate-400">{{ $msg->email }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800 max-w-xs">
                                {{ $msg->subjek }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs">
                                <p class="line-clamp-2">{{ $msg->pesan }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($msg->status === 'unread')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">Belum Dibaca</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">Sudah Dibaca</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $msg->created_at->isoFormat('D MMM Y, HH:mm') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition" title="Buka Detail">
                                        <i class="fa-solid fa-envelope-open text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition" title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada pesan yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
