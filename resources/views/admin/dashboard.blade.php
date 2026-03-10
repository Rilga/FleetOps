<x-app-layout>
    <div class="py-8 bg-[#0a0c12] min-h-screen text-slate-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-[#161922] border border-slate-800 p-5 rounded-2xl shadow-lg">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Active Fleet</p>
                    <h4 class="text-2xl font-black text-white mt-1">{{ $fleetStats['total_ships'] }} <span class="text-[10px] text-slate-600">Ships</span></h4>
                </div>
                <div class="bg-[#161922] border border-red-900/30 p-5 rounded-2xl shadow-lg">
                    <p class="text-[9px] font-black text-red-500 uppercase tracking-widest">Overdue</p>
                    <h4 class="text-2xl font-black text-white mt-1">{{ $fleetStats['overdue_tasks'] }} <span class="text-[10px] text-slate-600">Tasks</span></h4>
                </div>
                <div class="bg-[#161922] border border-yellow-900/30 p-5 rounded-2xl shadow-lg">
                    <p class="text-[9px] font-black text-yellow-500 uppercase tracking-widest">Will Due</p>
                    <h4 class="text-2xl font-black text-white mt-1">{{ $fleetStats['will_due_tasks'] }} <span class="text-[10px] text-slate-600">Units</span></h4>
                </div>
                <div class="bg-[#161922] border border-blue-900/30 p-5 rounded-2xl shadow-lg">
                    <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest">Pending Appr.</p>
                    <h4 class="text-2xl font-black text-white mt-1">{{ $fleetStats['pending_approvals'] }} <span class="text-[10px] text-slate-600">Jobs</span></h4>
                </div>
                <div class="bg-[#161922] border border-emerald-900/30 p-5 rounded-2xl shadow-lg">
                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Monthly Done</p>
                    <h4 class="text-2xl font-black text-white mt-1">{{ $completedThisMonth }} <span class="text-[10px] text-slate-600">Jobs</span></h4>
                </div>
            </div>

            <div class="bg-[#161922]/90 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl mb-10">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                    <div>
                        <h3 class="text-sm font-black text-white uppercase tracking-widest italic underline decoration-blue-600 underline-offset-8">Fleet Technical Matrix</h3>
                        <p class="text-[9px] text-slate-500 uppercase mt-2 tracking-widest font-bold">Real-time Machinery Compliance across 8 Vessels</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] border-b border-slate-800 bg-slate-900/30">
                                <th class="p-6">Vessel Identity</th>
                                <th class="p-6 text-center">Unit Count</th>
                                <th class="p-6 text-center">Overdue (Red)</th>
                                <th class="p-6 text-center">Will Due (Yel)</th>
                                <th class="p-6">Operational Condition</th>
                                <th class="p-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/50">
                            @foreach($ships as $ship)
                            @php
                                $tasks = $ship->machineries->flatMap->maintenanceTasks;
                                $crit = $tasks->where('status', 'critical')->count();
                                $warn = $tasks->where('status', 'warning')->count();
                                $health = ($crit > 0) ? 'CRITICAL' : (($warn > 0) ? 'WARNING' : 'HEALTHY');
                                $healthColor = ($crit > 0) ? 'border-red-900/50 text-red-500 bg-red-950/20' : ($warn > 0 ? 'border-yellow-900/50 text-yellow-500 bg-yellow-950/20' : 'border-emerald-900/50 text-emerald-500 bg-emerald-950/20');
                            @endphp
                            <tr class="hover:bg-slate-800/40 transition-all group">
                                <td class="p-6 border-l-4 {{ $crit > 0 ? 'border-red-600' : ($warn > 0 ? 'border-yellow-600' : 'border-emerald-600') }}">
                                    <p class="text-sm font-black text-white uppercase tracking-tight">{{ $ship->name }}</p>
                                    <p class="text-[9px] text-slate-600 font-bold uppercase tracking-widest mt-0.5">{{ $ship->call_sign ?? 'NO CALL SIGN' }}</p>
                                </td>
                                <td class="p-6 text-center">
                                    <span class="text-xs font-mono text-slate-500">{{ $ship->machineries->count() }} Units</span>
                                </td>
                                <td class="p-6 text-center font-mono text-sm {{ $crit > 0 ? 'text-red-500 font-black animate-pulse' : 'text-slate-800' }}">
                                    {{ $crit }}
                                </td>
                                <td class="p-6 text-center font-mono text-sm {{ $warn > 0 ? 'text-yellow-500 font-black' : 'text-slate-800' }}">
                                    {{ $warn }}
                                </td>
                                <td class="p-6">
                                    <span class="text-[8px] font-black border {{ $healthColor }} px-3 py-1 rounded-full tracking-widest">
                                        {{ $health }}
                                    </span>
                                </td>
                                <td class="p-6 text-right">
                                    <a href="{{ route('admin.analyze', $ship->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 border border-slate-700 rounded-xl text-[9px] font-black text-white uppercase hover:bg-blue-600 hover:border-blue-500 transition-all">
                                        Analyze Vessel <i class="fas fa-chevron-right text-[7px]"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-[#161922] border border-slate-800 p-6 rounded-3xl shadow-xl">
                    <h3 class="text-xs font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fas fa-chart-line text-blue-500"></i> Fleet Compliance Trend
                    </h3>
                    <div class="h-64 flex items-center justify-center bg-black/10 rounded-2xl p-4">
                        <canvas id="complianceChart"></canvas>
                    </div>
                </div>

                <div class="bg-[#161922] border border-slate-800 p-6 rounded-3xl shadow-xl">
                    <h3 class="text-xs font-black text-white uppercase tracking-widest mb-6 flex items-center gap-2">
                        <i class="fas fa-bell text-red-500"></i> Global Machinery Critical Alerts
                    </h3>
                    <div class="space-y-3">
                        @foreach(App\Models\MaintenanceTask::with('machinery.ship')->where('status', 'critical')->latest()->take(5)->get() as $task)
                        <div class="flex justify-between items-center p-4 bg-red-950/5 rounded-2xl border border-red-900/10 hover:border-red-900/30 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div>
                                <div>
                                    <p class="text-[8px] font-black text-red-500 uppercase tracking-tighter">{{ $task->machinery->ship->name }}</p>
                                    <p class="text-[11px] font-bold text-white uppercase leading-tight">{{ $task->machinery->name }}</p>
                                    <p class="text-[9px] text-slate-500 italic mt-0.5">{{ Str::limit($task->job_details, 40) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-mono text-slate-500 uppercase">Limit</p>
                                <p class="text-xs font-mono font-bold text-white">{{ number_format($task->next_due_rh, 0) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('complianceChart').getContext('2d');
        
        // Mengambil data dari PHP ke JavaScript
        const labels = @json($months);
        const realData = @json($complianceData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Fleet Compliance (%)',
                    data: realData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#64748b', font: { size: 10 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 10 } }
                    }
                }
            }
        });
    </script>
</x-app-layout>