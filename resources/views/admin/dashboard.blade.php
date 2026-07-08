<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard</h2>
                <p class="text-slate-500 text-sm font-medium">System overview and quick insights</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
                    <div class="w-6 h-6 rounded-md bg-green-50 flex items-center justify-center text-green-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-slate-700">{{ now()->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Total Employees -->
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <i data-lucide="users" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Workforce</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['total_employees'] }}</h3>
                </div>
                <p class="text-[10px] font-medium text-slate-500 mt-1">Active employees</p>
            </div>

            <!-- Active Departments -->
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Divisions</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $stats['total_departments'] }}</h3>
                </div>
                <p class="text-[10px] font-medium text-slate-500 mt-1">Active departments</p>
            </div>

            <!-- Pending Manager -->
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg {{ $pendingApprovals > 0 ? 'bg-amber-50 text-amber-600' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center">
                        <i data-lucide="clock" class="w-4 h-4 {{ $pendingApprovals > 0 ? 'animate-pulse' : '' }}"></i>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Pending Mgr</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black {{ $pendingApprovals > 0 ? 'text-amber-600' : 'text-slate-900' }} tracking-tighter">{{ $pendingApprovals }}</h3>
                </div>
                <p class="text-[10px] font-medium text-slate-500 mt-1">Awaiting approval</p>
            </div>

            <!-- Approved -->
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Approved</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ \App\Models\WeeklyAttendance::where('status', \App\Enums\AttendanceStatus::APPROVED)->count() }}</h3>
                </div>
                <p class="text-[10px] font-medium text-slate-500 mt-1">Total approved</p>
            </div>
        </div>

        <!-- Bottom Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Department Performance -->
            <div class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Department Performance</h3>
                    <a href="{{ route('admin.reports') }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 rounded-lg text-[9px] font-black text-slate-400 uppercase hover:bg-[#00ADC5] hover:text-white transition-all">
                        Full Report
                    </a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $deptStats = \App\Models\WeeklyAttendance::with(['department'])
                                ->where('status', \App\Enums\AttendanceStatus::APPROVED)
                                ->get()
                                ->groupBy('department.name')
                                ->map(fn($records) => $records->count());
                            $maxCount = $deptStats->max() ?: 1;
                        @endphp

                        @if($deptStats->isNotEmpty())
                            @foreach($deptStats->take(8) as $dept => $count)
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-slate-700 w-32 truncate">{{ $dept }}</span>
                                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-[#00ADC5] rounded-full" style="width: {{ ($count / $maxCount) * 100 }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-500 w-8 text-right">{{ $count }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-400 text-xs font-medium">No approved attendance records yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="lg:col-span-4 space-y-4">
                <!-- Quick Stats -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">System Health</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-600">Active Employees</span>
                            <span class="text-xs font-black text-slate-900">{{ \App\Models\Employee::active()->count() }}</span>
                        </div>
                        <div class="h-px bg-slate-100"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-600">Total Departments</span>
                            <span class="text-xs font-black text-slate-900">{{ \App\Models\Department::active()->count() }}</span>
                        </div>
                        <div class="h-px bg-slate-100"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-600">Pending Approvals</span>
                            <span class="text-xs font-black {{ $pendingApprovals > 0 ? 'text-amber-600' : 'text-slate-900' }}">{{ $pendingApprovals }}</span>
                        </div>
                        <div class="h-px bg-slate-100"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-600">Total Attendance</span>
                            <span class="text-xs font-black text-slate-900">{{ \App\Models\WeeklyAttendance::count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Admin Shortcuts -->
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Quick Access</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.employees.index') }}"
                            class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-blue-200 hover:bg-blue-50 transition-all">
                            <span class="text-xs font-bold text-slate-600 group-hover:text-blue-700">Employee Directory</span>
                            <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.reports') }}"
                            class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-indigo-200 hover:bg-indigo-50 transition-all">
                            <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-700">Generate Report</span>
                            <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.attendance.history') }}"
                            class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 group hover:border-emerald-200 hover:bg-emerald-50 transition-all">
                            <span class="text-xs font-bold text-slate-600 group-hover:text-emerald-700">Attendance History</span>
                            <svg class="w-3.5 h-3.5 text-slate-300 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
