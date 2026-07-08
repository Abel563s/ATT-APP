<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Profile Settings</h2>
                <p class="text-slate-500 text-sm font-medium">Manage your account information and preferences</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Form Side -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Profile Information -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center text-[#00ADC5]">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Profile Details</h3>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Identification Protocol</p>
                        </div>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- Update Password -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900">Access Key</h3>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Security Credentials</p>
                        </div>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>

                <!-- Danger Zone -->
                @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <div class="bg-rose-50/30 rounded-xl shadow-sm border border-rose-100 p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-rose-500 border border-rose-50">
                            <i data-lucide="shield-alert" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-rose-900">Purge Profile</h3>
                            <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest">Account Termination</p>
                        </div>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
                @endif
            </div>

            <!-- Meta Side -->
            <div class="lg:col-span-4">
                <div class="bg-slate-900 rounded-xl p-6 text-white relative overflow-hidden shadow-xl">
                    <div class="relative z-10 space-y-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-cyan-400 shadow-lg">
                            <span class="text-2xl font-black text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-black tracking-tight">{{ Auth::user()->name }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <p class="text-[9px] font-black text-white/40 uppercase tracking-widest">Verified Node</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-white/5">
                            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                                <span class="text-[9px] font-black text-white/30 uppercase tracking-widest block mb-1">Last Sync</span>
                                <span class="text-xs font-bold text-white">{{ Auth::user()->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="p-3 bg-white/5 rounded-xl border border-white/5">
                                <span class="text-[9px] font-black text-white/30 uppercase tracking-widest block mb-1">Node ID</span>
                                <span class="text-xs font-bold text-white/60">#{{ Auth::user()->id }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-[#00ADC5]/10 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
