<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">
                    <a href="{{ route('admin.employees.index') }}" class="hover:text-[#00ADC5]">Staff Registry</a>
                    <span class="mx-2 text-slate-200">/</span>
                    <span class="text-slate-600 italic uppercase">Edit</span>
                </nav>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Modify: {{ $employee->full_name }}</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.employees.show', $employee) }}"
                    class="px-4 py-2 bg-slate-900 rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-black transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                    View
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.employees.update', $employee) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf @method('PUT')

            <div class="lg:col-span-8 space-y-6">
                <!-- Personal Data -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Identity Profile</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">Primary identification data</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('first_name')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('last_name')" />
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $employee->user->email ?? $employee->email) }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm" placeholder="john.doe@example.com">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>
                </div>

                <!-- Organizational -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Structural Assignment</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">Division allocation and system ID</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Division Node</label>
                            <select name="department_id" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Operating Site</label>
                            <input type="text" name="site" value="{{ old('site', $employee->site) }}" class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('site')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Position / Title</label>
                            <input type="text" name="position" value="{{ old('position', $employee->position) }}" class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('position')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-300 uppercase tracking-widest ml-1">System Node ID (Locked)</label>
                            <div class="w-full rounded-xl bg-slate-50 p-2.5 font-black text-slate-400 text-sm tracking-widest select-none cursor-not-allowed border border-slate-100">
                                {{ $employee->employee_id }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Access Protocol -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-500">
                            <i data-lucide="shield" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Security Synchronization</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">System access level and protocol status</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Role</label>
                            <select name="role" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                <option value="user" {{ old('role', $employee->user->role) == 'user' ? 'selected' : '' }}>User</option>
                                <option value="manager" {{ old('role', $employee->user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role', $employee->user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="department_attendance_user" {{ old('role', $employee->user->role) == 'department_attendance_user' ? 'selected' : '' }}>Division Attendance Terminal</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Status</label>
                            <select name="status" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Operational (Active)</option>
                                <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Decommissioned (Inactive)</option>
                                <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Termination Protocol</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        Update
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Abort
                    </a>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-slate-900 rounded-xl p-5 text-white relative overflow-hidden shadow-xl">
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-4">Asset Metrics</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-indigo-400">
                                    <i data-lucide="fingerprint" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest text-white/40">System UID</p>
                                    <p class="text-xs font-bold text-white">{{ $employee->employee_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-[#00ADC5]/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
