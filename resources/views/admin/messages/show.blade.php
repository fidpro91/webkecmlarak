@extends('layouts.admin')

@section('title', 'Detail Pesan')
@section('page_title', 'Rincian Pesan Pengaduan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Rincian Pengaduan Warga</h2>
            <p class="text-xs text-slate-500">Diterima pada {{ $message->created_at->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</p>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Kembali ke Kotak Masuk
        </a>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pengirim:</span>
                <h3 class="text-lg font-bold text-slate-900">{{ $message->nama }}</h3>
                <p class="text-xs text-emerald-700 font-semibold flex items-center gap-1.5">
                    <i class="fa-regular fa-envelope"></i> {{ $message->email }}
                </p>
            </div>
            <div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $message->status === 'unread' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                    Status: {{ $message->status === 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                </span>
            </div>
        </div>

        <!-- Subject & Content -->
        <div class="space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Perihal / Subjek:</span>
            <h4 class="text-base font-extrabold text-slate-900 bg-slate-50 p-4 rounded-xl border border-slate-200">
                {{ $message->subjek }}
            </h4>
        </div>

        <div class="space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Uraian Pesan / Pengaduan:</span>
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                {{ $message->pesan }}
            </div>
        </div>

        <!-- Actions -->
        <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <form action="{{ route('admin.messages.toggle-status', $message->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                        Tandai {{ $message->status === 'unread' ? 'Sudah Dibaca' : 'Belum Dibaca' }}
                    </button>
                </form>

                <a href="mailto:{{ $message->email }}?subject=Tanggapan%20Pengaduan%20Kecamatan%20Mlarak:%20{{ urlencode($message->subjek) }}" 
                   class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold flex items-center gap-1.5 transition">
                    <i class="fa-solid fa-reply"></i> Balas ke Email Warga
                </a>
            </div>

            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Pesan
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
