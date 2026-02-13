<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modernized Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Access Control</h2>
                <p class="text-slate-500 font-medium">Manage corporate user identities and system permissions.</p>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 p-6 md:p-8">
            <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Search Identity</label>
                    <div class="relative group">
                        <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#00ADC5] transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or Email..."
                            class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Filter Role</label>
                    <select name="role" class="w-full py-3.5 px-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Protocol Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ request('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Department</label>
                    <select name="department_id" class="w-full py-3.5 px-4 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-700 focus:ring-4 focus:ring-[#00ADC5]/10 appearance-none cursor-pointer">
                        <option value="">All Divisions</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-3.5 bg-[#00ADC5] rounded-2xl text-[10px] font-black text-white uppercase tracking-widest shadow-lg shadow-cyan-100 hover:bg-[#007A8A] transition-all active:scale-95">
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-3.5 bg-slate-100 rounded-2xl text-slate-400 hover:text-slate-600 transition-colors flex items-center justify-center">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Grid -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identify / Identity</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Department</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Role</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs border-2 border-white ring-1 ring-slate-100 shadow-sm uppercase group-hover:bg-white transition-colors">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-2">
                                                <span class="font-black text-slate-800 leading-none">{{ $user->name }}</span>
                                                @if($user->is_active)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.5)]"></span>
                                                @else
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                                @endif
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.05em] mt-1">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    @if($user->department)
                                        <span class="text-xs font-bold text-slate-600">{{ $user->department->name }}</span>
                                    @else
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center">
                                    @php
                                        $roleColor = match($user->role) {
                                            'admin' => 'bg-emerald-50 text-emerald-600 ring-emerald-200',
                                            'manager' => 'bg-indigo-50 text-indigo-600 ring-indigo-200',
                                            default => 'bg-slate-50 text-slate-500 ring-slate-200'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest ring-1 ring-inset {{ $roleColor }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                            class="relative inline-flex h-7 w-14 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-[#00ADC5] focus:ring-offset-2 {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-lg transition-transform {{ $user->is_active ? 'translate-x-8' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="p-2 text-slate-400 hover:text-[#00ADC5] transition-colors rounded-xl hover:bg-cyan-50">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Purge this user node?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 transition-colors rounded-xl hover:bg-rose-50">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
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
                                        <i data-lucide="user-minus" class="w-12 h-12 mb-4"></i>
                                        <p class="text-xs font-black uppercase tracking-widest">No matching identity nodes found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $users->appends(request()->except('page'))->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
