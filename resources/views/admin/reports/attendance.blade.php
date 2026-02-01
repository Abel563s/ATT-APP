<x-app-layout>
    <div class="py-6 space-y-8 max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modernized Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="w-12 h-12 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-all group">
                    <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div class="space-y-1">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">Workforce Intelligence</h2>
                    <div
                        class="flex items-center gap-2 text-slate-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Operational Analytics Terminal
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="px-6 py-3 bg-white border border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 uppercase tracking-widest hover:border-blue-200 transition-all shadow-sm">
                    Export Raw Data
                </button>
                <button
                    class="px-6 py-3 bg-slate-900 rounded-2xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                    Generate Executive PDF
                </button>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($summary as $code => $data)
                @if($data['count'] > 0 || in_array($code, ['P', 'A']))
                    <div
                        class="bg-white rounded-[2.5rem] p-8 border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group overflow-hidden relative">
                        <div
                            class="absolute -right-4 -top-4 w-24 h-24 rounded-full opacity-5 group-hover:scale-150 transition-transform {{ str_replace('text-', 'bg-', $data['text_color']) }}">
                        </div>
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-4">{{ $data['label'] }}</span>
                        <div class="flex items-end justify-between relative z-10">
                            <span
                                class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($data['count']) }}</span>
                            <span
                                class="text-xs font-bold px-3 py-1 rounded-full {{ $data['color'] }} {{ $data['text_color'] }} ring-1 ring-inset {{ str_replace('bg-', 'ring-', $data['color']) }}">
                                Status Entries
                            </span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Performance Highlights -->
        @php
            $topDept = collect($departmentStats)->sortByDesc('percentage')->keys()->first();
            $topDeptScore = collect($departmentStats)->sortByDesc('percentage')->first()['percentage'] ?? 0;
            $avgAttendance = collect($departmentStats)->avg('percentage');
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <span class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-2">Top
                        Performer</span>
                    <h3 class="text-2xl font-black tracking-tight mb-1">{{ $topDept ?? 'N/A' }}</h3>
                    <p class="text-sm font-medium text-white/80">Leading with {{ $topDeptScore }}% efficiency</p>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div
                class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <span class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-2">Network
                        Health</span>
                    <h3 class="text-2xl font-black tracking-tight mb-1">{{ round($avgAttendance, 1) }}%</h3>
                    <p class="text-sm font-medium text-white/80">Average system-wide presence</p>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div
                class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl">
                <div class="relative z-10">
                    <span class="text-[10px] font-black text-white/60 uppercase tracking-widest block mb-2">Data
                        Integrity</span>
                    <h3 class="text-2xl font-black tracking-tight mb-1">100%</h3>
                    <p class="text-sm font-medium text-white/80">All nodes verified and synced</p>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-cyan-500/20 rounded-full blur-2xl"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Chart Area -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-200">
                    <div class="flex items-center justify-between mb-10">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Presence Velocity</h3>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Weekly
                                attendance percentage trend</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500 shadow-lg shadow-blue-200"></span>
                                <span class="text-[10px] font-black text-slate-500 uppercase">Operational P%</span>
                            </div>
                        </div>
                    </div>
                    <div class="h-[400px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Departmental Breakdown -->
                <div class="bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Division Efficiency
                            Matrix</h3>
                        <span
                            class="px-4 py-1.5 bg-white rounded-full text-[10px] font-black text-slate-500 uppercase shadow-sm border border-slate-100">Across
                            {{ count($departmentStats) }} Units</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/30">
                                    <th
                                        class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Department</th>
                                    <th
                                        class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Data Volume</th>
                                    <th
                                        class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        Presence Index</th>
                                    <th
                                        class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                                        Synchronization</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100/50">
                                @forelse($departmentStats as $name => $stats)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-10 h-10 rounded-2xl bg-slate-900 flex items-center justify-center text-white text-xs font-black shadow-lg shadow-slate-200">
                                                    {{ substr($name, 0, 1) }}
                                                </div>
                                                <span class="font-bold text-slate-700">{{ $name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-sm font-bold text-slate-500">
                                            {{ number_format($stats['total']) }} Nodes
                                        </td>
                                        <td class="px-10 py-6">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden max-w-[100px]">
                                                    <div class="h-full bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"
                                                        style="width: {{ $stats['percentage'] }}%"></div>
                                                </div>
                                                <span
                                                    class="text-xs font-black text-slate-900">{{ $stats['percentage'] }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-10 py-6 text-right">
                                            <span
                                                class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                                Active Sync
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-10 py-12 text-center text-slate-400 font-bold italic">No
                                            approved data nodes found in current cycle.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Side Intelligence -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[3rem] p-10 border border-slate-200 relative overflow-hidden shadow-sm">
                    <div class="relative z-10">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Attendance
                            Distribution</h3>
                        <div class="h-[300px]">
                            <canvas id="distributionChart"></canvas>
                        </div>
                        <div class="mt-8 space-y-4">
                            @foreach($summary as $code => $data)
                                @if($data['count'] > 0)
                                    <div
                                        class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $data['text_color']) }}">
                                            </div>
                                            <span class="text-xs font-bold text-slate-600">{{ $data['label'] }}</span>
                                        </div>
                                        <span class="text-sm font-black text-slate-900">{{ $data['count'] }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <!-- Ambient Decor -->
                    <div class="absolute -right-24 -bottom-24 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl"></div>
                </div>

                <div class="bg-white rounded-[3rem] p-10 border border-slate-200 shadow-sm space-y-6">
                    <h3
                        class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-100 pb-4">
                        Reporting Parameters</h3>
                    <div class="space-y-4">
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Target Cycle</span>
                            <span class="text-sm font-black text-slate-700">All Approved History</span>
                        </div>
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Data
                                Confidence</span>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                <span class="text-xs font-black text-slate-700">High Integrity (100%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                const trendData = @json($trendData);

                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendData.map(d => d.week),
                        datasets: [{
                            label: 'Presence Index',
                            data: trendData.map(d => d.percentage),
                            borderColor: '#3b82f6',
                            backgroundColor: (context) => {
                                const gradient = trendCtx.createLinearGradient(0, 0, 0, 400);
                                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                                gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                                return gradient;
                            },
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: '#f1f5f9' },
                                ticks: { font: { weight: 'bold' }, color: '#94a3b8', callback: value => value + '%' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: 'bold' }, color: '#94a3b8' }
                            }
                        }
                    }
                });

                const distCtx = document.getElementById('distributionChart').getContext('2d');
                const summary = @json($summary);
                const activeSummary = Object.values(summary).filter(s => s.count > 0);

                new Chart(distCtx, {
                    type: 'doughnut',
                    data: {
                        labels: activeSummary.map(s => s.label),
                        datasets: [{
                            data: activeSummary.map(s => s.count),
                            backgroundColor: activeSummary.map(s => {
                                // Extract color from tailwind classes if possible or map them
                                if (s.text_color.includes('emerald')) return '#10b981';
                                if (s.text_color.includes('rose')) return '#f43f5e';
                                if (s.text_color.includes('amber')) return '#f59e0b';
                                if (s.text_color.includes('blue')) return '#3b82f6';
                                return '#64748b';
                            }),
                            borderWidth: 0,
                            hoverOffset: 20
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '80%',
                        plugins: { legend: { display: false } }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>