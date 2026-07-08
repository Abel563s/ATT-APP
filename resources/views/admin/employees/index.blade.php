<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Staff Registry</h2>
                <p class="text-slate-500 text-sm font-medium">Manage personnel and department assignments</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openImportModal()"
                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                    <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                    Import
                </button>
                <a href="{{ route('admin.employees.export.pdf', request()->all()) }}"
                    class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                    <i data-lucide="download" class="w-3.5 h-3.5"></i>
                    Export
                </a>
                <a href="{{ route('admin.employees.create') }}"
                    class="px-4 py-2 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                    Add Employee
                </a>
            </div>
        </div>

        <!-- Counter Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3 group hover:border-[#00ADC5] transition-colors">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total</h4>
                    <p class="text-lg font-black text-slate-800 leading-none">{{ $totalFound }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3 group hover:border-emerald-500 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i data-lucide="user-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Active</h4>
                    <p class="text-lg font-black text-slate-800 leading-none">{{ $activeCount }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3 group hover:border-amber-500 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <i data-lucide="user-minus" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Inactive</h4>
                    <p class="text-lg font-black text-slate-800 leading-none">{{ $inactiveCount }}</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3 group hover:border-rose-500 transition-colors">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <i data-lucide="user-x" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Terminated</h4>
                    <p class="text-lg font-black text-slate-800 leading-none">{{ $terminatedCount }}</p>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <form action="{{ route('admin.employees.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Search</label>
                    <div class="relative group">
                        <i data-lucide="search"
                            class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#00ADC5] transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Name, EEC-ID or Email..."
                            class="w-full pl-9 pr-3 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                    <select name="department_id"
                        class="w-full py-2.5 px-3 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
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
                        class="w-full py-2.5 px-3 bg-slate-50 border-none rounded-xl text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95 shadow-lg shadow-cyan-100">
                        Filter
                    </button>
                    <a href="{{ route('admin.employees.index') }}"
                        class="px-3 py-2.5 bg-slate-100 rounded-xl text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Employees Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                Employee
                            </th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                Department
                            </th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Role
                            </th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Status
                            </th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 font-black text-xs border border-slate-200 shadow-sm uppercase group-hover:bg-white transition-colors">
                                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-800 leading-none mb-0.5 text-sm">{{ $employee->full_name }}</span>
                                            <span class="text-[9px] font-black text-[#00ADC5] uppercase tracking-wider">{{ $employee->employee_id }}
                                                <span class="text-slate-300 mx-1">•</span> {{ $employee->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold text-slate-700">{{ $employee->department->name }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $employee->site ?? 'No site' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">{{ ucfirst($employee->user->role) }}</span>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($employee->status === 'active')
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-emerald-50 text-emerald-600 ring-emerald-200 inline-block">
                                            Active
                                        </span>
                                    @elseif($employee->status === 'inactive')
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-amber-50 text-amber-600 ring-amber-200 inline-block">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-rose-50 text-rose-600 ring-rose-200 inline-block">
                                            Terminated
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.employees.show', $employee) }}"
                                            class="p-1.5 text-slate-300 hover:text-[#00ADC5] transition-colors rounded-lg hover:bg-cyan-50"
                                            title="View Details">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.employees.edit', $employee) }}"
                                            class="p-1.5 text-slate-300 hover:text-indigo-500 transition-colors rounded-lg hover:bg-indigo-50"
                                            title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        @if($employee->status === 'active')
                                            <button
                                                onclick="openTerminateModal('{{ $employee->id }}', '{{ $employee->full_name }}')"
                                                class="p-1.5 text-slate-300 hover:text-rose-500 transition-colors rounded-lg hover:bg-rose-50"
                                                title="Terminate">
                                                <i data-lucide="user-x" class="w-4 h-4"></i>
                                            </button>
                                        @elseif($employee->status !== 'active')
                                            <form action="{{ route('admin.employees.activate', $employee) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-1.5 text-slate-300 hover:text-emerald-500 transition-colors rounded-lg hover:bg-emerald-50"
                                                    title="Reactivate">
                                                    <i data-lucide="zap" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <i data-lucide="users-2" class="w-10 h-10 mb-3"></i>
                                        <p class="text-[10px] font-black uppercase tracking-widest">No employees found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $employees->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closeImportModal()"
                aria-hidden="true"></div>
            <div
                class="relative inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-xl bg-cyan-50 text-[#00ADC5] flex items-center justify-center">
                            <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                        </div>
                        <button onclick="closeImportModal()"
                            class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-1">Import Personnel</h3>
                    <p class="text-slate-500 text-sm font-medium mb-6">Upload a structured data file to sync the staff registry.</p>

                    <form action="{{ route('admin.employees.import.preview') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div
                            class="border-2 border-dashed border-slate-100 rounded-2xl p-8 text-center hover:border-[#00ADC5] transition-all group cursor-pointer relative bg-slate-50/50">
                            <input type="file" name="file" class="absolute inset-0 opacity-0 cursor-pointer"
                                required
                                onchange="this.nextElementSibling.querySelector('.file-name').innerHTML = this.files[0].name">
                            <div class="space-y-2 pointer-events-none text-center flex flex-col items-center">
                                <i data-lucide="file-spread-sheet"
                                    class="w-8 h-8 text-slate-300 group-hover:text-[#00ADC5] transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest file-name">
                                    Drop Excel or CSV File</p>
                            </div>
                        </div>
                        <div
                            class="bg-[#00ADC5]/5 p-4 rounded-xl flex items-start gap-2.5 border border-[#00ADC5]/10">
                            <i data-lucide="info" class="w-4 h-4 text-[#00ADC5] mt-0.5 shrink-0"></i>
                            <p
                                class="text-[10px] font-bold text-[#00ADC5] leading-relaxed uppercase tracking-tight">
                                Required Format: first_name, last_name, email, employee_id, department, position, role, site.
                            </p>
                        </div>

                        <button type="submit"
                            class="w-full py-3.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-[0.98]">
                            Analyze Data Structure
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Termination Modal -->
    <div id="terminateModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity"
                onclick="closeTerminateModal()" aria-hidden="true"></div>
            <div
                class="relative inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-white/20">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center">
                            <i data-lucide="power" class="w-6 h-6"></i>
                        </div>
                        <button onclick="closeTerminateModal()"
                            class="p-1.5 text-slate-400 hover:text-slate-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-2">Terminate Protocol</h3>
                    <p class="text-slate-500 text-sm font-medium mb-1">Deactivating: <span id="terminateEmployeeName"
                            class="text-rose-600 font-black"></span></p>
                    <p class="text-slate-400 text-[10px] font-bold mb-6 uppercase tracking-widest">Archive this personnel node from the system registry.</p>

                    <form id="terminateForm" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Reason</label>
                                <select name="termination_reason" required
                                    class="w-full py-3 px-4 bg-slate-50 border-2 border-slate-50 rounded-xl text-sm font-bold text-slate-700 focus:border-rose-400 focus:ring-2 focus:ring-rose-400/10 transition-all outline-none">
                                    <option value="Resigned">Resigned</option>
                                    <option value="Contract Ended">Contract Ended</option>
                                    <option value="Dismissed">Dismissed</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Effective Date</label>
                                <input type="date" name="termination_date" required value="{{ date('Y-m-d') }}"
                                    class="w-full py-3 px-4 bg-slate-50 border-2 border-slate-50 rounded-xl text-sm font-bold text-slate-700 focus:border-rose-400 focus:ring-2 focus:ring-rose-400/10 transition-all outline-none">
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full py-3.5 bg-rose-500 rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-xl shadow-rose-100 hover:bg-rose-600 transition-all active:scale-[0.98]">
                            Confirm Termination
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }
        function openTerminateModal(id, name) {
            document.getElementById('terminateEmployeeName').innerText = name;
            document.getElementById('terminateForm').action = `/admin/employees/${id}/terminate`;
            document.getElementById('terminateModal').classList.remove('hidden');
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        function closeTerminateModal() {
            document.getElementById('terminateModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
