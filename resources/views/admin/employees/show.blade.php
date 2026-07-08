<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">
                    <a href="{{ route('admin.employees.index') }}" class="hover:text-[#00ADC5]">Staff Registry</a>
                    <span class="mx-2 text-slate-200">/</span>
                    <span class="text-slate-600 italic uppercase">Personnel File</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $employee->full_name }}</h2>
                    @if($employee->status === 'active')
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-emerald-50 text-emerald-600 ring-emerald-200">Active</span>
                    @elseif($employee->status === 'inactive')
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-amber-50 text-amber-600 ring-amber-200">Inactive</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-rose-50 text-rose-600 ring-rose-200">Terminated</span>
                    @endif
                </div>
                <p class="text-slate-500 text-xs font-medium mt-1">System UID: <span class="font-black text-slate-700">{{ $employee->employee_id }}</span></p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.employees.edit', $employee) }}"
                    class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Personnel Data Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="id-card" class="w-3.5 h-3.5 text-[#00ADC5]"></i>
                            Identity & Bio
                        </h3>
                        <dl class="space-y-3">
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Full Identity</dt>
                                <dd class="text-xs font-black text-slate-800">{{ $employee->full_name }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Secure Email</dt>
                                <dd class="text-xs font-black text-slate-800">{{ $employee->email }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Mobile Access</dt>
                                <dd class="text-xs font-black text-slate-800">{{ $employee->phone ?? 'Not Configured' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-indigo-500"></i>
                            Organizational Hub
                        </h3>
                        <dl class="space-y-3">
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Division Node</dt>
                                <dd class="text-xs font-black text-slate-800">{{ $employee->department->name }} Bureau</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Position / Site</dt>
                                <dd class="text-xs font-black text-slate-800">{{ $employee->position ?? 'Associate' }} <span class="text-slate-300 mx-1">@</span> {{ $employee->site ?? 'Unspecified' }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-slate-400 uppercase tracking-tighter mb-0.5">Joining Date</dt>
                                <dd class="text-xs font-black text-slate-800">{{ $employee->date_of_joining ? $employee->date_of_joining->format('M d, Y') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                @if($employee->status === 'terminated')
                    <div class="bg-rose-50 rounded-xl border border-rose-100 p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center">
                                <i data-lucide="user-x" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-[10px] font-black text-rose-600 uppercase tracking-widest leading-none mb-0.5">Termination Summary</h3>
                                <p class="text-[10px] font-bold text-rose-500">Personnel file has been decommissioned.</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-0.5">Deactivation Reason</dt>
                                <dd class="text-xs font-black text-rose-700">{{ $employee->termination_reason }}</dd>
                            </div>
                            <div class="flex flex-col">
                                <dt class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-0.5">Effective Date</dt>
                                <dd class="text-xs font-black text-rose-700">{{ $employee->termination_date ? $employee->termination_date->format('M d, Y') : 'Unknown' }}</dd>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Presence -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-3 border-b border-slate-100 flex items-center gap-2">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-amber-500"></i>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Presence History</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($employee->attendanceEntries()->with('weeklyAttendance')->latest()->take(5)->get() as $entry)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-2.5">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-800">Week of {{ $entry->weeklyAttendance->week_start_date->format('M d, Y') }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Record #{{ $entry->weeklyAttendance->id }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-2.5 text-center">
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $entry->weeklyAttendance->status->color() }}">
                                                {{ $entry->weeklyAttendance->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-2.5 text-right">
                                            <a href="{{ route('attendance.show', $entry->weeklyAttendance->id) }}"
                                                class="text-[9px] font-black text-[#00ADC5] uppercase tracking-widest hover:underline">View →</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-8 text-center">
                                            <p class="text-slate-400 font-medium italic text-xs">No presence records archived.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-xl border border-slate-200 p-5 relative overflow-hidden shadow-sm">
                    <div class="flex flex-col items-center text-center space-y-4 relative z-10">
                        <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-[#00ADC5] to-[#007A8A] flex items-center justify-center text-2xl font-black text-white shadow-xl ring-4 ring-[#00ADC5]/10">
                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-base font-black tracking-tight uppercase text-slate-900">{{ $employee->full_name }}</h3>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($employee->user->role) }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 mt-4 relative z-10">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[9px] font-black text-slate-400 uppercase block mb-0.5">Access Status</span>
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full {{ $employee->is_active ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
                                <span class="text-xs font-black text-slate-700 tracking-widest uppercase">{{ $employee->is_active ? 'Synchronized' : 'Restricted' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-10 -top-10 w-24 h-24 bg-cyan-50 rounded-full blur-3xl"></div>
                </div>

                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                        Authority Terminal
                    </h4>
                    @if($employee->status === 'active')
                        <button onclick="openTerminateModal('{{ $employee->id }}', '{{ $employee->full_name }}')"
                            class="w-full text-left p-4 bg-rose-50 rounded-xl border border-rose-100 group hover:bg-rose-100 transition-all flex items-center justify-between mb-2">
                            <div>
                                <span class="text-[10px] font-black text-rose-700 uppercase tracking-widest block">Terminate</span>
                                <span class="text-[9px] font-bold text-rose-400 uppercase mt-0.5 block italic">Archive File</span>
                            </div>
                            <i data-lucide="power" class="w-4 h-4 text-rose-300 group-hover:text-rose-500 transition-colors"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Immediately deactivate this node?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full text-center py-2.5 text-[9px] font-black text-slate-300 uppercase tracking-widest hover:text-rose-400 transition-colors">
                                Quick Deactivation
                            </button>
                        </form>
                    @elseif($employee->status !== 'active')
                        <form method="POST" action="{{ route('admin.employees.activate', $employee) }}">
                            @csrf
                            <button type="submit" class="w-full text-left p-4 bg-emerald-50 rounded-xl border border-emerald-100 group hover:bg-emerald-100 transition-all flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest block">Re-Initialize</span>
                                    <span class="text-[9px] font-bold text-emerald-400 uppercase mt-0.5 block italic">Restore Access</span>
                                </div>
                                <i data-lucide="zap" class="w-4 h-4 text-emerald-300 group-hover:text-emerald-500 transition-colors"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Termination Modal -->
    <div id="terminateModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeTerminateModal()" aria-hidden="true"></div>
            <div class="relative inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                            <i data-lucide="power" class="w-6 h-6"></i>
                        </div>
                        <button onclick="closeTerminateModal()" class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight mb-1">Terminate Protocol</h3>
                    <p class="text-slate-500 text-xs font-medium mb-4">Deactivating this personnel node will restrict all system access immediately.</p>
                    <form id="terminateForm" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-3">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Reason</label>
                                <select name="termination_reason" required class="w-full py-2.5 px-3 bg-slate-50 border-2 border-slate-50 rounded-xl text-sm font-bold text-slate-700 focus:border-rose-400 focus:ring-2 focus:ring-rose-400/10 transition-all outline-none">
                                    <option value="Resigned">Resigned</option>
                                    <option value="Contract Ended">Contract Ended</option>
                                    <option value="Dismissed">Dismissed</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Effective Date</label>
                                <input type="date" name="termination_date" required value="{{ date('Y-m-d') }}" class="w-full py-2.5 px-3 bg-slate-50 border-2 border-slate-50 rounded-xl text-sm font-bold text-slate-700 focus:border-rose-400 focus:ring-2 focus:ring-rose-400/10 transition-all outline-none">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-rose-500 rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-rose-100 hover:bg-rose-600 transition-all active:scale-95">Confirm Termination</button>
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
