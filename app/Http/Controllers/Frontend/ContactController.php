<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('frontend.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subjek' => ['required', 'string', 'max:255'],
            'pesan' => ['required', 'string', 'min:10', 'max:5000'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'subjek.required' => 'Subjek atau perihal pesan wajib diisi.',
            'pesan.required' => 'Isi pesan atau pengaduan wajib diisi.',
            'pesan.min' => 'Isi pesan minimal 10 karakter.',
        ]);

        Message::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'subjek' => $validated['subjek'],
            'pesan' => $validated['pesan'],
            'status' => 'unread',
        ]);

        return back()->with('success', 'Terima kasih! Pesan atau pengaduan Anda telah berhasil dikirim ke Pemerintah Kecamatan Mlarak. Petugas kami akan segera menindaklanjuti.');
    }
}
