<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modern Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Security & Identity</h2>
                <p class="text-slate-500 font-medium">Manage your personal credentials and system access.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Form Side -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Profile Information -->
                <div
                    class="bg-white rounded-[2.5rem] p-10 md:p-12 border border-slate-200 shadow-sm relative overflow-hidden transition-all hover:shadow-xl hover:shadow-slate-200/50">
                    <div class="max-w-xl relative z-10">
                        <div class="flex items-center gap-4 mb-10">
                            <div
                                class="w-14 h-14 rounded-2xl bg-cyan-50 flex items-center justify-center text-[#00ADC5] border-2 border-white shadow-sm ring-1 ring-cyan-100">
                                <i data-lucide="user" class="w-7 h-7"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 leading-none">Profile Details</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5">
                                    Identification Protocol</p>
                            </div>
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Update Password -->
                <div
                    class="bg-white rounded-[2.5rem] p-10 md:p-12 border border-slate-200 shadow-sm relative overflow-hidden transition-all hover:shadow-xl hover:shadow-slate-200/50">
                    <div class="max-w-xl relative z-10">
                        <div class="flex items-center gap-4 mb-10">
                            <div
                                class="w-14 h-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 border-2 border-white shadow-sm ring-1 ring-amber-100">
                                <i data-lucide="key-round" class="w-7 h-7"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 leading-none">Access Key</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1.5">
                                    Security Credentials</p>
                            </div>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Danger Zone -->
                @if(Auth::user()->isAdmin())
                    <div
                        class="bg-rose-50/30 rounded-[2.5rem] p-10 md:p-12 border border-rose-100 shadow-sm relative overflow-hidden group">
                        <div class="max-w-xl relative z-10">
                            <div class="flex items-center gap-4 mb-10">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-rose-500 border-2 border-rose-50 shadow-sm transition-transform group-hover:scale-105">
                                    <i data-lucide="shield-alert" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-rose-900 leading-none">Purge Profile</h3>
                                    <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mt-1.5">Account
                                        Termination Terminal</p>
                                </div>
                            </div>
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                @endif
            </div>

            <!-- Meta Side -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Info Card -->
                <div
                    class="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl ring-1 ring-white/10">
                    <div class="relative z-10">
                        <div class="w-20 h-20 rounded-3xl bg-white/20 p-0.5 mb-8 shadow-xl shadow-cyan-900/10">
                            <div
                                class="w-full h-full rounded-[22px] bg-white/10 flex items-center justify-center border-4 border-white/20">
                                <span
                                    class="text-3xl font-black text-white italic">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <h4 class="text-2xl font-black tracking-tight mb-2">{{ Auth::user()->name }}</h4>
                        <div class="flex items-center gap-2 mb-10">
                            <span
                                class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shadow-lg shadow-emerald-400/50"></span>
                            <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Verified Network
                                Node</p>
                        </div>

                        <div class="space-y-6">
                            <div
                                class="p-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                                <span
                                    class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2">Protocol
                                    Sync</span>
                                <span
                                    class="text-sm font-bold text-white">{{ Auth::user()->updated_at->diffForHumans() }}</span>
                            </div>
                            <div
                                class="p-6 bg-white/5 rounded-2xl border border-white/10 hover:bg-white/10 transition-colors">
                                <span
                                    class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2">Node
                                    Registry</span>
                                <span class="text-sm font-bold text-white opacity-60">ID#{{ Auth::user()->id }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Decor -->
                    <div class="absolute -right-24 -top-24 w-64 h-64 bg-[#00ADC5]/10 rounded-full blur-3xl"></div>
                    <div class="absolute -left-24 -bottom-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>