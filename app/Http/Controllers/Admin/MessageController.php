<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Message::latest();

        if ($request->filled('status') && in_array($request->status, ['read', 'unread'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->q}%")
                  ->orWhere('email', 'like', "%{$request->q}%")
                  ->orWhere('subjek', 'like', "%{$request->q}%")
                  ->orWhere('pesan', 'like', "%{$request->q}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = Message::unread()->count();

        return view('admin.messages.index', compact('messages', 'unreadCount'));
    }

    public function show(Message $message): View
    {
        if ($message->status === 'unread') {
            $message->update(['status' => 'read']);
        }

        return view('admin.messages.show', compact('message'));
    }

    public function updateStatus(Request $request, Message $message): RedirectResponse
    {
        $newStatus = $message->status === 'unread' ? 'read' : 'unread';
        $message->update(['status' => $newStatus]);

        return back()->with('success', 'Status pesan diperbarui menjadi ' . $newStatus);
    }

    public function destroy(Message $message): RedirectResponse
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Pesan pengaduan berhasil dihapus.');
    }
}
