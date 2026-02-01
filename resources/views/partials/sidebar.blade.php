<aside id="sidebar"
    class="w-64 transition-all duration-300 bg-gradient-to-b from-[#00ADC5] via-[#00ADC5] to-[#007A8A] text-white flex flex-col z-40 relative shadow-2xl">

    <!-- Decorative White Linear Overlay -->
    <div class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent pointer-events-none"></div>

    <div class="h-20 flex items-center px-6 border-b border-white/10 shrink-0 overflow-hidden relative z-10">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center shrink-0 border border-white/20">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-7 h-7 object-contain brightness-0 invert">
            </div>
            <div class="flex flex-col leading-none sidebar-text">
                <span class="text-[10px] font-black uppercase tracking-[0.1em] text-white/50">WorkForce</span>
                <span class="text-sm font-black tracking-tight mt-0.5">Attendance</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 py-8 space-y-1.5 overflow-y-auto custom-scrollbar relative z-10">
        @php
            $menu = [
                ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
            ];

            // Employees - Admin only
            if (Auth::check() && Auth::user()->isAdmin()) {
                $menu[] = ['label' => 'Employees', 'icon' => 'users', 'route' => 'admin.employees.index', 'active' => request()->routeIs('admin.employees.*')];
            }

            // Attendance - Non-admin, non-manager users
            if (Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isManager()) {
                $menu[] = ['label' => 'Attendance', 'icon' => 'calendar-days', 'route' => 'attendance.index', 'active' => request()->routeIs('attendance.*')];
            }

            // Approvals - Managers and Admins
            if (Auth::check() && (Auth::user()->isManager() || Auth::user()->isAdmin())) {
                $menu[] = ['label' => 'Approvals', 'icon' => 'check-circle', 'route' => 'manager.approvals.index', 'active' => request()->routeIs('manager.approvals.*')];
            }

            // History - Accessible by All
            if (Auth::check()) {
                $menu[] = ['label' => 'History', 'icon' => 'clock', 'route' => 'attendance.history', 'active' => request()->routeIs('attendance.history')];
            }

            // Reports - Admin only
            if (Auth::check() && Auth::user()->isAdmin()) {
                $menu[] = ['label' => 'Reports', 'icon' => 'bar-chart-3', 'route' => 'admin.reports', 'active' => request()->routeIs('admin.reports')];
            }

            // Core Identifiers - Admin only
            if (Auth::check() && Auth::user()->isAdmin()) {
                $menu[] = ['label' => 'Core Identifiers', 'icon' => 'server', 'route' => 'admin.settings.index', 'active' => request()->routeIs('admin.settings.*')];
            }

            // User Roles - Admin only
            if (Auth::check() && Auth::user()->isAdmin()) {
                $menu[] = ['label' => 'User Roles', 'icon' => 'shield-check', 'route' => 'admin.users.index', 'active' => request()->routeIs('admin.users.*')];
            }

            // Settings - All users
            $menu[] = ['label' => 'Settings', 'icon' => 'settings', 'route' => 'profile.edit', 'active' => request()->routeIs('profile.*')];
        @endphp

        @foreach ($menu as $item)
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 p-3 rounded-xl transition-all duration-300 group relative {{ $item['active'] ? 'bg-white text-[#00ADC5] shadow-xl shadow-black/10' : 'hover:bg-white/10 text-white/80 hover:text-white' }}">
                @if($item['active'])
                    <div class="absolute inset-y-2 left-0 w-1 bg-[#00ADC5] rounded-full"></div>
                @endif
                <i data-lucide="{{ $item['icon'] }}"
                    class="w-5 h-5 shrink-0 transition-transform group-hover:scale-110"></i>
                <span class="sidebar-text font-bold text-sm whitespace-nowrap">{{ $item['label'] }}</span>
                @if(isset($item['unread']) && $item['unread'] > 0)
                    <span
                        class="ml-auto bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-lg shadow-lg shadow-rose-500/20 sidebar-text">
                        {{ $item['unread'] }}
                    </span>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="p-6 border-t border-white/10 bg-black/5 shrink-0 relative z-10">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white flex items-center justify-center font-black shadow-inner shrink-0 group-hover:rotate-6 transition-transform">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="sidebar-text overflow-hidden">
                <p class="text-sm font-black truncate leading-tight">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">{{ Auth::user()->role }}
                        Account</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<style>
    #sidebar.w-20 .sidebar-text {
        display: none;
    }

    #sidebar.w-20 .h-20 {
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }

    #sidebar.w-20 nav {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    #sidebar.w-20 .p-3 {
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }

    #sidebar.w-20 .p-6 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        justify-content: center;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
</style>