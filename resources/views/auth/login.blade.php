<x-guest-layout>
    <div class="space-y-6">
        <div>
            <h3 class="text-xl font-black text-slate-900 font-outfit tracking-tight">Security Check</h3>
            <p class="text-slate-500 text-xs font-medium mt-1">Authenticate to access the management suite.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    Corporate Email
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00ADC5] text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@company.com" class="block w-full pl-10 pr-3 py-2.5 bg-white/50 border-2 border-slate-100 rounded-xl text-slate-700 font-semibold text-sm placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs font-bold text-rose-500 ml-1" />
            </div>

            <div class="space-y-1.5">
                <div class="flex items-center justify-between ml-1">
                    <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Access Key
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-[10px] font-bold text-[#00ADC5] hover:text-[#007A8A] transition-colors" href="{{ route('password.request') }}">
                            Forgot key?
                        </a>
                    @endif
                </div>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-[#00ADC5] transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" class="block w-full pl-10 pr-3 py-2.5 bg-white/50 border-2 border-slate-100 rounded-xl text-slate-700 font-semibold text-sm placeholder-slate-300 focus:outline-none focus:ring-2 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs font-bold text-rose-500 ml-1" />
            </div>

            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#00ADC5] focus:ring-[#00ADC5]">
                    <span class="text-xs font-bold text-slate-600">Remember terminal</span>
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95">
                Authenticate
            </button>
        </form>
    </div>
</x-guest-layout>
