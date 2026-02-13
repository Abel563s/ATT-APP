<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modernized Breadcrumbs & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Overview</h2>
                <p class="text-slate-500 font-medium">Monitoring attendance performance across all divisions.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="bg-white border border-slate-200 rounded-2xl px-4 py-2 flex items-center gap-3 shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Today</span>
                        <span class="text-xs font-bold text-slate-700">{{ now()->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Employees -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <span
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Workforce</span>
                    <h3 class="text-4xl font-black text-slate-900 mb-1 tracking-tighter">{{ $stats['total_employees'] }}
                    </h3>
                    <p class="text-xs font-bold text-slate-500">Active Employees</p>
                </div>
                <div
                    class="absolute right-6 bottom-6 w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <!-- Active Departments -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <span
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Organization</span>
                    <h3 class="text-4xl font-black text-slate-900 mb-1 tracking-tighter">
                        {{ $stats['total_departments'] }}
                    </h3>
                    <p class="text-xs font-bold text-slate-500">Departments</p>
                </div>
                <div
                    class="absolute right-6 bottom-6 w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>

            <!-- Pending Review -->
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="relative z-10">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Action
                        Required</span>
                    <h3
                        class="text-4xl font-black {{ $pendingApprovals > 0 ? 'text-amber-600' : 'text-slate-900' }} mb-1 tracking-tighter">
                        {{ $pendingApprovals }}
                    </h3>
                    <p class="text-xs font-bold text-slate-500">Wait-listed Approvals</p>
                </div>
                <div
                    class="absolute right-6 bottom-6 w-12 h-12 {{ $pendingApprovals > 0 ? 'bg-amber-50 text-amber-600 animate-pulse' : 'bg-slate-50 text-slate-400' }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bottom Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Recent Activity List -->
            <div class="lg:col-span-8 bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Recent Submissions</h3>
                    <a href="{{ route('admin.attendance.history') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-xl text-[10px] font-black text-slate-400 uppercase hover:bg-[#00ADC5] hover:text-white transition-all">
                        View All History →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <tbody class="divide-y divide-slate-50">
                            @foreach($recentRecords as $record)
                                <tr class="hover:bg-slate-100/30 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100">
                                                {{ substr($record->department->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span
                                                    class="font-bold text-slate-800 leading-none mb-1">{{ $record->department->name ?? 'Unknown' }}</span>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Week:
                                                    {{ $record->week_start_date->format('M d') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-bold text-slate-600">{{ $record->submitter->name ?? 'Unknown' }}</span>
                                            <span
                                                class="text-[10px] text-slate-400">{{ $record->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span
                                            class="px-3 py-1 rounded-lg text-[10px] font-black uppercase ring-1 ring-inset {{ $record->status->color() }}">
                                            {{ $record->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 text-right">
                                        <a href="{{ route('manager.approvals.show', $record->id) }}"
                                            class="p-2 text-slate-300 hover:text-blue-600 transition-colors inline-block">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if($recentRecords->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center">
                                        <p class="text-slate-400 font-medium italic">No recent activity found.</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Access / Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Status Composition (Lightened) -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 relative overflow-hidden shadow-sm">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Status Composition</h3>
                    <div class="space-y-6 relative z-10">
                        @foreach(['Approved', 'Pending', 'Rejected'] as $stat)
                            <div class="space-y-2">
                                <div
                                    class="flex justify-between items-center text-[10px] font-black tracking-widest uppercase">
                                    <span class="text-slate-500">{{ $stat }}</span>
                                    <span
                                        class="text-slate-900">{{ $loop->first ? '85%' : ($loop->index == 1 ? '15%' : '0%') }}</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full @if($loop->index == 0) bg-emerald-500 @elseif($loop->index == 1) bg-amber-500 @else bg-rose-500 @endif"
                                        style="width: {{ $loop->first ? '85%' : ($loop->index == 1 ? '15%' : '0%') }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Decor -->
                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-slate-50 rounded-full blur-3xl"></div>
                </div>

                <!-- Admin Shortcuts -->
                <div class="bg-white rounded-3xl border border-slate-200 p-6 flex flex-col gap-3">
                    <a href="{{ route('admin.employees.index') }}"
                        class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-blue-200 hover:bg-blue-50 transition-all">
                        <span class="text-xs font-bold text-slate-600 group-hover:text-blue-700">Employee
                            Directory</span>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </a>
                    <a href="{{ route('admin.reports') }}"
                        class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-indigo-200 hover:bg-indigo-50 transition-all">
                        <span class="text-xs font-bold text-slate-600 group-hover:text-indigo-700">Generate
                            Report</span>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-500" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>