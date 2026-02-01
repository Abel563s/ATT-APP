<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Modernized Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">System Alerts</h2>
                <p class="text-slate-500 font-medium lowercase tracking-tight">System synchronization and activity logs.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="px-4 py-2 bg-slate-100 rounded-xl text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Total: {{ $notifications->total() }}
                </span>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    <div
                        class="p-6 md:p-8 hover:bg-slate-50/50 transition-all group {{ $notification->read_at ? 'opacity-60' : 'bg-cyan-50/20' }}">
                        <div class="flex items-start gap-6">
                            <!-- Icon and Status -->
                            <div class="relative shrink-0">
                                <div
                                    class="w-12 h-12 rounded-2xl {{ $notification->read_at ? 'bg-slate-100 text-slate-400' : 'bg-[#00ADC5] text-white' }} flex items-center justify-center shadow-lg {{ $notification->read_at ? '' : 'shadow-cyan-100' }} transition-all">
                                    @php
                                        $type = $notification->data['type'] ?? 'default';
                                        $icon = match ($type) {
                                            'attendance_submitted' => 'send',
                                            'status_updated' => 'refresh-cw',
                                            'employee_created' => 'user-plus',
                                            default => 'bell'
                                        };
                                    @endphp
                                    <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                                </div>
                                @if(!$notification->read_at)
                                    <span
                                        class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 border-2 border-white rounded-full animate-pulse"></span>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 space-y-1">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                        {{ $notification->data['title'] ?? 'System Notification' }}
                                    </h3>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 font-medium leading-relaxed">
                                    {{ $notification->data['message'] ?? 'No message content provided.' }}
                                </p>

                                <div class="pt-4 flex items-center gap-4">
                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}"
                                            class="text-[10px] font-black text-[#00ADC5] uppercase tracking-widest hover:text-[#007A8A] transition-colors flex items-center gap-1.5">
                                            View Details
                                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </a>
                                    @endif

                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-20 text-center space-y-4">
                        <div
                            class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto text-slate-200">
                            <i data-lucide="bell-off" class="w-10 h-10"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Silence Detected</h3>
                            <p class="text-[10px] font-bold text-slate-300 uppercase">No system alerts currently in queue.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>