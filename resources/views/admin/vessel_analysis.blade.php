<x-app-layout>
    <div class="py-8 bg-[#0a0c12] min-h-screen text-slate-300 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="...">
                <i class="fas fa-chevron-left mb-5"></i> Back to Fleet Overview
            </a>
            <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center bg-[#161922] p-8 rounded-3xl border border-slate-800 shadow-2xl">
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 bg-blue-600/10 border border-blue-500/20 rounded-2xl flex items-center justify-center text-blue-500 text-3xl">
                        <i class="fas fa-ship"></i>
                    </div>
                    <div>
                        <h2 class="text-4xl font-black text-white uppercase tracking-tighter">{{ $ship->name }}</h2>
                        <div class="flex gap-4 mt-2">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><i class="fas fa-fingerprint mr-1"></i> IMO: {{ $ship->imo_number ?? '9876543' }}</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest"><i class="fas fa-flag mr-1"></i> Flag: Indonesia</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-[#161922] border border-slate-800 p-6 rounded-3xl relative overflow-hidden">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Fleet Compliance</p>
                    <h4 class="text-4xl font-black {{ $complianceRate < 80 ? 'text-red-500' : 'text-emerald-500' }}">{{ $complianceRate }}%</h4>
                    <div class="w-full bg-slate-800 h-1.5 rounded-full mt-4">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $complianceRate }}%"></div>
                    </div>
                </div>
                <div class="bg-[#161922] border border-slate-800 p-6 rounded-3xl">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Total Machinery Units</p>
                    <h4 class="text-4xl font-black text-white">{{ $ship->machineries->count() }}</h4>
                    <p class="text-[9px] text-slate-600 font-bold uppercase mt-2">Active & Monitored</p>
                </div>
                <div class="bg-[#161922] border border-slate-800 p-6 rounded-3xl">
                    <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-2">Critical Exceptions</p>
                    <h4 class="text-4xl font-black text-white">{{ $overdueTasks }}</h4>
                    <p class="text-[9px] text-red-900 font-black uppercase mt-2 italic">Immediate Action Required</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-[#161922] border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
                        <div class="p-6 border-b border-slate-800 bg-slate-900/50">
                            <h3 class="text-xs font-black text-white uppercase tracking-widest">Machinery Condition Matrix</h3>
                        </div>
                        <table class="w-full text-left">
                            <thead class="bg-slate-900/30 text-[9px] font-black text-slate-600 uppercase">
                                <tr>
                                    <th class="p-5">Unit Name</th>
                                    <th class="p-5 text-center">Last Service</th>
                                    <th class="p-5 text-center">Next Due</th>
                                    <th class="p-5">Reliability</th>
                                    <th class="p-5 text-right">Trend</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50">
                                @foreach($ship->machineries as $m)
                                @php
                                    $tasks = $m->maintenanceTasks;
                                    $isCritical = $tasks->where('status', 'critical')->count() > 0;
                                @endphp
                                <tr class="hover:bg-blue-600/5 transition-colors">
                                    <td class="p-5">
                                        <p class="text-xs font-bold text-white uppercase">{{ $m->name }}</p>
                                        <p class="text-[9px] text-slate-500">{{ $m->model }}</p>
                                    </td>
                                    <td class="p-5 text-center font-mono text-[10px] text-slate-400">
                                        {{ number_format($tasks->max('last_done_rh'), 0) }} hrs
                                    </td>
                                    <td class="p-5 text-center font-mono text-[10px] {{ $isCritical ? 'text-red-500 font-bold' : 'text-emerald-500' }}">
                                        {{ number_format($tasks->min('next_due_rh'), 0) }} hrs
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-12 bg-slate-800 h-1 rounded-full overflow-hidden">
                                                <div class="bg-blue-500 h-full" style="width: {{ $isCritical ? '40%' : '95%' }}"></div>
                                            </div>
                                            <span class="text-[9px] font-bold text-slate-500">{{ $isCritical ? 'Low' : 'Optimal' }}</span>
                                        </div>
                                    </td>
                                    <td class="p-5 text-right">
                                        <i class="fas {{ $isCritical ? 'fa-chart-line text-red-500 rotate-180' : 'fa-chart-line text-emerald-500' }} text-xs"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-[#161922] border border-slate-800 p-6 rounded-3xl">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest mb-6">Recent Log Verification</h3>
                        <div class="space-y-4">
                            @foreach($ship->machineries->flatMap->maintenanceTasks->flatMap->histories->where('is_verified', true)->take(4) as $history)
                            <div class="flex gap-4 items-start border-l-2 border-emerald-500 pl-4 py-1">
                                <div>
                                    <p class="text-[10px] font-bold text-white uppercase tracking-tight">{{ $history->task->machinery->name }}</p>
                                    <p class="text-[9px] text-slate-500 italic">"{{ Str::limit($history->remarks, 40) }}"</p>
                                    <p class="text-[8px] text-slate-600 font-mono mt-1 uppercase">Verified by {{ $history->verifier->name ?? 'Chief' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <a href="#" class="block text-center mt-6 text-[9px] font-black text-slate-500 uppercase tracking-widest hover:text-blue-500 transition-colors">See Detailed Audit Log</a>
                    </div>

                    <div class="bg-red-950/10 border border-red-900/30 p-6 rounded-3xl">
                        <div class="flex items-center gap-2 mb-4">
                            <i class="fas fa-shield-alt text-red-500 text-xs"></i>
                            <h3 class="text-xs font-black text-red-500 uppercase tracking-widest">Compliance Risks</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 leading-relaxed italic">
                            Unit <span class="text-white font-bold">ME-1</span> is approaching a 5,000 hrs overhaul interval. Please ensure spare parts availability in the next procurement cycle.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>