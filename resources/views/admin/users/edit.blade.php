<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">
                    <a href="{{ route('admin.users.index') }}" class="hover:text-[#00ADC5]">Access Control</a>
                    <span class="mx-2 text-slate-200">/</span>
                    <span class="text-slate-600 italic uppercase">Edit</span>
                </nav>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Modify Identity: {{ $user->name }}</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
                        @csrf @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Identifier</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                                <x-input-error :messages="$errors->get('name')" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Sync Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm">
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Access Protocol (Role)</label>
                                <select name="role" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" />
                            </div>
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Assigned Division Node</label>
                                <select name="department_id" class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                    <option value="">Unassigned</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $user->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('department_id')" />
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Protocol Status</label>
                                <select name="is_active" required class="w-full rounded-xl border-none bg-slate-50 p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-[#00ADC5]/10 text-sm appearance-none cursor-pointer">
                                    <option value="1" {{ $user->is_active ? 'selected' : '' }}>Operational (Active)</option>
                                    <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Decommissioned (Inactive)</option>
                                </select>
                                <x-input-error :messages="$errors->get('is_active')" />
                            </div>
                        </div>

                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 space-y-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                                    <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-black text-amber-900 uppercase tracking-widest leading-none">Access Key Override</h4>
                                    <p class="text-[9px] font-bold text-amber-600 mt-0.5 uppercase">Leave blank to maintain current credentials.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-amber-700/60 uppercase tracking-widest ml-1">New Key</label>
                                    <input type="password" name="password" placeholder="••••••••" class="w-full rounded-lg border-none bg-white p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-amber-500/10 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black text-amber-700/60 uppercase tracking-widest ml-1">Confirm New Key</label>
                                    <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full rounded-lg border-none bg-white p-2.5 font-bold text-slate-700 focus:ring-2 focus:ring-amber-500/10 text-sm">
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div class="flex items-center gap-3 pt-2 border-t border-slate-50">
                            <button type="submit" class="px-6 py-2.5 bg-[#00ADC5] rounded-xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95">
                                Re-Synchronize Profile
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-white border border-slate-100 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:bg-slate-50 transition-all">
                                Abort
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-slate-900 rounded-xl p-5 text-white relative overflow-hidden shadow-xl">
                    <div class="relative z-10 space-y-5">
                        <h3 class="text-[10px] font-black text-white/30 uppercase tracking-widest mb-4">Security Protocol</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="id-card" class="w-4 h-4 text-cyan-400"></i>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest mb-0.5 italic text-cyan-400">Identify Node</h4>
                                    <p class="text-[10px] text-white/60 font-medium leading-relaxed">Updating the primary sync email will require the user to sign in with new credentials immediately.</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                    <i data-lucide="key" class="w-4 h-4 text-[#00ADC5]"></i>
                                </div>
                                <div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest mb-0.5 italic text-[#00ADC5]">Key Override</h4>
                                    <p class="text-[10px] text-white/60 font-medium leading-relaxed">Authority-level key resets bypass current session verification but trigger a core sync security alert.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-[#00ADC5]/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
