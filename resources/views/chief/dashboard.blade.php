<x-app-layout>
    <div class="py-8 bg-[#0f111a] min-h-screen text-slate-300 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-black text-white uppercase tracking-tighter text-blue-500">Fleet Oversight</h2>
                    <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.3em] mt-1 opacity-70">Chief Engineer Command Dashboard • All Vessels</p>
                </div>
                <div class="bg-[#1a1c23]/50 border border-slate-800 px-4 py-2 rounded-xl text-right">
                    <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Global Status</p>
                    <p class="text-xs font-mono text-emerald-500 uppercase font-bold tracking-tighter">Live & Synchronized</p>
                </div>
            </div>

            @if($pendingApprovals->count() > 0)
            <section class="mb-12">
                <div class="flex justify-between items-end mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse shadow-[0_0_10px_rgba(59,130,246,0.5)]"></div>
                        <h3 class="text-[11px] font-black text-white uppercase tracking-[0.2em]">Pending Verifications</h3>
                    </div>
                    
                    @if($totalPending > 2)
                    <a href="{{ route('chief.approvals') }}" class="text-[10px] font-bold text-blue-500 hover:text-blue-400 border-b border-blue-500/30 pb-0.5 transition-all">
                        View All Pending ({{ $totalPending }}) <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    @foreach($pendingApprovals as $item)
                    <div class="bg-[#1a1c23]/80 border border-blue-900/30 p-5 rounded-2xl flex flex-col lg:flex-row justify-between items-center gap-6 hover:border-blue-500/40 transition-all shadow-xl">
                        <div class="flex items-center gap-5 w-full">
                            <div class="hidden sm:flex p-4 bg-blue-600/10 text-blue-500 rounded-2xl border border-blue-500/20">
                                <i class="fas fa-clipboard-check text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black bg-blue-600 text-white px-2 py-0.5 rounded uppercase tracking-tighter">
                                        {{ $item->task->machinery->ship->name }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-mono italic">{{ $item->completion_date }}</span>
                                </div>
                                <h5 class="text-sm font-bold text-white uppercase tracking-tight">
                                    {{ $item->task->machinery->name }} — <span class="text-slate-400 font-medium">{{ $item->task->job_details }}</span>
                                </h5>
                                <div class="mt-2 text-[11px] text-slate-500 bg-black/20 p-2 rounded border border-slate-800/50 italic">
                                    Remarks: "{{ $item->remarks }}"
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 w-full lg:w-auto">
                            <form action="{{ route('chief.verify', $item->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full lg:w-32 bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg active:scale-95">
                                    Approve
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <div class="lg:col-span-8">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-4 bg-slate-700"></div>
                        <h3 class="font-bold text-white uppercase text-xs tracking-wider">Vessel Health Status</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($ships as $ship)
                            @php
                                $tasks = $ship->machineries->flatMap->maintenanceTasks;
                                $critCount = $tasks->where('status', 'critical')->count();
                                $warnCount = $tasks->where('status', 'warning')->count();
                                
                                $statusBorder = ($critCount > 0) ? 'border-red-600/50 bg-red-950/5' : (($warnCount > 0) ? 'border-yellow-600/50 bg-yellow-950/5' : 'border-slate-800 bg-[#1a1c23]/40');
                                $dotColor = ($critCount > 0) ? 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]' : (($warnCount > 0) ? 'bg-yellow-500' : 'bg-emerald-500');
                            @endphp

                            <div class="p-6 border {{ $statusBorder }} rounded-2xl transition-all hover:bg-slate-900 group">
                                <div class="flex justify-between items-start mb-5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $dotColor }}"></div>
                                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Live</span>
                                    </div>
                                    <a href="{{ route('chief.inspect', $ship->id) }}" class="text-[9px] font-bold text-blue-500 hover:text-white uppercase">Inspect →</a>
                                </div>
                                <h4 class="text-xl font-black text-white uppercase tracking-tighter mb-1">{{ $ship->name }}</h4>
                                <p class="text-[10px] text-slate-600 uppercase font-bold">{{ $ship->machineries->count() }} Units</p>
                                <div class="mt-6 flex gap-6 border-t border-slate-800/50 pt-5">
                                    <div>
                                        <p class="text-[9px] font-bold text-red-500 uppercase">Critical</p>
                                        <p class="text-lg font-mono font-bold text-white">{{ $critCount }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-bold text-yellow-500 uppercase">Warning</p>
                                        <p class="text-lg font-mono font-bold text-white">{{ $warnCount }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-4 bg-slate-700"></div>
                        <h3 class="font-bold text-white uppercase text-xs tracking-wider">Fleet Activities</h3>
                    </div>
                    <div class="bg-[#1a1c23]/40 border border-slate-800 rounded-3xl overflow-hidden p-2">
                        <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
                            @foreach($recentActivities as $activity)
                                <div class="p-4 mb-2 bg-slate-900/50 border border-slate-800/50 rounded-2xl">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[8px] font-black bg-slate-800 text-slate-400 px-2 py-0.5 rounded uppercase border border-slate-700">
                                            {{ $activity->task->machinery->ship->name }}
                                        </span>
                                        <span class="text-[9px] text-slate-600 font-mono italic">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h5 class="text-xs font-bold text-white mb-1 leading-tight">{{ $activity->task->machinery->name }}</h5>
                                    <p class="text-[10px] text-slate-500 italic">"{{ Str::limit($activity->remarks, 50) }}"</p>
                                    @if($activity->is_verified)
                                        <div class="mt-2 flex items-center gap-1 text-[8px] font-bold text-emerald-500 uppercase">
                                            <i class="fas fa-check-double"></i> Verified
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>