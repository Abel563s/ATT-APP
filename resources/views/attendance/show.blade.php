<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modernized Approval Header -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="flex items-center gap-6">
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.attendance.history') : route('attendance.history') }}" 
                       class="w-12 h-12 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all group">
                        <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    <div
                        class="w-20 h-20 rounded-[1.75rem] bg-cyan-50 flex items-center justify-center text-[#00ADC5] border-4 border-white shadow-xl shadow-cyan-100/50 ring-1 ring-cyan-100">
                        <i data-lucide="file-check" class="w-10 h-10"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Attendance Review</h2>
                            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $attendance->status->color() }}">
                                {{ $attendance->status->label() }}
                            </span>
                        </div>
                        <p
                            class="text-slate-500 font-bold mt-1 uppercase text-xs tracking-widest flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#00ADC5] animate-pulse"></span>
                            {{ $attendance->department->name ?? 'Unknown Department' }} • {{ $attendance->week_start_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>


            </div>

            <!-- Enhanced Data Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 border-t border-slate-50 bg-slate-50/30">
                <div class="px-10 py-8 border-r border-slate-50">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Data
                        Nodes</span>
                    <span class="text-3xl font-black text-slate-800 leading-none">{{ $attendance->entries->count() }}
                        Profiles</span>
                </div>
                <div class="px-10 py-8 border-r border-slate-50">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Origin
                        Submitter</span>
                    <span
                        class="text-sm font-black text-slate-700 truncate block">{{ $attendance->submitter->name ?? 'Unknown' }}</span>
                </div>
                <div class="px-10 py-8 border-r border-slate-50">
                    <span
                        class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Synchronization</span>
                    <span
                        class="text-sm font-black text-slate-700 block">{{ $attendance->updated_at->format('M d, H:i') }}</span>
                </div>
                <div class="px-10 py-8">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Integrity
                        Check</span>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-lg shadow-emerald-200"></div>
                        <span class="text-sm font-black text-emerald-600 uppercase tracking-tight">Verified Solid</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable Attendance Matrix -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto relative">
                <table class="w-full text-sm text-center border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="sticky left-0 bg-slate-50/50 z-20 px-10 py-6 min-w-[280px] text-left border-r border-slate-200">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Personnel
                                    ID Matrix</span>
                            </th>
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <th class="px-2 py-6 border-r border-slate-50 last:border-slate-200" colspan="2">
                                    <span
                                        class="text-[10px] font-black text-slate-600 uppercase tracking-widest block mb-1">{{ $day }}</span>
                                    <span
                                        class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $attendance->week_start_date->addDays($loop->index)->format('d M') }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($attendance->entries as $entry)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td
                                    class="sticky left-0 bg-white z-10 px-10 py-6 border-r border-slate-200 text-left shadow-[5px_0_15px_-5px_rgba(0,0,0,0.05)] transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 font-black text-xs border-2 border-white ring-1 ring-slate-100 shadow-sm uppercase group-hover:bg-white transition-colors">
                                            {{ substr($entry->employee->first_name, 0, 1) }}{{ substr($entry->employee->last_name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="font-black text-slate-800 leading-none mb-1">{{ $entry->employee->full_name }}</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.05em]">{{ $entry->employee->employee_id }}</span>
                                        </div>
                                    </div>
                                </td>
                                @foreach(['mon_m', 'mon_a', 'tue_m', 'tue_a', 'wed_m', 'wed_a', 'thu_m', 'thu_a', 'fri_m', 'fri_a', 'sat_m', 'sat_a'] as $field)
                                    @php 
                                        $val = $entry->{$field}; 
                                        $dbCode = $val ? ($codesMap[$val] ?? null) : null;
                                    @endphp
                                    <td class="p-1 border-r border-slate-50 last:border-slate-200">
                                        <div
                                            class="w-11 h-11 flex items-center justify-center mx-auto rounded-xl text-[10px] font-black border-2 border-transparent transition-all shadow-sm {{ $dbCode ? $dbCode->colorClasses() : 'bg-slate-50 text-slate-300' }}">
                                            {{ $val ?: '-' }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Audit & Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <div class="lg:col-span-8 bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-10">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-10 flex items-center gap-3">
                    <i data-lucide="history" class="w-4 h-4 text-[#00ADC5]"></i>
                    System Protocol History
                </h3>
                <div
                    class="relative space-y-10 before:absolute before:left-6 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-50">
                    @foreach($attendance->logs as $log)
                        <div class="relative pl-16">
                            <div
                                class="absolute left-0 w-12 h-12 rounded-[1rem] bg-white border-2 border-slate-100 flex items-center justify-center z-10 shadow-sm transition-transform hover:scale-105">
                                @if($log->action == 'approved')
                                    <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i>
                                @elseif($log->action == 'rejected')
                                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-500"></i>
                                @else
                                    <i data-lucide="upload-cloud" class="w-5 h-5 text-cyan-500"></i>
                                @endif
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm font-black text-slate-900 leading-none">{{ $log->user->name }}</span>
                                    <span
                                        class="text-[10px] font-black text-slate-300 uppercase tracking-widest">{{ $log->created_at->format('M d, H:i') }}</span>
                                </div>
                                <span
                                    class="text-[10px] font-black uppercase tracking-[0.2em] mt-1 {{ $log->action == 'approved' ? 'text-emerald-500' : ($log->action == 'rejected' ? 'text-rose-500' : 'text-cyan-500') }}">
                                    Protocol: {{ strtoupper($log->action) }}
                                </span>
                                @if($log->comment)
                                    <div
                                        class="mt-4 p-5 bg-slate-50 rounded-[1.25rem] border border-slate-100 relative max-w-lg">
                                        <p class="text-sm text-slate-600 italic font-medium leading-relaxed">
                                            "{{ $log->comment }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2.5rem] p-10 relative overflow-hidden shadow-sm border border-slate-200">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 relative z-10">
                        Verification Node</h3>
                    <div class="space-y-4 relative z-10">
                        <div
                            class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-slate-100 transition-all cursor-pointer group">
                            <i data-lucide="download"
                                class="w-4 h-4 text-[#00ADC5] mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-slate-400 uppercase block mb-1">Raw Export</span>
                            <span class="text-sm font-bold text-slate-900">Capture System Snapshot</span>
                        </div>
                        <div
                            class="p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-slate-100 transition-all cursor-pointer group">
                            <i data-lucide="bell"
                                class="w-4 h-4 text-[#00ADC5] mb-3 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] font-black text-slate-400 uppercase block mb-1">Alert
                                Submitter</span>
                            <span class="text-sm font-bold text-slate-900">Broadcast Protocol Status</span>
                        </div>
                    </div>
                    <!-- Decor -->
                    <div class="absolute -right-24 -bottom-24 w-64 h-64 bg-[#00ADC5]/5 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Background overlay, show/hide based on modal state. -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="hideRejectModal()" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel, show/hide based on modal state. -->
            <div class="relative inline-block align-middle bg-white rounded-[2.5rem] text-left overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.3)] transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20">
                <form action="{{ route('manager.approvals.reject', $attendance->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-10 pt-12 pb-8">
                        <div
                            class="w-20 h-20 rounded-[1.5rem] bg-rose-50 flex items-center justify-center text-rose-600 mb-8 mx-auto sm:mx-0 shadow-inner">
                            <i data-lucide="shield-alert" class="w-10 h-10"></i>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 mb-4 tracking-tight" id="modal-title">Rejection Feedback</h3>
                        <p class="text-slate-500 font-medium mb-8 leading-relaxed">Provide administrative reason for
                            declining this data node. This feedback is critical for the synchronization process.</p>
                        <textarea name="comment" required
                            class="w-full rounded-[1.5rem] border-2 border-slate-50 bg-slate-50/50 p-6 font-bold text-slate-700 focus:border-rose-400 focus:ring-4 focus:ring-rose-400/10 transition-all text-sm outline-none"
                            rows="4" placeholder="e.g. Protocol mismatch on decentralized data nodes..."></textarea>
                    </div>
                    <div class="bg-slate-50 px-10 py-8 sm:flex sm:flex-row-reverse gap-4">
                        <button type="submit"
                            class="flex-1 inline-flex justify-center rounded-2xl bg-rose-600 py-4 text-xs font-black text-white hover:bg-rose-700 shadow-xl shadow-rose-200 transition-all active:scale-95 uppercase tracking-widest">Execute
                            Refusal</button>
                        <button type="button" onclick="hideRejectModal()"
                            class="flex-1 inline-flex justify-center rounded-2xl bg-white border-2 border-slate-100 py-4 text-xs font-black text-slate-500 hover:bg-slate-50 transition-all active:scale-95 uppercase tracking-widest">Abort</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() { 
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden'); 
            // Focus the textarea for better UX
            setTimeout(() => {
                modal.querySelector('textarea').focus();
            }, 100);
            if (typeof lucide !== 'undefined') lucide.createIcons(); 
        }
        function hideRejectModal() { 
            document.getElementById('rejectModal').classList.add('hidden'); 
        }
    </script>
    @else
    <script>
        function showRejectModal() {}
        function hideRejectModal() {}
    </script>
    @endif
</x-app-layout>