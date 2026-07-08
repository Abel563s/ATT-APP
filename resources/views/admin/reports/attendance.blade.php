<x-app-layout>
    <div class="py-6 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Workforce Intelligence</h2>
                <p class="text-slate-500 text-sm font-medium">Attendance analytics and performance metrics</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-600 uppercase tracking-widest hover:bg-slate-50 transition-all shadow-sm">
                    Export Data
                </button>
                <button class="px-4 py-2 bg-slate-900 rounded-xl text-[10px] font-black text-white uppercase tracking-widest hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                    Generate PDF
                </button>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($summary as $code => $data)
                @if($data['count'] > 0 || in_array($code, ['P', 'A']))
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">{{ $data['label'] }}</span>
                        <div class="flex items-end justify-between">
                            <span class="text-2xl font-black text-slate-900 tracking-tighter">{{ number_format($data['count']) }}</span>
                            <span class="text-[9px] font-black px-2 py-1 rounded-md {{ $data['color'] }} {{ $data['text_color'] }}">
                                Entries
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-5 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <span class="text-[9px] font-black text-white/60 uppercase tracking-widest block mb-1">Top Performer</span>
                    <h3 class="text-xl font-black tracking-tight">{{ $topDept ?? 'N/A' }}</h3>
                    <p class="text-xs font-medium text-white/80">{{ $topDeptScore }}% efficiency</p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div class="bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl p-5 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <span class="text-[9px] font-black text-white/60 uppercase tracking-widest block mb-1">Workforce Sync</span>
                    <h3 class="text-xl font-black tracking-tight">{{ $intelligence['active_workers'] }} / {{ $intelligence['total_workers'] }}</h3>
                    <p class="text-xs font-medium text-white/80">Active Personnel</p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl p-5 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <span class="text-[9px] font-black text-white/60 uppercase tracking-widest block mb-1">Compliance</span>
                    <h3 class="text-xl font-black tracking-tight">{{ $intelligence['compliance_rate'] }}%</h3>
                    <p class="text-xs font-medium text-white/80">Approval Rate</p>
                </div>
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-cyan-500/20 rounded-full blur-2xl"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Chart -->
            <div class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Presence Velocity</h3>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Weekly attendance trend</p>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-[300px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Side Distribution -->
            <div class="lg:col-span-4 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900">Distribution</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Attendance breakdown</p>
                </div>
                <div class="p-6">
                    <div class="h-[200px]">
                        <canvas id="distributionChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-2">
                        @foreach($summary as $code => $data)
                            @if($data['count'] > 0)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $data['text_color']) }}"></div>
                                        <span class="text-xs font-bold text-slate-600">{{ $data['label'] }}</span>
                                    </div>
                                    <span class="text-sm font-black text-slate-900">{{ $data['count'] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Division Efficiency Matrix</h3>
                <span class="px-3 py-1 bg-white rounded-full text-[10px] font-black text-slate-500 uppercase border border-slate-100">Across {{ count($departmentStats) }} Units</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Department</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Data Volume</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest">Presence Index</th>
                            <th class="px-6 py-3 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/50">
                        @forelse($departmentStats as $name => $stats)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white text-xs font-black shadow-sm">
                                            {{ substr($name, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-700 text-sm">{{ $name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-sm font-bold text-slate-500">
                                    {{ number_format($stats['total']) }} EMP
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden max-w-[120px]">
                                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $stats['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-xs font-black text-slate-900">{{ $stats['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                        Active Sync
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 font-bold italic text-sm">No approved data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                                const gradient = trendCtx.createLinearGradient(0, 0, 0, 300);
                                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
                                gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                                return gradient;
                            },
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
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
                                ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8', callback: value => value + '%' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }
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
                                if (s.text_color.includes('emerald')) return '#10b981';
                                if (s.text_color.includes('rose')) return '#f43f5e';
                                if (s.text_color.includes('amber')) return '#f59e0b';
                                if (s.text_color.includes('blue')) return '#3b82f6';
                                return '#64748b';
                            }),
                            borderWidth: 0,
                            hoverOffset: 15
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: { legend: { display: false } }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
