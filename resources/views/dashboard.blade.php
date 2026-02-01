<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- New Modern Welcome Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div
                class="px-8 py-10 flex flex-col md:flex-row md:items-center justify-between gap-8 bg-gradient-to-br from-white to-slate-50/50">
                <div class="space-y-2">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">
                        Welcome back, <span class="text-blue-600">{{ Auth::user()->name }}</span>
                    </h2>
                    <p class="text-slate-500 font-medium flex items-center gap-2">
                        <span class="inline-flex w-2 h-2 rounded-full bg-blue-500"></span>
                        @if(Auth::user()->isManager())
                            Department Manager • Reviewing team presence
                        @elseif(Auth::user()->isUser() || Auth::user()->isDepartmentAttendanceUser())
                            Department Representative • Managing
                            {{ Auth::user()->department->name ?? 'Division' }}
                        @else
                            System User • Accessing attendance modules
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div
                        class="bg-white border border-slate-200 rounded-2xl px-5 py-3 shadow-sm flex flex-col items-end">
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Local
                            Time</span>
                        <span class="text-lg font-bold text-slate-700">{{ now()->format('h:i A') }}</span>
                    </div>
                    <div class="bg-blue-600 rounded-2xl px-6 py-4 shadow-lg shadow-blue-200 text-white">
                        <span
                            class="text-[10px] font-black opacity-60 uppercase tracking-widest block mb-0.5">Today</span>
                        <span class="text-lg font-black leading-none">{{ now()->format('D, M d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div
                class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-blue-200 transition-all">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Network</span>
                <div class="flex items-end justify-between">
                    <span
                        class="text-3xl font-black text-slate-900 leading-none">{{ $stats['total_departments'] }}</span>
                    <span class="text-xs font-bold text-slate-400 uppercase">Divisions</span>
                </div>
            </div>

            <div
                class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-blue-200 transition-all">
                <span
                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Workforce</span>
                <div class="flex items-end justify-between">
                    <span class="text-3xl font-black text-slate-900 leading-none">{{ $stats['total_employees'] }}</span>
                    <span class="text-xs font-bold text-slate-400 uppercase">Employees</span>
                </div>
            </div>

            @if(Auth::user()->isManager())
                <div class="bg-slate-900 rounded-3xl p-6 shadow-xl ring-1 ring-white/10 relative overflow-hidden group">
                    <span
                        class="text-[10px] font-black text-white/40 uppercase tracking-widest block mb-2 relative z-10">Approval
                        Queue</span>
                    <div class="flex items-end justify-between relative z-10">
                        <span class="text-3xl font-black text-white leading-none">{{ $stats['pending_approvals'] }}</span>
                        <a href="{{ route('manager.approvals.index') }}"
                            class="text-[10px] font-black text-blue-400 uppercase hover:text-blue-300 transition-colors">Review
                            Queue →</a>
                    </div>
                    <div
                        class="absolute -right-4 -bottom-4 w-16 h-16 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all">
                    </div>
                </div>
            @endif

            <div
                class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm group hover:border-blue-200 transition-all">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">My
                    Department</span>
                <div class="flex items-end justify-between">
                    @if($stats['my_department_status'])
                        <span
                            class="px-2 py-1 rounded-lg text-[9px] font-black uppercase ring-1 ring-inset {{ $stats['my_department_status']->status->color() }}">
                            {{ $stats['my_department_status']->status->label() }}
                        </span>
                    @else
                        <span class="text-xs font-bold text-slate-400 italic">Not Filed</span>
                    @endif
                    <a href="{{ route('attendance.index') }}"
                        class="text-[10px] font-black text-blue-600 uppercase hover:text-blue-700">Open Grid →</a>
                </div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Submission History -->
            <div class="lg:col-span-8 bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Attendance Submission
                        History</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recently
                            Filed</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Week Period</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                    Status</th>
                                <th
                                    class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                    Synchronization</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($attendanceHistory as $record)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black border-2 border-white shadow-sm ring-1 ring-slate-100">
                                                <i data-lucide="calendar" class="w-5 h-5 text-[#00ADC5]"></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-800 leading-tight">Week of
                                                    {{ $record->week_start_date->format('M d, Y') }}</span>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Cycle
                                                    #{{ $record->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $record->status->color() }}">
                                            {{ $record->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        @if(Auth::user()->isAdmin())
                                            <a href="{{ route('attendance.index', ['week' => $record->week_start_date->toDateString()]) }}"
                                                class="inline-flex items-center px-4 py-2 bg-slate-50 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-[#00ADC5] hover:text-white transition-all">
                                                Audit Record →
                                            </a>
                                        @else
                                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Archived Record</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <i data-lucide="layers-2" class="w-10 h-10 mb-4"></i>
                                            <p class="text-[10px] font-black uppercase tracking-widest">No previous
                                                submissions archived.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Side Cards -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Summary Card -->
                <div
                    class="bg-slate-900 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl ring-1 ring-white/10">
                    <div class="relative z-10">
                        <div
                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 p-0.5 mb-6 shadow-xl shadow-blue-500/20">
                            <div class="w-full h-full rounded-[14px] bg-slate-900 flex items-center justify-center">
                                <span
                                    class="text-2xl font-black text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h4 class="text-xl font-black tracking-tight mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-xs font-bold text-white/40 uppercase tracking-widest mb-8">
                            {{ Auth::user()->email }}
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                                <span class="text-[9px] font-black text-white/40 uppercase block mb-1">My Role</span>
                                <span class="text-sm font-bold text-white">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/10">
                                <span class="text-[9px] font-black text-white/40 uppercase block mb-1">Member
                                    Since</span>
                                <span
                                    class="text-sm font-bold text-white">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Decor -->
                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl"></div>
                </div>

                <!-- System Stats -->
                <div class="bg-white rounded-3xl border border-slate-200 p-8 space-y-6">
                    <h3
                        class="text-xs font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-4">
                        Infrastructure Status</h3>
                    <div class="space-y-4">
                        @foreach(['Central Database', 'Auth Server', 'Vite Assets'] as $item)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-600">{{ $item }}</span>
                                <div class="flex items-center gap-1.5">
                                    <div
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]">
                                    </div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase">Operational</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>