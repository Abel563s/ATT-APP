<x-app-layout>
    <div class="py-6 space-y-8">
        <!-- Modern Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-[0.2rem] text-slate-400 mb-2">
                    <a href="{{ route('admin.employees.index') }}" class="hover:text-[#00ADC5]">Staff Registry</a>
                    <span class="mx-3 text-slate-200">/</span>
                    <span class="text-slate-600 italic uppercase">Personnel File</span>
                </nav>
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ $employee->full_name }}</h2>
                    @if($employee->status === 'active')
                        <span
                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-emerald-50 text-emerald-600 ring-emerald-200">Operational</span>
                    @elseif($employee->status === 'inactive')
                        <span
                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-amber-50 text-amber-600 ring-amber-200">Inactive</span>
                    @else
                        <span
                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-rose-50 text-rose-600 ring-rose-200">Terminated</span>
                    @endif
                </div>
                <p class="text-slate-500 font-medium mt-1 uppercase tracking-wider text-[11px]">System UID: <span
                        class="font-black text-slate-700">{{ $employee->employee_id }}</span></p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.employees.edit', $employee) }}"
                    class="px-8 py-4 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-2xl shadow-sm hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                    Edit Record
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Personnel Data Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Identity & Profile -->
                    <div
                        class="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-110 transition-transform">
                        </div>
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2 relative z-10">
                            <i data-lucide="id-card" class="w-4 h-4 text-[#00ADC5]"></i>
                            Identity & Bio
                        </h3>
                        <dl class="space-y-6 relative z-10">
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Full
                                    Identity</dt>
                                <dd class="text-sm font-black text-slate-800">{{ $employee->full_name }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Secure
                                    Email</dt>
                                <dd class="text-sm font-black text-slate-800">{{ $employee->email }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Mobile
                                    Access</dt>
                                <dd class="text-sm font-black text-slate-800">{{ $employee->phone ?? 'Not Configured' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Work Context -->
                    <div
                        class="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm relative overflow-hidden group">
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-bl-full -mr-10 -mt-10 group-hover:scale-110 transition-transform">
                        </div>
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 flex items-center gap-2 relative z-10">
                            <i data-lucide="briefcase" class="w-4 h-4 text-indigo-500"></i>
                            Organizational Hub
                        </h3>
                        <dl class="space-y-6 relative z-10">
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">
                                    Division Node</dt>
                                <dd class="text-sm font-black text-slate-800">{{ $employee->department->name }} Bureau
                                </dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">
                                    Position / Site</dt>
                                <dd class="text-sm font-black text-slate-800">{{ $employee->position ?? 'Associate' }}
                                    <span class="text-slate-300 mx-2">@</span> {{ $employee->site ?? 'Unspecified' }}
                                </dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-1">Joining
                                    Date</dt>
                                <dd class="text-sm font-black text-slate-800">
                                    {{ $employee->date_of_joining ? $employee->date_of_joining->format('M d, Y') : 'N/A' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Termination Record (if applies) -->
                @if($employee->status === 'terminated')
                    <div class="bg-rose-50 rounded-[2.5rem] p-10 border border-rose-100 relative overflow-hidden">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center">
                                <i data-lucide="user-x" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3
                                    class="text-[10px] font-black text-rose-600 uppercase tracking-widest leading-none mb-1">
                                    Termination Summary</h3>
                                <p class="text-xs font-bold text-rose-500">Personnel file has been decommissioned.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Deactivation
                                    Reason</dt>
                                <dd class="text-sm font-black text-rose-700">{{ $employee->termination_reason }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Effective
                                    Date</dt>
                                <dd class="text-sm font-black text-rose-700">
                                    {{ $employee->termination_date ? $employee->termination_date->format('M d, Y') : 'Unknown' }}
                                </dd>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Presence -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between">
                        <h3
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="activity" class="w-4 h-4 text-amber-500"></i>
                            Presence History (Logs)
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($employee->attendanceEntries()->with('weeklyAttendance')->latest()->take(5)->get() as $entry)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-10 py-6">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-800">Week of
                                                    {{ $entry->weeklyAttendance->week_start_date->format('M d, Y') }}</span>
                                                <span
                                                    class="text-[9px] font-bold text-slate-400 uppercase mt-1 tracking-tighter">Record
                                                    ID: #{{ $entry->weeklyAttendance->id }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-center">
                                            <span
                                                class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $entry->weeklyAttendance->status->color() }}">
                                                {{ $entry->weeklyAttendance->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <a href="{{ route('attendance.show', $entry->weeklyAttendance->id) }}"
                                                class="text-[10px] font-black text-[#00ADC5] uppercase tracking-widest hover:underline">View
                                                Record →</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-10 py-16 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <i data-lucide="layers-2" class="w-10 h-10 mb-4"></i>
                                                <p class="text-[10px] font-black uppercase tracking-widest">No presence
                                                    records archived.</p>
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
                <!-- Status Badge Card (Lightened) -->
                <div class="bg-white rounded-[2.5rem] p-10 border border-slate-200 relative overflow-hidden shadow-sm">
                    <div class="flex flex-col items-center text-center space-y-6 relative z-10">
                        <div
                            class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-[#00ADC5] to-[#007A8A] flex items-center justify-center text-3xl font-black text-white shadow-2xl ring-4 ring-[#00ADC5]/10 italic">
                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-black tracking-tight mb-2 uppercase text-slate-900">
                                {{ $employee->full_name }}</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">
                                {{ ucfirst($employee->user->role) }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 mt-10 relative z-10">
                        <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                            <span
                                class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] block mb-2">Protocol
                                Access</span>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-2 h-2 rounded-full {{ $employee->is_active ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]' : 'bg-rose-400 shadow-[0_0_8px_rgba(251,113,133,0.5)]' }}">
                                </div>
                                <span class="text-xs font-black text-slate-700 tracking-widest uppercase">System:
                                    {{ $employee->is_active ? 'Synchronized' : 'Restricted' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-cyan-50 rounded-full blur-3xl"></div>
                </div>

                <!-- Admin Management Tools -->
                <div
                    class="bg-white rounded-[2.5rem] border border-slate-200 p-8 flex flex-col gap-4 shadow-sm relative overflow-hidden">
                    <h4
                        class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                        Authority Terminal
                    </h4>

                    @if($employee->status === 'active')
                        <button onclick="openTerminateModal('{{ $employee->id }}', '{{ $employee->full_name }}')"
                            class="w-full text-left p-6 bg-rose-50 rounded-3xl border border-rose-100 group hover:bg-rose-100 transition-all flex items-center justify-between">
                            <div>
                                <span class="text-[11px] font-black text-rose-700 uppercase tracking-widest block">Terminate
                                    Protocol</span>
                                <span class="text-[9px] font-bold text-rose-400 uppercase mt-0.5 block italic">Archive
                                    Personnel File</span>
                            </div>
                            <i data-lucide="power"
                                class="w-5 h-5 text-rose-300 group-hover:text-rose-500 transition-colors"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}"
                            onsubmit="return confirm('Immediately deactivate this node?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full text-center py-4 text-[9px] font-black text-slate-300 uppercase tracking-widest hover:text-rose-400 transition-colors">
                                Quick Deactivation
                            </button>
                        </form>
                    @elseif($employee->status !== 'active')
                        <form method="POST" action="{{ route('admin.employees.activate', $employee) }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left p-6 bg-emerald-50 rounded-3xl border border-emerald-100 group hover:bg-emerald-100 transition-all flex items-center justify-between shadow-lg shadow-emerald-900/5">
                                <div>
                                    <span
                                        class="text-[11px] font-black text-emerald-700 uppercase tracking-widest block">Re-Initialize
                                        Node</span>
                                    <span
                                        class="text-[9px] font-bold text-emerald-400 uppercase mt-0.5 block italic">Restore
                                        Registry Access</span>
                                </div>
                                <i data-lucide="zap"
                                    class="w-5 h-5 text-emerald-300 group-hover:text-emerald-500 transition-colors"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Termination Modal (Centered Div) -->
    <div id="terminateModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity"
                onclick="closeTerminateModal()" aria-hidden="true"></div>

            <!-- Modal panel -->
            <div
                class="relative inline-block align-middle bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20">
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center">
                            <i data-lucide="power" class="w-8 h-8"></i>
                        </div>
                        <button onclick="closeTerminateModal()"
                            class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                    </div>

                    <h3 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Terminate Protocol</h3>
                    <p class="text-slate-500 font-medium mb-8">Deactivating this personnel node will restrict all system
                        access immediately.</p>

                    <form id="terminateForm" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive
                                    Reason</label>
                                <select name="termination_reason" required
                                    class="w-full py-4 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-sm font-bold text-slate-700 focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 transition-all outline-none">
                                    <option value="Resigned">Resigned</option>
                                    <option value="Contract Ended">Contract Ended</option>
                                    <option value="Dismissed">Dismissed</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Effective
                                    Date</label>
                                <input type="date" name="termination_date" required value="{{ date('Y-m-d') }}"
                                    class="w-full py-4 px-6 bg-slate-50 border-2 border-slate-50 rounded-2xl text-sm font-bold text-slate-700 focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 transition-all outline-none">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-5 bg-rose-500 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-rose-100 hover:bg-rose-600 transition-all active:scale-[0.98]">Confirm
                            Permanent Termination</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openTerminateModal(id, name) {
            document.getElementById('terminateForm').action = `/admin/employees/${id}/terminate`;
            document.getElementById('terminateModal').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function closeTerminateModal() {
            document.getElementById('terminateModal').classList.add('hidden');
        }
    </script>
</x-app-layout>