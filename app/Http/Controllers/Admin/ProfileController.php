<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMediaAccount;
use App\Services\ImageService;
use App\Services\SocialMediaPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman pengaturan akun & sosial media.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $socialAccounts = SocialMediaAccount::orderBy('created_at', 'asc')->get();

        return view('admin.profile.edit', compact('user', 'socialAccounts'));
    }

    /**
     * Perbarui data profil akun (Nama, Email, Foto).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = ImageService::compressAndStore($request->file('photo'), 'users', 500, 500);
        }

        $user->save();

        return back()->with('success', 'Profil akun Anda berhasil diperbarui.');
    }

    /**
     * Perbarui kata sandi akun.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok dengan data kami.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui.');
    }

    /**
     * Tambah akun autentikasi sosial media baru.
     */
    public function storeSocialAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => ['required', 'string', 'in:facebook,instagram,twitter,telegram,whatsapp,other'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_id' => ['nullable', 'string', 'max:255'],
            'app_id' => ['nullable', 'string', 'max:255'],
            'app_secret' => ['nullable', 'string'],
            'access_token' => ['nullable', 'string'],
            'token_secret' => ['nullable', 'string'],
            'webhook_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'auto_post' => ['nullable', 'boolean'],
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['auto_post'] = $request->boolean('auto_post', true);
        $validated['last_status'] = 'Akun ditambahkan';

        SocialMediaAccount::create($validated);

        return back()->with('success', 'Akun sosial media berhasil ditambahkan ke sistem.');
    }

    /**
     * Perbarui kredensial akun sosial media.
     */
    public function updateSocialAccount(Request $request, SocialMediaAccount $socialAccount): RedirectResponse
    {
        $validated = $request->validate([
            'platform' => ['required', 'string', 'in:facebook,instagram,twitter,telegram,whatsapp,other'],
            'account_name' => ['required', 'string', 'max:255'],
            'account_id' => ['nullable', 'string', 'max:255'],
            'app_id' => ['nullable', 'string', 'max:255'],
            'app_secret' => ['nullable', 'string'],
            'access_token' => ['nullable', 'string'],
            'token_secret' => ['nullable', 'string'],
            'webhook_url' => ['nullable', 'url', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'auto_post' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['auto_post'] = $request->boolean('auto_post');

        // Jika access_token kosong saat edit, pertahankan token lama jika tidak ingin mengganti
        if (empty($validated['access_token']) && $socialAccount->access_token) {
            unset($validated['access_token']);
        }
        if (empty($validated['app_secret']) && $socialAccount->app_secret) {
            unset($validated['app_secret']);
        }

        $socialAccount->update($validated);

        return back()->with('success', "Pengaturan akun {$socialAccount->account_name} berhasil diperbarui.");
    }

    /**
     * Hapus konfigurasi akun sosial media.
     */
    public function destroySocialAccount(SocialMediaAccount $socialAccount): RedirectResponse
    {
        $name = $socialAccount->account_name;
        $socialAccount->delete();

        return back()->with('success', "Akun sosial media '{$name}' berhasil dihapus.");
    }

    /**
     * Toggle status aktif atau auto-post akun sosial media.
     */
    public function toggleSocialAccount(Request $request, SocialMediaAccount $socialAccount): JsonResponse
    {
        $field = $request->input('field', 'is_active');

        if ($field === 'auto_post') {
            $socialAccount->auto_post = !$socialAccount->auto_post;
            $status = $socialAccount->auto_post;
            $msg = $status ? 'Otomatis post diaktifkan.' : 'Otomatis post dinonaktifkan.';
        } else {
            $socialAccount->is_active = !$socialAccount->is_active;
            $status = $socialAccount->is_active;
            $msg = $status ? 'Akun sosial media diaktifkan.' : 'Akun sosial media dinonaktifkan.';
        }

        $socialAccount->save();

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => $msg,
        ]);
    }

    /**
     * Uji koneksi token sosial media.
     */
    public function testSocialAccount(SocialMediaAccount $socialAccount): JsonResponse
    {
        $result = SocialMediaPostService::testConnection($socialAccount);

        if ($result['success']) {
            $socialAccount->update(['last_status' => 'Koneksi terverifikasi']);
        }

        return response()->json($result);
    }
}
