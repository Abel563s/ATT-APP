<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Review Queue</h2>
                <p class="text-slate-500 text-sm font-medium">Approve or reject weekly attendance submissions</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
                    <div class="w-6 h-6 rounded-md bg-blue-50 flex items-center justify-center text-blue-600">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Queue</span>
                        <span class="text-xs font-black text-slate-700">{{ $pendingAttendances->count() }}</span>
                    </div>
                </div>
                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <div class="bg-white border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
                    <div class="w-6 h-6 rounded-md bg-amber-50 flex items-center justify-center text-amber-600">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Awaiting Mgr</span>
                        <span class="text-xs font-black text-slate-700">{{ $awaitingManagerCount }}</span>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 rounded-xl px-3 py-2 flex items-center gap-2 shadow-sm">
                    <div class="w-6 h-6 rounded-md bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Awaiting Admin</span>
                        <span class="text-xs font-black text-slate-700">{{ $awaitingAdminCount }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <form action="{{ route('manager.approvals.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Status</label>
                    <select name="status"
                        class="w-full py-2.5 px-3 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Manager</option>
                        <option value="pending_admin" {{ request('status') == 'pending_admin' ? 'selected' : '' }}>Pending Admin</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                    <select name="department_id"
                        class="w-full py-2.5 px-3 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Divisions</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="flex gap-2 {{ !(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) ? 'md:col-span-3' : '' }}">
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95 shadow-lg shadow-cyan-100">
                        Apply Filters
                    </button>
                    <a href="{{ route('manager.approvals.index') }}"
                        class="px-3 py-2.5 bg-slate-100 rounded-xl text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6">
                @if($pendingAttendances->isEmpty())
                    <div class="text-center py-12 flex flex-col items-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Queue is Empty</h3>
                        <p class="text-slate-500 text-sm font-medium">All attendance submissions have been processed.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-200">
                                    <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Department</th>
                                    <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Period</th>
                                    <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Submitted By</th>
                                    <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($pendingAttendances as $attendance)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs border border-blue-100">
                                                    {{ substr($attendance->department->name, 0, 1) }}
                                                </div>
                                                <span class="font-bold text-slate-800 text-sm">{{ $attendance->department->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <div class="flex flex-col">
                                                <span class="text-slate-700 font-bold text-sm">{{ $attendance->week_start_date->format('M d, Y') }}</span>
                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Starting Monday</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="text-xs font-bold text-slate-700">{{ $attendance->submitter->name }}</span>
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $attendance->status->color() }}">
                                                {{ $attendance->status->label() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <a href="{{ route('manager.approvals.show', $attendance->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-[#00ADC5] border border-transparent rounded-lg text-[10px] font-bold text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95">
                                                Review
                                                <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($pendingAttendances->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $pendingAttendances->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
