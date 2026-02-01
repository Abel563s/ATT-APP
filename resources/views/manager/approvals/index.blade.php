<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    Review Queue
                </h2>
                <p class="text-slate-500 font-medium mt-1">
                    Manage and approve weekly attendance submissions.
                </p>
            </div>

            <div
                class="flex items-center bg-blue-50 border border-blue-100 rounded-2xl px-5 py-3 shadow-sm text-blue-900">
                <div class="flex flex-col">
                    <span
                        class="text-[10px] font-bold text-blue-400 uppercase tracking-widest leading-none mb-1">Pending
                        Review</span>
                    <span class="text-xl font-black text-blue-600">{{ $pendingAttendances->count() }} Records</span>
                </div>
            </div>
        </div>

        @if(session('debug'))
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <pre class="text-xs">{{ session('debug') }}</pre>
            </div>
        @endif

        <!-- Main Content Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8">
                @if($pendingAttendances->isEmpty())
                    <div class="text-center py-20 flex flex-col items-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Queue is Empty</h3>
                        <p class="text-slate-500 max-w-xs mx-auto font-medium">Excellent work! All attendance submissions
                            have been processed.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest rounded-l-2xl">
                                        Department</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Period
                                    </th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        Submitted By</th>
                                    <th
                                        class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right rounded-r-2xl">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($pendingAttendances as $attendance)
                                    <tr class="hover:bg-blue-50/30 transition-all group">
                                        <td class="px-6 py-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 font-bold border-2 border-white shadow-sm ring-1 ring-blue-50">
                                                    {{ substr($attendance->department->name, 0, 1) }}
                                                </div>
                                                <div class="font-bold text-slate-800 tracking-tight">
                                                    {{ $attendance->department->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-slate-700 font-bold">{{ $attendance->week_start_date->format('M d, Y') }}</span>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Starting
                                                    Monday</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold border-2 border-white">
                                                    {{ substr($attendance->submitter->name, 0, 1) }}
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-slate-700 font-bold text-xs">{{ $attendance->submitter->name }}</span>
                                                    <span
                                                        class="text-[10px] font-medium text-slate-400">{{ $attendance->updated_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-6 text-right">
                                            <a href="{{ route('manager.approvals.show', $attendance->id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-[#00ADC5] border border-transparent rounded-xl text-xs font-bold text-white uppercase tracking-widest hover:bg-[#007A8A] hover:shadow-lg hover:shadow-cyan-100 transition-all active:scale-95 group-hover:-translate-x-1">
                                                Review Details
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
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
        </div>
    </div>
</x-app-layout>