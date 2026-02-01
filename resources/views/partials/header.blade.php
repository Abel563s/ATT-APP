<header
    class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-30 shadow-sm shadow-slate-100/50">
    <div class="flex items-center gap-6">
        <button id="sidebarToggle"
            class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-slate-100 hover:text-[#00ADC5] transition-all active:scale-95 group relative">
            <i data-lucide="chevron-right" id="toggleIconOpen" class="w-5 h-5 transition-all duration-300"></i>
            <i data-lucide="chevron-left" id="toggleIconClose"
                class="w-5 h-5 transition-all duration-300 absolute opacity-0 scale-50 pointer-events-none"></i>
        </button>

        <div class="flex flex-col">
            <h1 class="text-lg font-black text-slate-900 tracking-tight leading-none">
                @if(isset($header))
                    {{ $header }}
                @else
                    @yield('page-title', 'System Dashboard')
                @endif
            </h1>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                @php
                    $roleLabel = match (Auth::user()->role) {
                        'admin' => 'Authority Panel',
                        'manager' => 'Management Node',
                        default => 'Division Terminal'
                    };
                @endphp
                WorkForce Attendance • {{ $roleLabel }}
            </p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="hidden md:flex flex-col text-right">
            <p class="text-xs font-bold text-slate-700 leading-none">{{ Auth::user()->name }}</p>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mt-1">{{ Auth::user()->email }}
            </p>
        </div>

        <!-- Notifications Bell -->
        <a href="{{ route('notifications.index') }}"
            class="relative w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-400 flex items-center justify-center hover:bg-cyan-50 hover:text-[#00ADC5] hover:border-[#00ADC5]/30 transition-all active:scale-95 group">
            <i data-lucide="bell" class="w-5 h-5 transition-transform group-hover:rotate-12"></i>
            @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 border-2 border-white rounded-full"></span>
            @endif
        </a>

        <div class="relative group" x-data="{ open: false }">
            <button @click="open = !open"
                class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center font-black hover:border-[#00ADC5] hover:bg-cyan-50 transition-all overflow-hidden group-hover:shadow-lg group-hover:shadow-cyan-100">
                {{ substr(Auth::user()->name, 0, 1) }}
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 py-1"
                x-cloak>
                <div class="px-4 py-3 border-b border-slate-50 bg-slate-50/50">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Account</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-cyan-50 hover:text-[#00ADC5] transition-colors">
                    <i data-lucide="user-cog" class="w-4 h-4"></i>
                    Profile Settings
                </a>
                <div class="border-t border-slate-50"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full h-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>