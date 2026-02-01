<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modernized Header Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-slate-50/50">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                        Department Attendance
                    </h2>
                    <p class="text-slate-500 font-medium flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#00ADC5]"></span>
                        {{ $department->name }} Division
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center bg-white border border-slate-200 rounded-2xl px-3 py-1.5 shadow-sm">
                        <span class="text-xs font-black text-slate-400 uppercase mr-3 tracking-widest">Status</span>
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest ring-1 ring-inset {{ $attendance->status->color() }}">
                            {{ $attendance->status->label() }}
                        </span>
                    </div>

                    @if($isManagerReadOnly && $attendance->isEditable())
                        <div class="flex items-center bg-amber-50 border border-amber-200 rounded-2xl px-3 py-1.5 shadow-sm text-amber-700">
                            <i data-lucide="lock" class="w-3 h-3 mr-2"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Read-Only Mode</span>
                        </div>
                    @endif



                @if($attendance->isEditable() && !$isManagerReadOnly)
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="markAllAsPresent()" 
                                class="inline-flex items-center px-5 py-2.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-sm font-bold text-emerald-700 shadow-sm hover:bg-emerald-100 hover:border-emerald-300 transition-all active:scale-95">
                            <i data-lucide="check-square" class="w-4 h-4 mr-2"></i>
                            Mark All Present
                        </button>
                        <button form="attendance-form" type="submit" 
                                class="inline-flex items-center px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95">
                            <i data-lucide="save" class="w-4 h-4 mr-2 text-slate-400"></i>
                            Save Draft
                        </button>
                        <button type="button" onclick="confirmSubmit()"
                                class="inline-flex items-center px-5 py-2.5 bg-[#00ADC5] rounded-2xl text-sm font-black text-white shadow-lg shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95 uppercase tracking-widest">
                            <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                            Submit All
                        </button>
                    </div>
                @endif
            </div>
            </div>

            <div class="px-8 py-4 border-t border-slate-100 flex flex-col md:flex-row gap-6 md:items-center">
                <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-3">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="calendar" class="h-4 w-4 text-slate-400 group-focus-within:text-[#00ADC5] transition-colors"></i>
                        </div>
                        <input type="date" name="week" value="{{ $weekStart }}" 
                               onchange="this.form.submit()"
                               class="block w-full pl-10 pr-4 py-2 bg-slate-50 border-none rounded-xl text-slate-700 text-sm font-bold focus:ring-2 focus:ring-[#00ADC5]/20 transition-all cursor-pointer">
                    </div>
                </form>

                @if($attendance->rejection_reason)
                    <div class="flex-1 flex items-center gap-3 bg-red-50 px-4 py-2 rounded-2xl text-red-700 border border-red-100">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
                        <p class="text-[10px] font-black uppercase tracking-widest flex-1">Issue: {{ $attendance->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modern Attendance Grid -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <form id="attendance-form" action="{{ route('attendance.save') }}" method="POST">
                @csrf
                <input type="hidden" name="weekly_attendance_id" value="{{ $attendance->id }}">
                
                <div class="overflow-x-auto relative">
                    <table class="w-full text-sm text-center border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-200">
                                <th class="sticky left-0 bg-slate-50 z-20 px-8 py-6 min-w-[240px] text-left border-r border-slate-200">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Team Member Information</span>
                                </th>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                    <th class="px-2 py-4 border-r border-slate-100 last:border-slate-200" colspan="2">
                                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest leading-none block mb-1">{{ $day }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $attendance->week_start_date->addDays($loop->index)->format('d M') }}</span>
                                    </th>
                                @endforeach
                            </tr>
                            <tr class="bg-slate-50/30 text-[9px] font-black uppercase tracking-[0.15em] text-slate-400">
                                <th class="sticky left-0 bg-slate-50/30 z-20 border-r border-slate-200 px-8 py-1"></th>
                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                    <th class="py-2 px-1 border-r border-slate-100">Morn</th>
                                    <th class="py-2 px-1 border-r border-slate-100 last:border-slate-200">Aftr</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($employees as $employee)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="sticky left-0 bg-white group-hover:bg-slate-50 z-10 px-8 py-5 border-r border-slate-200 text-left shadow-[5px_0_15px_-5px_rgba(0,0,0,0.05)] transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs border-2 border-white ring-1 ring-slate-100 overflow-hidden uppercase">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-black text-slate-800 leading-none mb-1">{{ $employee->full_name }}</span>
                                                <span class="text-[10px] font-bold text-slate-400">{{ $employee->employee_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    @foreach(['mon', 'tue', 'wed', 'thu', 'fri', 'sat'] as $day)
                                        @foreach(['m', 'a'] as $period)
                                            @php 
                                                $field = "{$day}_{$period}";
                                                $value = $entries->has($employee->id) ? $entries[$employee->id]->{$field} : null;
                                                $dbCode = $value ? ($codesMap[$value] ?? null) : null;
                                            @endphp
                                            <td class="p-1 border-r border-slate-100 last:border-slate-200">
                                                <select name="attendance[{{ $employee->id }}][{{ $field }}]" 
                                                        class="attendance-select w-11 h-11 border-2 border-transparent p-1 text-[10px] font-black rounded-xl focus:ring-4 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all appearance-none text-center {{ $dbCode ? "{$dbCode->bg_color} {$dbCode->text_color}" : 'bg-slate-50 text-slate-300 hover:bg-slate-100' }} cursor-pointer"
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

        <!-- Legend Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Attendance System Legend</h3>
                <span class="px-3 py-1 bg-slate-100 rounded-full text-[9px] font-black text-slate-500 uppercase tracking-widest">Core Identifiers</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
                @foreach($attendanceValues as $val)
                    <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border border-slate-50 bg-slate-50/30 hover:bg-slate-50 transition-colors">
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl text-[10px] font-black {{ $val->bg_color }} {{ $val->text_color }} shadow-sm">
                            {{ $val->code }}
                        </span>
                        <span class="text-[10px] font-black text-slate-600 uppercase tracking-tight text-center">{{ $val->label }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div id="submitModal" class="fixed inset-0 z-[100] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity z-[100]" aria-hidden="true" onclick="hideModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-middle bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20 relative z-[110]">
                <div class="bg-white px-8 pt-10 pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-[1.5rem] bg-cyan-50 sm:mx-0 shadow-inner">
                            <i data-lucide="check-circle" class="h-8 w-8 text-[#00ADC5]"></i>
                        </div>
                        <div class="mt-4 text-center sm:mt-0 sm:ml-8 sm:text-left">
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight" id="modal-title">Finalize Submission</h3>
                            <div class="mt-3">
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">
                                    Your attendance data will be saved and submitted for approval. Once submitted, the data will be locked and routed to management for review.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-10 py-8 sm:flex sm:flex-row-reverse gap-4">
                    <button type="button" onclick="saveAndSubmit()" 
                            class="flex-1 inline-flex justify-center rounded-[1.25rem] bg-[#00ADC5] py-4 text-xs font-black text-white uppercase tracking-widest shadow-xl shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95">
                        Save & Submit
                    </button>
                    <button type="button" onclick="hideModal()"
                            class="flex-1 inline-flex justify-center rounded-[1.25rem] bg-white border-2 border-slate-100 py-4 text-xs font-black text-slate-500 uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95">
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
            
            select.className = 'attendance-select w-11 h-11 border-2 border-transparent p-1 text-[10px] font-black rounded-xl focus:ring-4 focus:ring-[#00ADC5]/10 focus:border-[#00ADC5] transition-all appearance-none text-center cursor-pointer ' + (colors[select.value] || colors['']);
        }

        function markAllAsPresent() {
            const selects = document.querySelectorAll('.attendance-select');
            selects.forEach(select => {
                if (!select.disabled) {
                    select.value = 'P';
                    updateCellColor(select);
                }
            });
            
            // Show a brief notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg font-bold z-50 animate-fade-in';
            notification.textContent = '✓ All attendance marked as Present';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 2000);
        }

        function confirmSubmit() {
            document.getElementById('submitModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function hideModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        async function saveAndSubmit() {
            const form = document.getElementById('attendance-form');
            const formData = new FormData(form);
            
            try {
                // First, save the attendance data
                const saveResponse = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (saveResponse.ok) {
                    // Create a temporary form to submit the POST request
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
