<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modernized Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Staff Registry</h2>
                <p class="text-slate-500 font-medium">Manage human assets and division assignments.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.employees.create') }}"
                    class="px-6 py-3.5 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Add Employee
                </a>
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
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol
                        Status</label>
                    <select name="is_active"
                        class="w-full py-3.5 px-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All States</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Operational</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Decommissioned</option>
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
                                Department</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Security Level</th>
                            <th
                                class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                                Protocol Status</th>
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
                                        <span class="text-xs font-bold text-slate-700">{{ $employee->department->name }}
                                            Department</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span
                                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">{{ $employee->user->role }}</span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @if($employee->is_active)
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-emerald-50 text-emerald-600 ring-emerald-200">
                                            Operational
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset bg-rose-50 text-rose-600 ring-rose-200 shadow-sm shadow-rose-100">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.employees.show', $employee) }}"
                                            class="p-2 text-slate-300 hover:text-[#00ADC5] transition-colors rounded-xl hover:bg-cyan-50">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <a href="{{ route('admin.employees.edit', $employee) }}"
                                            class="p-2 text-slate-300 hover:text-indigo-500 transition-colors rounded-xl hover:bg-indigo-50">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        @if($employee->is_active)
                                            <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST"
                                                onsubmit="return confirm('Decommission this asset node?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-300 hover:text-rose-500 transition-colors rounded-xl hover:bg-rose-50">
                                                    <i data-lucide="power" class="w-5 h-5"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.employees.activate', $employee) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 text-slate-300 hover:text-emerald-500 transition-colors rounded-xl hover:bg-emerald-50">
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

        <!-- Quick Integration Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i data-lucide="user-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Employees</h4>
                    <p class="text-xl font-black text-slate-800">{{ $employees->where('is_active', true)->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <i data-lucide="user-minus" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inactive Employees</h4>
                    <p class="text-xl font-black text-slate-800">{{ $employees->where('is_active', false)->count() }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Admin Power</h4>
                    <p class="text-xl font-black text-slate-800">{{ $employees->where('user.role', 'admin')->count() }}
                    </p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <i data-lucide="git-branch" class="w-6 h-6"></i>
                </div>
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Departments</h4>
                    <p class="text-xl font-black text-slate-800">{{ $departments->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>