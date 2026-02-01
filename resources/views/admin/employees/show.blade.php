<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modern Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-[0.2rem] text-slate-400 mb-2">
                    <a href="{{ route('admin.employees.index') }}" class="hover:text-[#00ADC5]">Staff Registry</a>
                    <span class="mx-3 text-slate-200">/</span>
                    <span class="text-slate-600 italic uppercase">Personnel File</span>
                </nav>
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ $employee->full_name }}</h2>
                    @if($employee->is_active)
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)]"></span>
                    @else
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-400 shadow-[0_0_10px_rgba(251,113,133,0.5)]"></span>
                    @endif
                </div>
                <p class="text-slate-500 font-medium mt-1 uppercase tracking-wider text-[11px]">System Node UID: <span
                        class="font-black text-slate-700">{{ $employee->employee_id }}</span></p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.employees.edit', $employee) }}"
                    class="px-8 py-4 bg-[#00ADC5] text-white text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    Modify Record
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content: Stats and Details -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Personal Info -->
                    <div
                        class="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-110 transition-transform">
                        </div>
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                            <i data-lucide="id-card" class="w-4 h-4 text-[#00ADC5]"></i>
                            Identity & Contact
                        </h3>
                        <dl class="space-y-6 relative z-10">
                            <div class="flex flex-col">
                                <dt
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1 select-none">
                                    Full Name</dt>
                                <dd class="text-sm font-black text-slate-800 tracking-tight">{{ $employee->full_name }}
                                </dd>
                            </div>
                            <div class="flex flex-col">
                                <dt
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1 select-none">
                                    Secure Email Address</dt>
                                <dd class="text-sm font-black text-slate-800 tracking-tight">{{ $employee->email }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1 select-none">
                                    Communication Link (Phone)</dt>
                                <dd class="text-sm font-black text-slate-800 tracking-tight">
                                    {{ $employee->phone ?? 'Protocol pending linkage' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Work Info -->
                    <div
                        class="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-110 transition-transform">
                        </div>
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2">
                            <i data-lucide="briefcase" class="w-4 h-4 text-indigo-500"></i>
                            Organizational Hub
                        </h3>
                        <dl class="space-y-6 relative z-10">
                            <div class="flex flex-col">
                                <dt
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1 select-none">
                                    Division Node</dt>
                                <dd class="text-sm font-black text-slate-800 tracking-tight">
                                    {{ $employee->department->name }} Division</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1 select-none">
                                    Position Designation</dt>
                                <dd class="text-sm font-black text-slate-800 tracking-tight">
                                    {{ $employee->position ?? 'Associate Terminal' }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt
                                    class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1 select-none">
                                    Deployment Date</dt>
                                <dd class="text-sm font-black text-slate-800 tracking-tight">
                                    {{ $employee->date_of_joining ? $employee->date_of_joining->format('M d, Y') : 'Deployment in progress' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Recent Presence -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4 text-amber-500"></i>
                            Recent Activity Logs (Presence)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-50">
                                @forelse($employee->attendanceEntries()->with('weeklyAttendance')->latest()->take(10)->get() as $entry)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-10 py-6">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-xs font-black text-slate-800 group-hover:text-[#00ADC5] transition-colors">Week
                                                    of
                                                    {{ $entry->weeklyAttendance->week_start_date->format('M d, Y') }}</span>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter leading-none mt-1">Submission
                                                    Cycle #{{ $entry->weeklyAttendance->id }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-center">
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $entry->weeklyAttendance->status->color() }}">
                                                {{ $entry->weeklyAttendance->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <a href="{{ route('manager.approvals.show', $entry->weeklyAttendance->id) }}"
                                                class="bg-slate-50 px-4 py-2 rounded-xl text-slate-400 hover:text-[#00ADC5] hover:bg-cyan-50 text-[10px] font-black uppercase tracking-widest transition-all">Review
                                                Info →</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-10 py-16 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <i data-lucide="layers-2" class="w-10 h-10 mb-4"></i>
                                                <p class="text-[10px] font-black uppercase tracking-widest">No activity logs
                                                    archived for this node.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Profile Sidebar -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Status Badge Card -->
                <div
                    class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl ring-1 ring-white/10">
                    <div class="flex flex-col items-center text-center space-y-6 relative z-10">
                        <div
                            class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-[#00ADC5] to-[#007A8A] flex items-center justify-center text-3xl font-black text-white shadow-2xl ring-4 ring-white/10 uppercase italic">
                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-black tracking-tight mb-2">{{ $employee->full_name }}</h3>
                            <span
                                class="inline-flex px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-[0.2em] {{ $employee->is_active ? 'bg-emerald-500/10 text-emerald-400 ring-1 ring-emerald-500/20' : 'bg-rose-500/10 text-rose-400 ring-1 ring-rose-500/20 shadow-lg shadow-rose-900/10' }}">
                                {{ $employee->is_active ? 'Operational (Active)' : 'Decommissioned' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4 mt-10 relative z-10">
                        <div class="p-5 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
                            <span
                                class="text-[9px] font-black text-white/30 uppercase tracking-[0.1em] block mb-2">Protocol
                                Access Role</span>
                            <span
                                class="text-xs font-black text-white tracking-widest uppercase italic text-cyan-400">{{ ucfirst($employee->user->role) }}
                                Overlord</span>
                        </div>
                        <div class="p-5 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
                            <span
                                class="text-[9px] font-black text-white/30 uppercase tracking-[0.1em] block mb-2">Registry
                                Tier</span>
                            <span class="text-xs font-black text-white tracking-widest uppercase">Full Personnel Record
                                Access</span>
                        </div>
                    </div>

                    <!-- Decor -->
                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-16 -bottom-16 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
                </div>

                <!-- Admin Management Tools -->
                <div
                    class="bg-white rounded-[2.5rem] border border-slate-200 p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden group">
                    <div
                        class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#00ADC5]/20 to-transparent">
                    </div>
                    <h4
                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                        Authority Terminal
                    </h4>

                    @if($employee->is_active)
                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}"
                            onsubmit="return confirm('Initiate archival protocol for this file?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full text-left p-5 bg-rose-50 rounded-3xl border border-rose-100 group/btn hover:bg-rose-100 transition-all flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-[11px] font-black text-rose-700 uppercase tracking-widest block">Decommission
                                        Node</span>
                                    <span class="text-[9px] font-bold text-rose-400 uppercase mt-0.5 block italic">Seal
                                        personnel record</span>
                                </div>
                                <i data-lucide="power"
                                    class="w-5 h-5 text-rose-300 group-hover/btn:text-rose-500 transition-colors"></i>
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.employees.activate', $employee) }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left p-5 bg-emerald-50 rounded-3xl border border-emerald-100 group/btn hover:bg-emerald-100 transition-all flex items-center justify-between">
                                <div>
                                    <span
                                        class="text-[11px] font-black text-emerald-700 uppercase tracking-widest block">Re-Initialize
                                        node</span>
                                    <span
                                        class="text-[9px] font-bold text-emerald-400 uppercase mt-0.5 block italic">Restore
                                        full system access</span>
                                </div>
                                <i data-lucide="zap"
                                    class="w-5 h-5 text-emerald-300 group-hover/btn:text-emerald-500 transition-colors"></i>
                            </button>
                        </form>
                    @endif

                    <div class="mt-4 p-5 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                        <h5
                            class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 italic flex items-center gap-2">
                            <i data-lucide="shield-info" class="w-3.5 h-3.5 text-slate-300"></i>
                            Security Bulletin
                        </h5>
                        <p class="text-[10px] font-bold text-slate-400 leading-relaxed italic">Changes to operational
                            status trigger a system-wide sync audit and alert the Division Hub.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>