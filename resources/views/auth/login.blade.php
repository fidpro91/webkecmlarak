<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Login Administrator</h2>
        <p class="text-xs text-slate-500 mt-1">Masukkan kredensial akun Anda untuk mengakses panel admin</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Email</label>
            <div class="relative">
                <input id="email" class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-rose-500 @enderror" 
                       type="email" name="email" value="{{ old('email', 'admin@mlarak.ponorogo.go.id') }}" required autofocus autocomplete="username" />
                <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            </div>
            @error('email')
                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kata Sandi</label>
            <div class="relative">
                <input id="password" class="w-full pl-10 pr-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 @error('password') border-rose-500 @enderror"
                       type="password" name="password" value="password" required autocomplete="current-password" />
                <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
            </div>
            @error('password')
                <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember" checked>
                <span class="ms-2 text-slate-600 font-medium">Ingat Saya</span>
            </label>
            <span class="text-[11px] text-slate-400">Default: password</span>
        </div>

        <div class="pt-3">
            <button type="submit" 
                    class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition transform active:scale-95 flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Panel Admin
            </button>
        </div>
    </form>
</x-guest-layout>
