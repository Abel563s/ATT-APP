<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">
                    <a href="{{ route('admin.employees.index') }}" class="hover:text-[#00ADC5]">Staff Registry</a>
                    <span class="mx-2 text-slate-200">/</span>
                    <span class="text-slate-600 italic uppercase">New Employee</span>
                </nav>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Register New Employee</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.employees.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            @csrf

            <div class="lg:col-span-8 space-y-6">
                <!-- Personal Data -->
                <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-5">
                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase tracking-widest text-xs">Identity Profile</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase">Primary personnel identification data</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm" placeholder="John">
                            <x-input-error :messages="$errors->get('first_name')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm" placeholder="Doe">
                            <x-input-error :messages="$errors->get('last_name')" />
                        </div>
                        <div class="space-y-1 md:col-span-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm" placeholder="john.doe@example.com">
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
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                            <select name="department_id" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                <option value="">Select Division</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">System Node ID</label>
                            <div class="w-full rounded-xl bg-slate-100 p-2.5 font-black text-slate-400 text-sm italic select-none border border-slate-200">
                                AUTO-GENERATED (EEC-XXXXX)
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
                            <p class="text-[9px] font-bold text-slate-400 uppercase">System access level and initial credentials</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Role</label>
                            <select name="role" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" />
                        </div>
                        <div class="flex items-end pb-1">
                            <p class="text-[9px] font-bold text-slate-400 italic leading-tight uppercase tracking-tight">
                                NOTE: New nodes initialized in <span class="text-rose-500 font-black">DEACTIVATED</span> state. Manual clearance required.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95 flex items-center gap-2">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        Register
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="px-5 py-2.5 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition-all">
                        Abort
                    </a>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-slate-900 rounded-xl p-5 text-white relative overflow-hidden shadow-xl">
                    <div class="relative z-10 space-y-5">
                        <div>
                            <h3 class="text-[10px] font-black text-cyan-400 uppercase tracking-widest mb-2">Registration Protocol</h3>
                            <p class="text-xs text-white/60 font-medium leading-relaxed">
                                Executing this registration initializes both a <span class="text-white font-bold italic">Staff Registry Node</span> and a <span class="text-white font-bold italic">System Access Identity</span>.
                            </p>
                        </div>
                        <div class="space-y-3">
                            <div class="flex gap-3">
                                <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="lock" class="w-3.5 h-3.5 text-cyan-400"></i>
                                </div>
                                <p class="text-[10px] text-white/50 leading-relaxed font-medium uppercase tracking-tight">
                                    Initial state: <span class="text-rose-400 font-bold">DEACTIVATED</span>.</p>
                            </div>
                            <div class="flex gap-3">
                                <div class="w-7 h-7 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="fingerprint" class="w-3.5 h-3.5 text-cyan-400"></i>
                                </div>
                                <p class="text-[10px] text-white/50 leading-relaxed font-medium uppercase tracking-tight">
                                    ID Format: <span class="text-white font-bold tracking-widest">EEC-00000</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-[#00ADC5]/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
