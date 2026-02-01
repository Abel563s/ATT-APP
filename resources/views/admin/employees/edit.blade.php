<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2">
                    <a href="{{ route('admin.employees.index') }}" class="hover:text-[#00ADC5]">Staff Registry</a>
                    <span class="mx-3 text-slate-200">/</span>
                    <span class="text-slate-600 italic">Configuration Override</span>
                </nav>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Modify Asset: {{ $employee->full_name }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.employees.show', $employee) }}"
                    class="px-6 py-3.5 bg-slate-900 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-black transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    View details
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.employees.update', $employee) }}"
            class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            @csrf
            @method('PUT')

            <div class="lg:col-span-8 space-y-8">
                <!-- Section 1: Personal Data -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8 space-y-8">
                    <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Identity Profile
                            </h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Primary personnel
                                identification data</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">First
                                Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $employee->first_name) }}"
                                required
                                class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('first_name')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Last
                                Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $employee->last_name) }}"
                                required
                                class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('last_name')" />
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Secure
                                Email</label>
                            <input type="email" name="email" value="{{ old('email', $employee->email) }}" required
                                class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm">
                            <x-input-error :messages="$errors->get('email')" />
                        </div>
                    </div>
                </div>

                <!-- Section 2: Organizational Node -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8 space-y-8">
                    <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                            <i data-lucide="briefcase" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Structural
                                Assignment</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Division allocation
                                and system ID</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Division
                                Node</label>
                            <select name="department_id" required
                                class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" />
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 text-slate-300">System
                                Node ID (Locked)</label>
                            <div
                                class="w-full rounded-2xl bg-slate-50 p-4 font-black text-slate-400 text-sm tracking-widest select-none cursor-not-allowed">
                                {{ $employee->employee_id }}
                            </div>
                            <p class="text-[9px] font-bold text-slate-300 mt-1 uppercase">Immutable EEC identifier</p>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Access Protocol -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-8 space-y-8">
                    <div class="flex items-center gap-4 border-b border-slate-50 pb-6">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500">
                            <i data-lucide="shield" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Security
                                Synchronization</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">System access level
                                and protocol status</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol
                                Role</label>
                            <select name="role" required
                                class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                <option value="user" {{ old('role', $employee->user->role) == 'user' ? 'selected' : '' }}>
                                    Standard Asset (User)</option>
                                <option value="manager" {{ old('role', $employee->user->role) == 'manager' ? 'selected' : '' }}>Division Manager</option>
                                <option value="admin" {{ old('role', $employee->user->role) == 'admin' ? 'selected' : '' }}>Authority Overlord</option>
                                <option value="department_attendance_user" {{ old('role', $employee->user->role) == 'department_attendance_user' ? 'selected' : '' }}>Division
                                    Attendance Terminal</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol
                                Status</label>
                            <select name="is_active" required
                                class="w-full rounded-2xl border-none bg-slate-50 p-4 font-bold {{ $employee->is_active ? 'text-emerald-600' : 'text-rose-600' }} focus:ring-4 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                <option value="1" {{ old('is_active', $employee->is_active) ? 'selected' : '' }}>🟢
                                    Operational (Active)</option>
                                <option value="0" {{ old('is_active', $employee->is_active) ? '' : 'selected' }}>🔴
                                    Decommissioned (Inactive)</option>
                            </select>
                            <x-input-error :messages="$errors->get('is_active')" />
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit"
                        class="px-10 py-4 bg-[#00ADC5] rounded-2xl text-xs font-black text-white uppercase tracking-[0.2em] shadow-xl shadow-cyan-200 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-3">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Update Configuration
                    </button>
                    <a href="{{ route('admin.employees.index') }}"
                        class="px-8 py-4 bg-white border-2 border-slate-100 rounded-2xl text-xs font-black text-slate-400 uppercase tracking-[0.2em] hover:bg-slate-50 transition-all">
                        Abort
                    </a>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                <!-- Status Card -->
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                    <div class="relative z-10">
                        <h3 class="text-xs font-black text-white/30 uppercase tracking-[0.2em] mb-8">Asset Metrics</h3>
                        <div class="space-y-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-indigo-400">
                                    <i data-lucide="fingerprint" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-white/40">System UID
                                    </p>
                                    <p class="text-xs font-bold text-white">{{ $employee->employee_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-[#00ADC5]/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>