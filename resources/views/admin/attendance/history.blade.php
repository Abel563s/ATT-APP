<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Attendance History</h2>
                <p class="text-slate-500 text-sm font-medium">Complete organizational attendance records</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.attendance.history.export', request()->all()) }}"
                    class="px-4 py-2 bg-emerald-500 rounded-xl text-[10px] font-black text-white hover:bg-emerald-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-100">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    Excel
                </a>
                <a href="{{ route('admin.attendance.history.export.pdf', request()->all()) }}"
                    class="p-2 bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white transition-all flex items-center justify-center shadow-lg shadow-indigo-100"
                    title="Export PDF">
                    <i data-lucide="file-down" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <form method="GET" action="{{ route('admin.attendance.history') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                    <select name="department_id"
                        class="w-full px-3 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 appearance-none cursor-pointer">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20 appearance-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full px-3 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/20">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95 shadow-lg shadow-cyan-100">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.attendance.history') }}"
                        class="px-3 py-2.5 bg-slate-100 rounded-xl text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Submitted</span>
                    <p class="text-lg font-black text-slate-900 leading-none">{{ $stats['total_submitted'] }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Awaiting Mgr</span>
                    <p class="text-lg font-black text-slate-900 leading-none">{{ $stats['pending_manager'] }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Awaiting Admin</span>
                    <p class="text-lg font-black text-slate-900 leading-none">{{ $stats['pending_admin'] }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Approved</span>
                    <p class="text-lg font-black text-slate-900 leading-none">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <!-- Records Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Attendance Records</h3>
                <span class="px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase">
                    {{ $records->total() }} Total
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/30">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Department</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Week Period</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Submitted By</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $record)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs border border-blue-100">
                                            {{ substr($record->department->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-800 text-sm">{{ $record->department->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 text-sm">{{ $record->week_start_date->format('M d, Y') }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase">Week {{ $record->week_start_date->weekOfYear }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-xs font-bold text-slate-600">{{ $record->submitter->name ?? 'Unknown' }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $record->status->color() }}">
                                        {{ $record->status->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('attendance.show', $record->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-slate-50 rounded-lg text-[10px] font-black text-slate-400 uppercase hover:bg-[#00ADC5] hover:text-white transition-all">
                                        View Details
                                    </a>
                                    <form action="{{ route('admin.attendance.destroy', $record->id) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-50 rounded-lg text-[10px] font-black text-red-500 uppercase hover:bg-red-500 hover:text-white transition-all">
                                            <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <p class="text-slate-400 font-medium italic text-sm">No attendance records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $records->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
