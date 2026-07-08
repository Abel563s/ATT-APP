<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-br from-white to-slate-50/50">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Welcome back, <span class="text-blue-600">{{ Auth::user()->name }}</span>
                    </h2>
                    <p class="text-slate-500 text-sm font-medium flex items-center gap-2">
                        <span class="inline-flex w-2 h-2 rounded-full bg-blue-500"></span>
                        @if(Auth::user()->isManager())
                            Department Manager • Reviewing team presence
                        @elseif(Auth::user()->isUser() || Auth::user()->isDepartmentAttendanceUser())
                            Department Representative • Managing {{ Auth::user()->department->name ?? 'Division' }}
                        @else
                            System User • Accessing attendance modules
                        @endif
                    </p>
                </div>
                <div class="flex items-center">
                    <div class="bg-blue-600 rounded-xl px-4 py-2.5 shadow-lg shadow-blue-200 text-white">
                        <span class="text-[9px] font-black opacity-60 uppercase tracking-widest block mb-0.5">Today</span>
                        <span class="text-sm font-black leading-none">{{ now()->format('D, M d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-blue-200 transition-all">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Network</span>
                <div class="flex items-end justify-between">
                    <span class="text-xl font-black text-slate-900 leading-none">{{ $stats['total_departments'] }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Divisions</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-blue-200 transition-all">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Workforce</span>
                <div class="flex items-end justify-between">
                    <span class="text-xl font-black text-slate-900 leading-none">{{ $stats['total_employees'] }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Employees</span>
                </div>
            </div>
            @if(Auth::user()->isManager())
                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden hover:border-[#00ADC5] transition-all">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1 relative z-10">Approvals</span>
                    <div class="flex items-end justify-between">
                        <span class="text-xl font-black text-slate-900 leading-none">{{ $stats['pending_approvals'] }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Pending</span>
                    </div>
                    <div class="absolute -right-3 -bottom-3 w-10 h-10 bg-[#00ADC5]/5 rounded-full blur-xl"></div>
                </div>
            @endif
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-blue-200 transition-all">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">My Dept</span>
                <div class="flex items-end justify-between">
                    @if($stats['my_department_status'])
                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase ring-1 ring-inset {{ $stats['my_department_status']->status->color() }}">
                            {{ $stats['my_department_status']->status->label() }}
                        </span>
                    @else
                        <span class="text-[10px] font-bold text-slate-400 italic">Not Filed</span>
                    @endif
                    <a href="{{ route('attendance.index') }}" class="text-[9px] font-black text-blue-600 uppercase hover:text-blue-700">Open Grid →</a>
                </div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Submission History -->
            <div class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Attendance Submission History</h3>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Recently Filed</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Week Period</th>
                                <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($attendanceHistory as $record)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-2.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 border border-slate-200 shadow-sm">
                                                <i data-lucide="calendar" class="w-4 h-4 text-[#00ADC5]"></i>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-800 leading-tight text-xs">Week of {{ $record->week_start_date->format('M d, Y') }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">Cycle #{{ $record->id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-2.5 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $record->status->color() }}">
                                            {{ $record->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-2.5 text-right">
                                        @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                            <a href="{{ route('attendance.index', ['week' => $record->week_start_date->toDateString()]) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-slate-50 rounded-lg text-[9px] font-black text-slate-400 uppercase tracking-widest hover:bg-[#00ADC5] hover:text-white transition-all">
                                                Audit →
                                            </a>
                                        @else
                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">Archived</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <i data-lucide="layers-2" class="w-8 h-8 mb-3"></i>
                                            <p class="text-[10px] font-black uppercase tracking-widest">No previous submissions</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Side Profile Card -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-xl border border-slate-200 p-5 relative overflow-hidden shadow-sm">
                    <div class="relative z-10 space-y-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#00ADC5] to-[#007A8A] p-0.5 shadow-lg shadow-[#00ADC5]/10">
                            <div class="w-full h-full rounded-[10px] bg-white flex items-center justify-center">
                                <span class="text-xl font-black text-[#00ADC5]">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-black tracking-tight text-slate-900">{{ Auth::user()->name }}</h4>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase block mb-0.5">My Role</span>
                                <span class="text-xs font-bold text-slate-700">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-[9px] font-black text-slate-400 uppercase block mb-0.5">Member Since</span>
                                <span class="text-xs font-bold text-slate-700">{{ Auth::user()->created_at->format('M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-10 -top-10 w-24 h-24 bg-[#00ADC5]/5 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
