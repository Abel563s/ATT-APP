<x-app-layout>
    <div class="py-6 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">System Alerts</h2>
                <p class="text-slate-500 text-xs font-medium">System synchronization and activity logs</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 bg-slate-100 rounded-lg text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Total: {{ $notifications->total() }}
                </span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    <div class="p-4 md:p-5 hover:bg-slate-50/50 transition-all {{ $notification->read_at ? 'opacity-50' : 'bg-cyan-50/20' }}">
                        <div class="flex items-start gap-4">
                            <div class="relative shrink-0">
                                <div class="w-10 h-10 rounded-xl {{ $notification->read_at ? 'bg-slate-100 text-slate-400' : 'bg-[#00ADC5] text-white' }} flex items-center justify-center shadow-sm">
                                    @php
                                        $type = $notification->data['type'] ?? 'default';
                                        $icon = match ($type) {
                                            'attendance_submitted' => 'send',
                                            'status_updated' => 'refresh-cw',
                                            'employee_created' => 'user-plus',
                                            default => 'bell'
                                        };
                                    @endphp
                                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                                </div>
                                @if(!$notification->read_at)
                                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-rose-500 border-2 border-white rounded-full animate-pulse"></span>
                                @endif
                            </div>
                            <div class="flex-1 space-y-1 min-w-0">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-1">
                                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight truncate">
                                        {{ $notification->data['title'] ?? 'System Notification' }}
                                    </h3>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic whitespace-nowrap">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                    {{ $notification->data['message'] ?? 'No message content provided.' }}
                                </p>
                                <div class="flex items-center gap-3 pt-2">
                                    @if(isset($notification->data['action_url']))
                                        <a href="{{ $notification->data['action_url'] }}" class="text-[10px] font-black text-[#00ADC5] uppercase tracking-widest hover:text-[#007A8A] transition-colors flex items-center gap-1">
                                            View Details <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                        </a>
                                    @endif
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center space-y-3">
                        <div class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center mx-auto text-slate-200">
                            <i data-lucide="bell-off" class="w-7 h-7"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Silence Detected</h3>
                            <p class="text-[10px] font-bold text-slate-300 uppercase">No system alerts currently in queue.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
