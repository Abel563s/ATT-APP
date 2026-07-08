<x-app-layout>
    <div class="py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Import Preview</h2>
                <p class="text-slate-500 text-xs font-medium">Review the data before finalizing the import.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-slate-100 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                    Cancel
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Name</th>
                            <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">EEC-ID</th>
                            <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Department</th>
                            <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-2.5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($rows as $row)
                            <tr class="{{ $row['is_valid'] ? 'hover:bg-slate-50/50' : 'bg-rose-50/30' }} transition-colors">
                                <td class="px-6 py-2.5">
                                    <div class="font-bold text-slate-800 text-sm">{{ ($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '') }}</div>
                                    <div class="text-[9px] text-slate-400">{{ $row['email'] ?? 'No Email' }}</div>
                                </td>
                                <td class="px-6 py-2.5 font-mono text-xs uppercase">{{ $row['employee_id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-2.5 text-xs font-medium">{{ $row['department'] ?? 'N/A' }}</td>
                                <td class="px-6 py-2.5">
                                    @if($row['is_valid'])
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-lg uppercase tracking-tight">Ready</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[9px] font-bold rounded-lg uppercase tracking-tight">Invalid</span>
                                    @endif
                                </td>
                                <td class="px-6 py-2.5 text-[10px] font-medium">
                                    @if(!$row['is_valid'])
                                        <ul class="list-disc list-inside text-rose-500">
                                            @foreach($row['errors'] as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-emerald-500">Looks good</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end p-5 bg-slate-50 border-t border-slate-200 rounded-xl">
            <form action="{{ route('admin.employees.import.process') }}" method="POST">
                @csrf
                <input type="hidden" name="path" value="{{ $path }}">
                <button type="submit" @if($rows->where('is_valid', false)->count() > 0) disabled @endif
                    class="px-6 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    Finalize Import ({{ $rows->where('is_valid', true)->count() }} valid rows)
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
