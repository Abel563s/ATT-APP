<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header with Back Button -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}"
                class="w-12 h-12 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Attendance History</h2>
                <p class="text-slate-500 font-medium">Complete organizational attendance records</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
            <form method="GET" action="{{ route('admin.attendance.history') }}"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Department</label>
                    <select name="department_id"
                        class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#00ADC5]/20">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#00ADC5]/20">
                        <option value="">All Statuses</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">From
                        Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full px-4 py-2 bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-2 focus:ring-[#00ADC5]/20">
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit"
                        class="flex-1 px-6 py-2 bg-[#00ADC5] rounded-xl text-sm font-black text-white hover:bg-[#007A8A] transition-all shadow-lg shadow-cyan-100">
                        Apply Filters
                    </button>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.attendance.history.export', request()->all()) }}"
                            class="px-5 py-2 bg-emerald-500 rounded-xl text-xs font-black text-white hover:bg-emerald-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-100"
                            title="Export Excel">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                            Excel
                        </a>
                        <a href="{{ route('admin.attendance.history.export.pdf', request()->all()) }}"
                            class="px-5 py-2 bg-rose-500 rounded-xl text-xs font-black text-white hover:bg-rose-600 transition-all flex items-center justify-center gap-2 shadow-lg shadow-rose-100 border border-rose-600/10"
                            title="Export PDF">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Records Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Attendance Records</h3>
                <span class="px-4 py-1.5 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase">
                    {{ $records->total() }} Total Records
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/30">
                        <tr>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Department</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Week
                                Period</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Submitted By</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status
                            </th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Last
                                Updated</th>
                            <th
                                class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($records as $record)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 font-bold border border-blue-100">
                                            {{ substr($record->department->name ?? '?', 0, 1) }}
                                        </div>
                                        <span
                                            class="font-bold text-slate-800">{{ $record->department->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-700">{{ $record->week_start_date->format('M d, Y') }}</span>
                                        <span class="text-[10px] text-slate-400 uppercase">Week
                                            {{ $record->week_start_date->weekOfYear }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="text-xs font-bold text-slate-600">{{ $record->submitter->name ?? 'Unknown' }}</span>
                                </td>
                                <td class="px-8 py-5">
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-black uppercase ring-1 ring-inset {{ $record->status->color() }}">
                                        {{ $record->status->label() }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-xs text-slate-500">{{ $record->updated_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('attendance.show', $record->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-slate-50 rounded-xl text-[10px] font-black text-slate-400 uppercase hover:bg-[#00ADC5] hover:text-white transition-all">
                                            View Details →
                                        </a>
                                        <form action="{{ route('admin.attendance.destroy', $record->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-red-50 rounded-xl text-[10px] font-black text-red-500 uppercase hover:bg-red-500 hover:text-white transition-all">
                                                <i data-lucide="trash-2" class="w-3 h-3 mr-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-12 text-center">
                                    <p class="text-slate-400 font-medium italic">No attendance records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
                <div class="px-8 py-6 border-t border-slate-100">
                    {{ $records->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>