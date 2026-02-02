<x-app-layout>
    <div class="py-6 space-y-8">
        <!-- Modernized Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Staff Registry</h2>
                <p class="text-slate-500 font-medium">Manage human assets and division assignments.</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('importModal').showModal()"
                    class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Import
                </button>
                <a href="{{ route('admin.employees.export.pdf', request()->all()) }}"
                    class="px-5 py-3.5 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-all active:scale-95 flex items-center gap-2 shadow-sm">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export PDF
                </a>
                <a href="{{ route('admin.employees.create') }}"
                    class="px-6 py-3.5 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add Employee
                </a>
            </div>
        </div>

        <!-- Smart Counter Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div
                class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center gap-5 group hover:border-[#00ADC5] transition-colors">
                <div
                    class="w-14 h-14 rounded-2xl bg-cyan-50 flex items-center justify-center text-[#00ADC5] group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-7 h-7"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Found</h4>
                    <p class="text-2xl font-black text-slate-800 counter" data-target="{{ $totalFound }}">
                        {{ $totalFound }}
                    </p>
                </div>
            </div>
            <div
                class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center gap-5 group hover:border-emerald-500 transition-colors">
                <div
                    class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="user-check" class="w-7 h-7"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active</h4>
                    <p class="text-2xl font-black text-slate-800 counter" data-target="{{ $activeCount }}">
                        {{ $activeCount }}
                    </p>
                </div>
            </div>
            <div
                class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center gap-5 group hover:border-amber-500 transition-colors">
                <div
                    class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="user-minus" class="w-7 h-7"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inactive</h4>
                    <p class="text-2xl font-black text-slate-800 counter" data-target="{{ $inactiveCount }}">
                        {{ $inactiveCount }}
                    </p>
                </div>
            </div>
            <div
                class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex items-center gap-5 group hover:border-rose-500 transition-colors">
                <div
                    class="w-14 h-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform">
                    <i data-lucide="user-x" class="w-7 h-7"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Terminated</h4>
                    <p class="text-2xl font-black text-slate-800 counter" data-target="{{ $terminatedCount }}">
                        {{ $terminatedCount }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-6 md:p-8">
            <form action="{{ route('admin.employees.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Search
                        Employee</label>
                    <div class="relative group">
                        <i data-lucide="search"
                            class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#00ADC5] transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Name, EEC-ID or Email..."
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10">
                    </div>
                </div>

                <div class="space-y-2">
                    <label
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                    <select name="department_id"
                        class="w-full py-3.5 px-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Status</label>
                    <select name="status"
                        class="w-full py-3.5 px-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                        <option value="terminated" {{ request('status') === 'terminated' ? 'selected' : '' }}>Terminated
                        </option>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 py-3.5 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-[#007A8A] transition-all active:scale-95 shadow-lg shadow-cyan-100">
                        Filter Registry
                    </button>
                    <a href="{{ route('admin.employees.index') }}"
                        class="px-5 py-3.5 bg-slate-100 rounded-2xl text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Employees Table -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Employee Name / ID</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Department & Site</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Role</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs border-2 border-white ring-1 ring-slate-100 shadow-sm uppercase group-hover:bg-white transition-colors">
                                            {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span
                                                class="font-black text-slate-800 leading-none mb-1">{{ $employee->full_name }}</span>
                                            <span
                                                class="text-[10px] font-black text-[#00ADC5] uppercase tracking-[0.1em]">{{ $employee->employee_id }}
                                                <span class="text-slate-300 mx-1">•</span> {{ $employee->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-xs font-bold text-slate-700">{{ $employee->department->name }}</span>
                                        <span
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $employee->site ?? 'No site' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">{{ ucfirst($employee->user->role) }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($employee->status === 'active')
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-emerald-50 text-emerald-600 ring-emerald-200 text-center inline-block min-w-[80px]">
                                            Operational
                                        </span>
                                    @elseif($employee->status === 'inactive')
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-amber-50 text-amber-600 ring-amber-200 text-center inline-block min-w-[80px]">
                                            Inactive
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-rose-50 text-rose-600 ring-rose-200 text-center inline-block min-w-[80px]">
                                            Terminated
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.employees.show', $employee) }}"
                                            class="p-2 text-slate-300 hover:text-[#00ADC5] transition-colors rounded-xl hover:bg-cyan-50"
                                            title="View Details">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.employees.edit', $employee) }}"
                                            class="p-2 text-slate-300 hover:text-indigo-500 transition-colors rounded-xl hover:bg-indigo-50"
                                            title="Edit Employee">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        @if($employee->status === 'active')
                                            <button
                                                onclick="openTerminateModal('{{ $employee->id }}', '{{ $employee->full_name }}')"
                                                class="p-2 text-slate-300 hover:text-rose-500 transition-colors rounded-xl hover:bg-rose-50"
                                                title="Terminate">
                                                <i data-lucide="user-x" class="w-5 h-5"></i>
                                            </button>
                                        @elseif($employee->status !== 'active')
                                            <form action="{{ route('admin.employees.activate', $employee) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-slate-300 hover:text-emerald-500 transition-colors rounded-xl hover:bg-emerald-50"
                                                    title="Reactivate">
                                                    <i data-lucide="zap" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-40">
                                        <i data-lucide="users-2" class="w-12 h-12 mb-4"></i>
                                        <p class="text-[10px] font-black uppercase tracking-widest">No employees
                                            registered in registry</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Import Modal -->
    <dialog id="importModal"
        class="modal p-0 rounded-[2.5rem] shadow-2xl border-none bg-transparent backdrop:bg-slate-900/50">
        <div class="bg-white w-[30rem] rounded-[2.5rem] overflow-hidden">
            <div class="p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Import Employees</h3>
                    <button onclick="document.getElementById('importModal').close()"
                        class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <form action="{{ route('admin.employees.import.preview') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div
                            class="border-2 border-dashed border-slate-200 rounded-3xl p-10 text-center hover:border-[#00ADC5] transition-colors group cursor-pointer relative">
                            <input type="file" name="file" class="absolute inset-0 opacity-0 cursor-pointer" required
                                onchange="this.nextElementSibling.innerHTML = this.files[0].name">
                            <div class="space-y-3 pointer-events-none">
                                <i data-lucide="file-up"
                                    class="w-10 h-10 mx-auto text-slate-300 group-hover:text-[#00ADC5] transition-colors"></i>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Click or drag
                                    Excel/CSV file here</p>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-2xl flex items-start gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5"></i>
                            <p class="text-[10px] font-bold text-blue-700 leading-relaxed uppercase tracking-tight">
                                File must contain: first_name, last_name, email, employee_id, department, position,
                                role, site.
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95">
                        Preview Import Data
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Termination Modal -->
    <dialog id="terminateModal"
        class="modal p-0 rounded-[2.5rem] shadow-2xl border-none bg-transparent backdrop:bg-slate-900/50">
        <div class="bg-white w-[30rem] rounded-[2.5rem] overflow-hidden">
            <div class="p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight text-rose-600">Terminate
                        Protocol</h3>
                    <button onclick="document.getElementById('terminateModal').close()"
                        class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100">
                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-tight">System Warning</p>
                    <p class="text-sm font-bold text-rose-700">Deactivating: <span id="terminateEmployeeName"
                            class="underline"></span></p>
                </div>

                <form id="terminateForm" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Reason
                                for Termination</label>
                            <select name="termination_reason" required
                                class="w-full py-3.5 px-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-rose-500/10">
                                <option value="Resigned">Resigned</option>
                                <option value="Contract Ended">Contract Ended</option>
                                <option value="Terminated">Terminated</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Effective
                                Date</label>
                            <input type="date" name="termination_date" required value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-rose-500/10">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-rose-500 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-rose-100 hover:bg-rose-600 transition-all active:scale-95">
                        Confirm Termination
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function openTerminateModal(id, name) {
            document.getElementById('terminateEmployeeName').innerText = name;
            document.getElementById('terminateForm').action = `/admin/employees/${id}/terminate`;
            document.getElementById('terminateModal').showModal();
        }

        // Animated Counters
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const speed = 200;
            const updateCount = () => {
                const count = +counter.innerText;
                const inc = target / speed;
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 1);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
        });
    </script>
</x-app-layout>