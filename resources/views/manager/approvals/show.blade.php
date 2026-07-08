<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('manager.approvals.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-[#00ADC5] transition-colors mb-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Back to Queue
                </a>
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Attendance Review</h2>
                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $attendance->status->color() }}">
                        {{ $attendance->status->label() }}
                    </span>
                </div>
                <p class="text-slate-500 text-xs font-medium mt-0.5 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#00ADC5]"></span>
                    {{ $attendance->department->name ?? 'Unknown Department' }} • {{ $attendance->week_start_date->format('M d, Y') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                @if((auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) && $attendance->status === \App\Enums\AttendanceStatus::PENDING && !auth()->user()->isSuperAdmin())
                    <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5">
                        <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest leading-none mb-0.5">Awaiting Manager Approval</p>
                        <p class="text-[10px] font-bold text-slate-500">Admins can approve after manager review.</p>
                    </div>
                @else
                    <button type="button" onclick="showRejectModal()"
                        class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-400 hover:text-red-500 hover:border-red-100 transition-all active:scale-95 uppercase tracking-widest">
                        <i data-lucide="x-circle" class="w-3.5 h-3.5 mr-1.5"></i>
                        Reject
                    </button>
                    <form action="{{ route('manager.approvals.approve', $attendance->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 uppercase tracking-widest">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5 mr-1.5"></i>
                            Approve
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Data Nodes</span>
                <span class="text-lg font-black text-slate-900 leading-none">{{ $attendance->entries->count() }} Profiles</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Submitted by</span>
                <span class="text-sm font-black text-slate-700 truncate block">{{ $attendance->submitter->name ?? 'Unknown' }}</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Created on</span>
                <span class="text-sm font-black text-slate-700 block">{{ $attendance->created_at->format('M d, H:i') }}</span>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Integrity Check</span>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <span class="text-sm font-black text-emerald-600 uppercase tracking-tight">Verified</span>
                </div>
            </div>
        </div>

        <!-- Attendance Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Attendance Grid</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Week of {{ $attendance->week_start_date->format('M d, Y') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-center border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="sticky left-0 bg-slate-50 z-20 px-6 py-3 min-w-[180px] text-left border-r border-slate-200">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Team Member</span>
                            </th>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <th class="px-2 py-3 border-r border-slate-100 last:border-slate-200" colspan="{{ $day === 'Saturday' ? '1' : '2' }}">
                                    <span class="text-[9px] font-black text-slate-600 uppercase tracking-widest leading-none block mb-0.5">{{ $day }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">{{ $attendance->week_start_date->copy()->addDays($loop->index)->format('d M') }}</span>
                                </th>
                            @endforeach
                        </tr>
                        <tr class="bg-slate-50/30 text-[9px] font-black uppercase tracking-widest text-slate-400">
                            <th class="sticky left-0 bg-slate-50/30 z-20 border-r border-slate-200 px-6 py-1"></th>
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <th class="py-1.5 px-1 border-r border-slate-100">Morn</th>
                                @if($day !== 'Sat')
                                    <th class="py-1.5 px-1 border-r border-slate-100 last:border-slate-200">Aftr</th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($attendance->entries as $entry)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="sticky left-0 bg-white hover:bg-slate-50 z-10 px-6 py-2.5 border-r border-slate-200 text-left shadow-[5px_0_15px_-5px_rgba(0,0,0,0.05)]">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-black text-[10px] border border-slate-200 shadow-sm uppercase">
                                            {{ substr($entry->employee->first_name, 0, 1) }}{{ substr($entry->employee->last_name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 leading-none mb-0.5 text-xs">{{ $entry->employee->full_name }}</span>
                                            <span class="text-[9px] font-bold text-slate-400">{{ $entry->employee->employee_id }}</span>
                                        </div>
                                    </div>
                                </td>
                                @foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat'] as $day)
                                    @foreach(['m', 'a'] as $period)
                                        @if($day === 'sat' && $period === 'a')
                                            @continue
                                        @endif
                                        @php $field = "{$day}_{$period}"; $value = $entry->{$field}; $dbCode = $value ? ($codesMap[$value] ?? null) : null; @endphp
                                        <td class="p-1 border-r border-slate-100 last:border-slate-200">
                                            <div class="w-10 h-10 flex items-center justify-center rounded-lg text-[10px] font-black {{ $dbCode ? "{$dbCode->bg_color} {$dbCode->text_color}" : 'bg-slate-50 text-slate-300' }}">
                                                {{ $value ?: '-' }}
                                            </div>
                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div id="rejectModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="hideRejectModal()" aria-hidden="true"></div>
                <div class="relative inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
                    <form method="POST" action="{{ route('manager.approvals.reject', $attendance->id) }}">
                        @csrf
                        <div class="bg-white px-6 pt-6 pb-4">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Reject Attendance</h3>
                            <p class="mt-2 text-xs text-slate-500 font-medium">Provide a reason for rejection. This will be sent back for correction.</p>
                            <div class="mt-4 space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Rejection Reason</label>
                                <textarea name="comment" required rows="3" class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-rose-500/10 text-sm" placeholder="Enter rejection reason..."></textarea>
                                <x-input-error :messages="$errors->get('comment')" />
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex gap-3">
                            <button type="submit" class="flex-1 py-2.5 bg-rose-500 rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-rose-600 transition-all active:scale-95">
                                Confirm Rejection
                            </button>
                            <button type="button" onclick="hideRejectModal()" class="flex-1 py-2.5 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Approval Logs -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="activity" class="w-3.5 h-3.5 text-amber-500"></i>
                    Approval Timeline
                </h3>
            </div>
            <div class="p-6">
                <div class="flow-root">
                    <div class="relative">
                        @foreach($attendance->logs as $log)
                            <div class="flex gap-4 pb-6 {{ !$loop->last ? 'border-l-2 border-slate-100 ml-3' : '' }}">
                                <div class="relative">
                                    <div class="w-6 h-6 rounded-full {{ $log->action === 'approved' ? 'bg-emerald-100 text-emerald-600' : ($log->action === 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600') }} flex items-center justify-center ring-4 ring-white">
                                        <i data-lucide="{{ $log->action === 'approved' ? 'check' : ($log->action === 'rejected' ? 'x' : 'send') }}" class="w-3 h-3"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ ucfirst($log->action) }}</p>
                                    <p class="text-[10px] text-slate-500 font-medium">{{ $log->user->name ?? 'System' }} • {{ $log->created_at->diffForHumans() }}</p>
                                    @if($log->comment)
                                        <p class="text-xs text-slate-600 mt-1 italic">"{{ $log->comment }}"</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() { document.getElementById('rejectModal').classList.remove('hidden'); lucide.createIcons(); }
        function hideRejectModal() { document.getElementById('rejectModal').classList.add('hidden'); }
    </script>
</x-app-layout>
