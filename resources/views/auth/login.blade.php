<x-guest-layout>
    <div class="space-y-8">
        <div>
            <h3 class="text-2xl font-black text-slate-900 font-outfit tracking-tight">Security Check</h3>
            <p class="text-slate-500 font-medium text-sm mt-1">Authenticate to access the management suite.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    Corporate Email
                </label>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00ADC5] text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                            </path>
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        autocomplete="username" placeholder="name@company.com"
                        class="block w-full pl-12 pr-4 py-4 bg-white/50 border-2 border-slate-100 rounded-2xl text-slate-700 font-semibold placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs font-bold text-rose-500 ml-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between ml-1">
                    <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Access Key
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-[10px] font-black text-[#00ADC5] uppercase tracking-widest hover:text-[#007A8A] transition-colors">
                            Lost Key?
                        </a>
                    @endif
                </div>
                <div class="relative group">
                    <div
                        class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-[#00ADC5] text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="block w-full pl-12 pr-4 py-4 bg-white/50 border-2 border-slate-100 rounded-2xl text-slate-700 font-semibold placeholder-slate-300 focus:outline-none focus:ring-4 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs font-bold text-rose-500 ml-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center ml-1">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-5 h-5 text-[#00ADC5] border-2 border-slate-200 rounded-lg focus:ring-[#00ADC5] focus:ring-offset-0 bg-white/50 transition-all cursor-pointer">
                <label for="remember_me"
                    class="ml-3 text-xs font-bold text-slate-500 cursor-pointer uppercase tracking-tight">Stay
                    authenticated on this device</label>
            </div>

            <!-- Action -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-slate-900 border-2 border-slate-900 py-4 rounded-2xl text-sm font-black text-white uppercase tracking-[0.2em] shadow-2xl shadow-slate-900/20 hover:bg-white hover:text-slate-900 transition-all active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                    Initialize Session
                </button>
            </div>
        </form>

        <!-- Dynamic Hint -->
        <div class="pt-6 border-t border-slate-100 flex items-center gap-4">
            <div class="p-2 bg-cyan-50 rounded-xl">
                <svg class="w-4 h-4 text-[#00ADC5]" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-[10px] font-bold text-slate-400 leading-relaxed uppercase tracking-tight">
                Use your official domain credentials to access your specific department dashboard.
            </p>
        </div>
    </div>
</x-guest-layout>