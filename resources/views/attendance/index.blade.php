<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Department Attendance</h2>
                <p class="text-slate-500 text-sm font-medium flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#00ADC5]"></span>
                    {{ $department->name }} Division
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 shadow-sm">
                    <span class="text-[9px] font-black text-slate-400 uppercase mr-2 tracking-widest">Status</span>
                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $attendance->status->color() }}">
                        {{ $attendance->status->label() }}
                    </span>
                </div>
                @if($attendance->rejection_reason)
                    <div class="flex items-center bg-red-50 px-3 py-2 rounded-xl text-red-700 border border-red-100">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1.5 flex-shrink-0"></i>
                        <p class="text-[9px] font-black uppercase tracking-widest">Issue: {{ $attendance->rejection_reason }}</p>
                    </div>
                @endif
                <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="calendar" class="h-4 w-4 text-slate-400 group-focus-within:text-[#00ADC5] transition-colors"></i>
                        </div>
                        <input type="date" name="week" id="week-picker" value="{{ $weekStart }}" 
                               onchange="validateMonday(this)"
                               class="block w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-slate-700 text-sm font-bold focus:ring-2 focus:ring-[#00ADC5]/20 transition-all cursor-pointer">
                    </div>
                </form>
                @if($isManagerReadOnly && $attendance->isEditable())
                    <div class="flex items-center bg-amber-50 border border-amber-200 rounded-xl px-3 py-2 shadow-sm text-amber-700">
                        <i data-lucide="lock" class="w-3 h-3 mr-1.5"></i>
                        <span class="text-[9px] font-black uppercase tracking-widest">Read-Only</span>
                    </div>
                @endif
                @if($attendance->isEditable() && !$isManagerReadOnly)
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="markAllAsPresent()" 
                                class="inline-flex items-center px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-bold text-emerald-700 shadow-sm hover:bg-emerald-100 transition-all active:scale-95">
                            <i data-lucide="check-square" class="w-3.5 h-3.5 mr-1.5"></i>
                            Mark All Present
                        </button>
                        <button form="attendance-form" type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all active:scale-95">
                            <i data-lucide="save" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                            Save Draft
                        </button>
                        <button type="button" onclick="confirmSubmit()"
                                class="inline-flex items-center px-4 py-2 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white shadow-lg shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95 uppercase tracking-widest">
                            <i data-lucide="send" class="w-3.5 h-3.5 mr-1.5"></i>
                            Submit
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="attendance-form" action="{{ route('attendance.save') }}" method="POST">
                @csrf
                <input type="hidden" name="weekly_attendance_id" value="{{ $attendance->id }}">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-center border-collapse">
                        <thead>
                            <tr class="bg-[#00ADC5] border-b border-[#00ADC5]">
                                <th class="sticky left-0 bg-[#00ADC5] z-20 px-3 py-3 min-w-[140px] text-left border-r border-white/10">
                                    <span class="text-[9px] font-black text-white uppercase tracking-widest">Team Member</span>
                                </th>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <th class="px-2 py-3 border-r border-white/10 last:border-[#00ADC5]" colspan="{{ $day === 'Saturday' ? '1' : '2' }}">
                                        <span class="text-[9px] font-black text-white uppercase tracking-widest leading-none block mb-0.5">{{ $day }}</span>
                                        <span class="text-[9px] font-bold text-white/70 uppercase">{{ $attendance->week_start_date->addDays($loop->index)->format('d M') }}</span>
                                    </th>
                                @endforeach
                            </tr>
                            <tr class="bg-[#007A8A] text-[9px] font-black uppercase tracking-widest text-white/80">
                                <th class="sticky left-0 bg-[#007A8A] z-20 border-r border-white/10 px-3 py-1.5"></th>
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                    <th class="py-1.5 px-1 border-r border-white/10">Morn</th>
                                    @if($day !== 'Sat')
                                        <th class="py-1.5 px-1 border-r border-white/10 last:border-[#007A8A]">Aftr</th>
                                    @endif
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="sticky left-0 bg-white group-hover:bg-slate-50 z-10 px-3 py-2 border-r border-slate-200 text-left shadow-[5px_0_15px_-5px_rgba(0,0,0,0.05)] transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-black text-[10px] border border-slate-200 shadow-sm uppercase shrink-0">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col min-w-0">
                                                <span class="font-bold text-slate-800 leading-none mb-0.5 text-xs truncate">{{ $employee->full_name }}</span>
                                                <span class="text-[9px] font-bold text-slate-400">{{ $employee->employee_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat'] as $day)
                                        @foreach(['m', 'a'] as $period)
                                            @if($day === 'sat' && $period === 'a')
                                                @continue
                                            @endif
                                            @php 
                                                $field = "{$day}_{$period}";
                                                $value = $entries->has($employee->id) ? $entries[$employee->id]->{$field} : null;
                                                $dbCode = $value ? ($codesMap[$value] ?? null) : null;
                                            @endphp
                                            <td class="p-1 border-r border-slate-100 last:border-slate-200">
                                                <select name="attendance[{{ $employee->id }}][{{ $field }}]" 
                                                        class="attendance-select w-10 h-10 border-2 border-transparent p-0.5 text-[9px] font-black rounded-lg focus:ring-2 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all appearance-none text-center {{ $dbCode ? "{$dbCode->bg_color} {$dbCode->text_color}" : 'bg-slate-50 text-slate-300 hover:bg-slate-100' }} cursor-pointer"
                                                        {{ (!$attendance->isEditable() || $isManagerReadOnly) ? 'disabled' : '' }}
                                                        onchange="updateCellColor(this)">
                                                    <option value="">-</option>
                                                    @foreach($attendanceValues as $val)
                                                        <option value="{{ $val->code }}" {{ $value === $val->code ? 'selected' : '' }}>
                                                            {{ $val->code }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endforeach
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <!-- Legend -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Legend</h3>
                <span class="px-2.5 py-1 bg-slate-100 rounded-full text-[9px] font-black text-slate-500 uppercase tracking-widest">Core Identifiers</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
                @foreach($attendanceValues as $val)
                    <div class="flex flex-col items-center gap-1.5 p-3 rounded-xl border border-slate-50 bg-slate-50/30 hover:bg-slate-50 transition-colors">
                        <span class="w-8 h-8 flex items-center justify-center rounded-lg text-[9px] font-black {{ $val->bg_color }} {{ $val->text_color }} shadow-sm">
                            {{ $val->code }}
                        </span>
                        <span class="text-[9px] font-black text-slate-600 uppercase tracking-tight text-center">{{ $val->label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Submit Modal -->
    <div id="submitModal" class="fixed inset-0 z-[100] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity z-[100]" aria-hidden="true" onclick="hideModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20 relative z-[110]">
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-start gap-4">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-cyan-50 sm:mx-0">
                            <i data-lucide="check-circle" class="h-6 w-6 text-[#00ADC5]"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight" id="modal-title">Finalize Submission</h3>
                            <p class="mt-2 text-xs text-slate-500 font-medium leading-relaxed">
                                Your attendance data will be saved and submitted for approval. Once submitted, the data will be locked and routed to management for review.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" onclick="saveAndSubmit()" 
                            class="flex-1 inline-flex justify-center rounded-xl bg-[#00ADC5] py-2.5 text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95">
                        Save & Submit
                    </button>
                    <button type="button" onclick="hideModal()"
                            class="flex-1 inline-flex justify-center rounded-xl bg-white border-2 border-slate-100 py-2.5 text-[10px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95">
                        Keep Editing
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCellColor(select) {
            const colors = {
                @foreach($attendanceValues as $val)
                    '{{ $val->code }}': '{{ $val->bg_color }} {{ $val->text_color }}',
                @endforeach
                '': 'bg-slate-50 text-slate-300'
            };
            select.className = 'attendance-select w-10 h-10 border-2 border-transparent p-0.5 text-[9px] font-black rounded-lg focus:ring-2 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all appearance-none text-center cursor-pointer ' + (colors[select.value] || colors['']);
        }

        function markAllAsPresent() {
            const selects = document.querySelectorAll('.attendance-select');
            selects.forEach(select => {
                if (!select.disabled) {
                    select.value = 'P';
                    updateCellColor(select);
                }
            });
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded-xl shadow-lg font-bold text-xs z-50';
            notification.textContent = '✓ All marked as Present';
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 2000);
        }

        function confirmSubmit() {
            document.getElementById('submitModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function hideModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function validateMonday(input) {
            if (!input.value) return;
            const [year, month, day] = input.value.split('-').map(Number);
            const date = new Date(year, month - 1, day);
            const dayOfWeek = date.getDay();
            if (dayOfWeek !== 1) {
                const diff = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
                date.setDate(date.getDate() + diff);
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                input.value = `${y}-${m}-${d}`;
            }
            input.form.submit();
        }

        async function saveAndSubmit() {
            const form = document.getElementById('attendance-form');
            const formData = new FormData(form);
            try {
                const saveResponse = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (saveResponse.ok) {
                    const submitForm = document.createElement('form');
                    submitForm.method = 'POST';
                    submitForm.action = '{{ route('attendance.submit', $attendance->id) }}';
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    submitForm.appendChild(csrfToken);
                    document.body.appendChild(submitForm);
                    submitForm.submit();
                } else {
                    alert('Failed to save attendance. Please try again.');
                    hideModal();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while saving. Please try again.');
                hideModal();
            }
        }
    </script>
</x-app-layout>
